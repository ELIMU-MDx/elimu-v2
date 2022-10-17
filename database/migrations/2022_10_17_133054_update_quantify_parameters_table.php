<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quantify_parameters', function(Blueprint $table) {
            $table->decimal('slope', 8, 4)->change();
            $table->decimal('intercept', 8, 4)->change();
        });
    }
};
