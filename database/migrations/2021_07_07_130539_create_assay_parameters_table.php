<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assay_parameters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assay_id')->constrained();
            $table->string('target');
            $table->integer('required_repetitions');
            $table->float('cutoff');
            $table->float('standard_deviation_cutoff');
            $table->float('slope')->nullable();
            $table->float('intercept')->nullable();

            $table->timestamps();
        });
    }
};
