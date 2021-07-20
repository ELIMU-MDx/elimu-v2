<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('target');
            $table->float('cq')->nullable();
            $table->float('quantification')->nullable();
            $table->string('qualification');
            $table->float('standard_deviation')->nullable();

            $table->timestamps();
        });
    }
};
