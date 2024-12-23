<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\UseItem;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;
use RuntimeException;
use Illuminate\Contracts\Console\PromptsForMissingInput;

/**
 * Artisan command to add a trait to all models in the application.
 */
final class AddTraitToModelsCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the command.
     *
     * @var string
     */
    protected $signature = 'models:add-trait
        {fqcn : The fully qualified class name of the trait}
        {--a|alias= : Optional alias for the trait}
        {--m|models-path= : The path to the models directory}
        {--force : Force the operation to run when in production}
        ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add a trait to all models in the application';

    /**
     * @var string
     */
    private $modelsPath;

    public function __construct()
    {
        parent::__construct();

        $this->modelsPath = app_path('Models');
    }

    /**
     * Execute the console command.
     *
     * @throws \RuntimeException
     */
    public function handle(): int
    {
        $traitFqcn = $this->argument('fqcn');
        $alias = $this->option('alias');
        $modelsPath = $this->option('models-path');
        $force = $this->option('force');

        $this->info('Adding trait \'' . $traitFqcn . '\' to all models in the application...');

        if (!$force && app()->environment('production')) {
            $this->error('Running this command in production is not allowed. Use the --force option to continue.');

            return 1;
        }

        if (empty($traitFqcn)) {
            $this->error('The --fqcn option is required.');

            return 1;
        }

        if (!$modelsPath) {
            $this->modelsPath = app_path('Models');
        } else {
            $this->modelsPath = base_path($modelsPath);
        }

        if (! $this->validateTrait($traitFqcn)) {
            $this->error("The trait {$traitFqcn} is not valid or not available in the project.");

            return 1;
        }

        $modelFiles = $this->findModelFiles();

        if (empty($modelFiles)) {
            $this->warn('No model files found.');

            return 0;
        }

        $successCount = 0;
        foreach ($modelFiles as $modelFile) {
            try {
                $this->processModelFile($modelFile, $traitFqcn, $alias);
                $successCount++;
            } catch (\Exception $e) {
                $this->error("Failed to process {$modelFile}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully processed {$successCount} model files.");

        return 0;
    }

    /**
     * Find all model files in the configured path.
     *
     * @return array<string>
     */
    private function findModelFiles(): array
    {
        if (! File::isDirectory($this->modelsPath)) {
            throw new RuntimeException("Models directory not found: {$this->modelsPath}");
        }

        /** @var array<string> */
        $filesArray = File::glob($this->modelsPath . '/*.php') ?: [];

        return $filesArray;
    }

    /**
     * Validate that the given trait exists and is actually a trait.
     */
    private function validateTrait(string $traitFqcn): bool
    {
        try {
            if (! trait_exists($traitFqcn)) {
                return false;
            }

            $reflection = new ReflectionClass($traitFqcn);

            return $reflection->isTrait();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Process a single model file.
     *
     * @throws \RuntimeException
     */
    private function processModelFile(string $filePath, string $traitFqcn, ?string $alias): void
    {
        // Read file
        $code = File::get($filePath);

        // Parser
        $parser = (new ParserFactory())->createForHostVersion();

        // Run CloningVisitor before making changes to the AST.
        $traverser = new NodeTraverser(new CloningVisitor());

        // Builder factory
        $factory = new BuilderFactory;

        // Printer
        $printer = new Standard();

        // Node finder
        $nodeFinder = new NodeFinder;

        $oldStmts = $parser->parse($code) ?: [];
        $oldTokens = $parser->getTokens();
        $newStmts = $traverser->traverse($oldStmts);
        $classNode = $nodeFinder->findFirstInstanceOf($newStmts, Node\Stmt\Class_::class);

        if (!$classNode) {
            throw new RuntimeException("No class definition found in {$filePath}");
        }

        // Check if trait is already used
        $existingTraitUses = $nodeFinder->findInstanceOf($classNode, TraitUse::class);
        foreach ($existingTraitUses as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                $traitName = $trait->toString();
                if ($traitName === $traitFqcn || ($alias && $traitName === $alias) || $traitName === (new Name($traitFqcn))->getLast()) {
                    $this->info("Trait already used in {$filePath}");
                    return;
                }
            }
        }

        // Add use statement if not exists
        $useStatements = $nodeFinder->findInstanceOf($newStmts, UseItem::class);
        $useExists = false;
        foreach ($useStatements as $use) {
            if ($use->name->toString() === $traitFqcn) {
                $useExists = true;
                break;
            }
        }

        if (! $useExists) {
            $useNode = $factory->use($traitFqcn);
            if ($alias) {
                $useNode = $useNode->as($alias);
            }

            // Find the position to insert the use statement
            $namespaceNode = $nodeFinder->findFirstInstanceOf($newStmts, Node\Stmt\Namespace_::class);

            if ($namespaceNode !== null) {
                array_splice($namespaceNode->stmts, 0, 0, [$useNode->getNode()]);
            } else {
                array_splice($newStmts, 0, 0, [$useNode->getNode()]);
            }
        }

        $traitName = $alias ?: (new Name($traitFqcn))->getLast();
        $newTraitUse = new TraitUse([new Name($traitName)]);

        if (empty($existingTraitUses)) {
            $classNode->stmts = array_merge([$newTraitUse], $classNode->stmts);
        } else {
            $existingTraits = $existingTraitUses[0]->traits;
            $existingTraits[] = new Name($traitName);
            $existingTraitUses[0]->traits = $existingTraits;
        }

        // Save modified file
        $newCode = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);
        File::put($filePath, $newCode);

        $this->info("Successfully added trait to {$filePath}");
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string|array<string>> The questions to ask the user
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'fqcn' => ['Enter the fully qualified class name of the trait', 'e.g. Illuminate\\Database\\Eloquent\\SoftDeletes'],
        ];
    }
}
