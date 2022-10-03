<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assay_parameters', function (Blueprint $table) {
            $table->after('intercept', function (Blueprint $table) {
                $table->string('positive_control')->nullable();
                $table->string('negative_control')->nullable();
                $table->string('ntc_control')->nullable();
            });
        });
    }
};
