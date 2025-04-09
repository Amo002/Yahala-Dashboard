<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('merchant_id')->nullable()->after('team_id');
            $table->unsignedBigInteger('created_by')->nullable()->after('merchant_id');

            $table->foreign('merchant_id')->references('id')->on('merchants')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['merchant_id']);
            $table->dropForeign(['created_by']);

            $table->dropColumn(['merchant_id', 'created_by']);
        });
    }
};
