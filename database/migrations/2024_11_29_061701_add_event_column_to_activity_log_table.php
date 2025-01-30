<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventColumnToActivityLogTable extends Migration
{
    public function up(): void
    {
        Schema::connection(
            $this->getActivityLogConnection()
        )->table(config()->string('activitylog.table_name'), function (Blueprint $table) {
            $table->string('event')->nullable()->after('subject_type');
        });
    }

    public function down(): void
    {
        Schema::connection(
            $this->getActivityLogConnection()
        )->table(config()->string('activitylog.table_name'), function (Blueprint $table) {
            $table->dropColumn('event');
        });
    }

    public function getActivityLogConnection(): ?string
    {
        return is_string($connection = config('activitylog.database_connection')) ? $connection : null;
    }
}
