<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assays', function (Blueprint $table) {
            $table->dropConstrainedForeignId('study_id');
        });
        DB::table('assays')->delete();
        Schema::table('assays', function (Blueprint $table) {
            $table->foreignId('study_id')->after('id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
};
