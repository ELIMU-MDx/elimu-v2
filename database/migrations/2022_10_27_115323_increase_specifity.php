<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            $table->decimal('cq', 18, 15)->nullable()->change();
        });
        Schema::table('quantify_parameters', function (Blueprint $table) {
            $table->decimal('slope', 10, 8)->change();
            $table->decimal('intercept', 10, 8)->change();
        });
    }
};
