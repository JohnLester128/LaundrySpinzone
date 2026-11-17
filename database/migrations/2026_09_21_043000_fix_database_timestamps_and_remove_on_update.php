<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Remove ON UPDATE CURRENT_TIMESTAMP from started_at in service_request_status_histories
        DB::statement("ALTER TABLE `service_request_status_histories` MODIFY COLUMN `started_at` TIMESTAMP NULL DEFAULT NULL");

        // 2. Remove ON UPDATE CURRENT_TIMESTAMP from sold_at in sales
        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `sold_at` TIMESTAMP NULL DEFAULT NULL");

        // 3. Normalize existing status histories:
        //    For rows where started_at was overwritten by ON UPDATE CURRENT_TIMESTAMP to the end time,
        //    created_at holds the true start time.
        //    All past timestamps were recorded in UTC (offset -8h from Asia/Manila).
        //    Shift them by +8 hours to align with Asia/Manila.
        DB::statement("
            UPDATE `service_request_status_histories`
            SET `started_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR),
                `ended_at` = CASE WHEN `ended_at` IS NOT NULL THEN DATE_ADD(`ended_at`, INTERVAL 8 HOUR) ELSE NULL END,
                `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR),
                `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)
        ");

        // 4. Shift past records in service_requests by +8 hours to align with Asia/Manila
        DB::statement("
            UPDATE `service_requests`
            SET `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR),
                `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR),
                `wash_started_at` = CASE WHEN `wash_started_at` IS NOT NULL THEN DATE_ADD(`wash_started_at`, INTERVAL 8 HOUR) ELSE NULL END,
                `dry_started_at` = CASE WHEN `dry_started_at` IS NOT NULL THEN DATE_ADD(`dry_started_at`, INTERVAL 8 HOUR) ELSE NULL END,
                `cycle_paused_at` = CASE WHEN `cycle_paused_at` IS NOT NULL THEN DATE_ADD(`cycle_paused_at`, INTERVAL 8 HOUR) ELSE NULL END
        ");

        // 5. Shift past records in sales
        DB::statement("
            UPDATE `sales`
            SET `sold_at` = DATE_ADD(`sold_at`, INTERVAL 8 HOUR),
                `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR),
                `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)
        ");

        // 6. Shift past records in login_logs
        DB::statement("
            UPDATE `login_logs`
            SET `login_at` = CASE WHEN `login_at` IS NOT NULL THEN DATE_ADD(`login_at`, INTERVAL 8 HOUR) ELSE NULL END,
                `logout_at` = CASE WHEN `logout_at` IS NOT NULL THEN DATE_ADD(`logout_at`, INTERVAL 8 HOUR) ELSE NULL END,
                `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR),
                `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)
        ");

        // 7. Shift past records in feedbacks, customers, admins, branches, machines
        DB::statement("UPDATE `feedbacks` SET `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR), `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)");
        DB::statement("UPDATE `customers` SET `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR), `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)");
        DB::statement("UPDATE `admins` SET `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR), `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)");
        DB::statement("UPDATE `branches` SET `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR), `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)");
        DB::statement("UPDATE `machines` SET `created_at` = DATE_ADD(`created_at`, INTERVAL 8 HOUR), `updated_at` = DATE_ADD(`updated_at`, INTERVAL 8 HOUR)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert columns back
        DB::statement("ALTER TABLE `service_request_status_histories` MODIFY COLUMN `started_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `sold_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
};
