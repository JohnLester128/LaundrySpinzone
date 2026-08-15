<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration { public function up(): void { DB::table('service_requests')->whereIn('status', ['pending', 'in_queue'])->update(['status' => 'received']); DB::table('service_requests')->where('status', 'transfer')->update(['status' => 'washing']); DB::table('service_request_status_histories')->whereIn('status', ['pending', 'in_queue'])->update(['status' => 'received']); DB::table('service_request_status_histories')->where('status', 'transfer')->update(['status' => 'washing']); } public function down(): void {} };
