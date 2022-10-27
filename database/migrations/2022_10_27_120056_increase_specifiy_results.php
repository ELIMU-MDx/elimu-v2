<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function(Blueprint $table) {
            $table->decimal('cq', 18, 15)->nullable()->change();
        });
    }
};
