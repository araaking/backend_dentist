<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->string('type', 2)->nullable()->after('question_code'); // 'SQ' | 'EQ'
        });

        // Isi data lama berdasarkan kode pertanyaan.
        DB::table('answers')->where('question_code', 'like', 'SQ%')->update(['type' => 'SQ']);
        DB::table('answers')->where('question_code', 'like', 'E%')->update(['type' => 'EQ']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
