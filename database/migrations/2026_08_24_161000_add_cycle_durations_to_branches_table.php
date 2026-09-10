<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->integer('washing_time_minutes')->default(35)->after('address');
            $table->integer('drying_time_minutes')->default(45)->after('washing_time_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['washing_time_minutes', 'drying_time_minutes']);
        });
    }
};
