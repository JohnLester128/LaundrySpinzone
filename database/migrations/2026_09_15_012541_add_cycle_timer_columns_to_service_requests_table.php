<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('service_requests', 'cycle_phase')) {
                $table->string('cycle_phase')->default('idle')->after('status');
            }
            if (!Schema::hasColumn('service_requests', 'wash_started_at')) {
                $table->timestamp('wash_started_at')->nullable()->after('cycle_phase');
            }
            if (!Schema::hasColumn('service_requests', 'wash_duration_minutes')) {
                $table->integer('wash_duration_minutes')->default(33)->after('wash_started_at');
            }
            if (!Schema::hasColumn('service_requests', 'rest_duration_minutes')) {
                $table->integer('rest_duration_minutes')->default(5)->after('wash_duration_minutes');
            }
            if (!Schema::hasColumn('service_requests', 'dry_started_at')) {
                $table->timestamp('dry_started_at')->nullable()->after('rest_duration_minutes');
            }
            if (!Schema::hasColumn('service_requests', 'dry_duration_minutes')) {
                $table->integer('dry_duration_minutes')->nullable()->after('dry_started_at');
            }
            if (!Schema::hasColumn('service_requests', 'cycle_paused_at')) {
                $table->timestamp('cycle_paused_at')->nullable()->after('dry_duration_minutes');
            }
            if (!Schema::hasColumn('service_requests', 'paused_remaining_seconds')) {
                $table->integer('paused_remaining_seconds')->nullable()->after('cycle_paused_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $cols = [
                'cycle_phase',
                'wash_started_at',
                'wash_duration_minutes',
                'rest_duration_minutes',
                'dry_started_at',
                'dry_duration_minutes',
                'cycle_paused_at',
                'paused_remaining_seconds',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('service_requests', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
