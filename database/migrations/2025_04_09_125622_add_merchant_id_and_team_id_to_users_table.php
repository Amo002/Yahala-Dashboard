<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('merchant_id')->nullable()->after('id');
            $table->unsignedBigInteger('team_id')->nullable()->after('merchant_id');

            $table->foreign('merchant_id')->references('id')->on('merchants')->nullOnDelete();
            $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['merchant_id']);
            $table->dropForeign(['team_id']);
            $table->dropColumn(['merchant_id', 'team_id']);
        });
    }
};
