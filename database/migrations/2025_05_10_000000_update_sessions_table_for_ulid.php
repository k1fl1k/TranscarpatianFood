<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Спочатку видаляємо всі сесії, щоб уникнути проблем з міграцією
        Schema::table('sessions', function (Blueprint $table) {
            DB::table('sessions')->truncate();
        });

        // Змінюємо тип поля user_id з bigint на string
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->string('user_id', 26)->nullable()->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Спочатку видаляємо всі сесії, щоб уникнути проблем з міграцією
        Schema::table('sessions', function (Blueprint $table) {
            DB::table('sessions')->truncate();
        });

        // Повертаємо тип поля user_id на bigint
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index()->after('id');
        });
    }
};
