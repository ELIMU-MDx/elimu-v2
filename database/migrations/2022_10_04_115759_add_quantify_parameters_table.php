<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quantify_parameters', function(Blueprint $table) {
            $table->id();

            $table->foreignId('experiment_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('target');
            $table->decimal('slope');
            $table->decimal('intercept');
            $table->timestamps();
        });
    }
};
