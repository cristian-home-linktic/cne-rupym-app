<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    public function up(): void
    {
        Schema::connection(
            $this->getActivityLogConnection()
        )->create(config()->string('activitylog.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->index('log_name');
        });
    }

    public function down(): void
    {
        Schema::connection(
            $this->getActivityLogConnection()
        )->dropIfExists(config()->string('activitylog.table_name'));
    }

    public function getActivityLogConnection(): ?string
    {
        return is_string($connection = config('activitylog.database_connection')) ? $connection : null;
    }
}
