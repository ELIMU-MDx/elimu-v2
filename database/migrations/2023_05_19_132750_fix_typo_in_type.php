<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('measurements')
            ->where('type', 'POSTIVE_CONTROL')
            ->update(['type' => 'POSITIVE_CONTROL']);
    }
};
