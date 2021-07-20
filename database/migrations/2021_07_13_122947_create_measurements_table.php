<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experiment_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sample_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->float('cq')->nullable();
            $table->string('target');
            $table->string('position')->nullable();

            $table->boolean('excluded')->default(false);
            $table->timestamps();
        });
    }
};
