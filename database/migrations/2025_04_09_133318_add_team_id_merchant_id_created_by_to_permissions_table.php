<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->after('guard_name');
            $table->unsignedBigInteger('merchant_id')->nullable()->after('team_id');
            $table->unsignedBigInteger('created_by')->nullable()->after('merchant_id');

            $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
            $table->foreign('merchant_id')->references('id')->on('merchants')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['merchant_id']);
            $table->dropForeign(['created_by']);

            $table->dropColumn(['team_id', 'merchant_id', 'created_by']);
        });
    }
};
