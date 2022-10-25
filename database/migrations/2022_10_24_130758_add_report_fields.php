<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assay_parameters', function(Blueprint $table) {
            $table->after('target', function(Blueprint $table) {
                $table->string('description')->nullable();
                $table->boolean('is_control')->default(false);
            });
        });
    }
};
