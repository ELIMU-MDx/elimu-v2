<?php

use Domain\Assay\Models\AssayParameter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        AssayParameter::query()
            ->update(['description' => DB::raw('target')]);

        Schema::table('assay_parameters', function (Blueprint $table) {
            $table->string('description')->change();
        });
    }
};
