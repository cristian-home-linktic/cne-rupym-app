<?php

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/** @var Command $this */
Artisan::command('inspire', function () {
    /** @var Command $command */
    $command = $this;
    $command->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
