<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurement_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('cycle');
            $table->decimal('temperature')->nullable();
            $table->decimal('fluor')->nullable();
            $table->timestamps();
        });
    }
};
