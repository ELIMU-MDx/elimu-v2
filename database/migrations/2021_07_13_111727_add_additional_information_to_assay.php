<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assays', function (Blueprint $table) {
            $table->string('sample_type')->after('name');
            $table->foreignId('study_id')->after('id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
