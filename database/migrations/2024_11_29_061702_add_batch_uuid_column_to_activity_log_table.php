<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchUuidColumnToActivityLogTable extends Migration
{
    public function up(): void
    {
        Schema::connection(
            $this->getActivityLogConnection()
        )->table(config()->string('activitylog.table_name'), function (Blueprint $table) {
            $table->uuid('batch_uuid')->nullable()->after('properties');
        });
    }

    public function down(): void
    {
        Schema::connection(
            $this->getActivityLogConnection()
        )->table(config()->string('activitylog.table_name'), function (Blueprint $table) {
            $table->dropColumn('batch_uuid');
        });
    }

    public function getActivityLogConnection(): ?string
    {
        return is_string($connection = config('activitylog.database_connection')) ? $connection : null;
    }
}
