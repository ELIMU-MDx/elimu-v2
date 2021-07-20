<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            $table->foreignId('result_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
