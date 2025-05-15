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
        Schema::table('users', function (Blueprint $table) {
            // Add additional user fields
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable()->default('Ukraine');
            $table->date('birth_date')->nullable();
            $table->enum('role', ['customer', 'admin'])->default('customer');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the additional fields
            $table->dropColumn([
                'phone', 'address', 'city', 'postal_code', 'country',
                'birth_date', 'role', 'avatar', 'bio', 'last_login_at'
            ]);
        });
    }
};
