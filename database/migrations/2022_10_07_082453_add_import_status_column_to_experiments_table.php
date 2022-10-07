<?php

use Domain\Experiment\Enums\ImportStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experiments', function (Blueprint $table) {
            $table->string('import_status')->default(ImportStatus::PENDING->name)->after('user_id');
        });
    }
};
