<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('UPDATE sales INNER JOIN service_requests ON service_requests.id = sales.service_request_id SET sales.amount = service_requests.final_price WHERE service_requests.final_price IS NOT NULL');
        DB::statement('DELETE sales FROM sales INNER JOIN service_requests ON service_requests.id = sales.service_request_id WHERE service_requests.final_price IS NULL');
    }

    public function down(): void {}
};
