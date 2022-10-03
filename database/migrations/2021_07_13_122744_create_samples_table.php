<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('identifier');

            $table->timestamps();

            $table->unique(['study_id', 'identifier']);
        });
    }
};
