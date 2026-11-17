<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->integer('wash_buffer_minutes')->default(5)->after('drying_time_minutes');
            $table->integer('dry_buffer_minutes')->default(5)->after('wash_buffer_minutes');
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->integer('dry_rest_duration_minutes')->default(5)->after('dry_duration_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['wash_buffer_minutes', 'dry_buffer_minutes']);
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['dry_rest_duration_minutes']);
        });
    }
};
