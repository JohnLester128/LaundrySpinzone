<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::table('service_requests', function (Blueprint $table) { $table->foreignId('washing_machine_id')->nullable()->after('accepted_by')->constrained('machines')->nullOnDelete(); $table->foreignId('dryer_machine_id')->nullable()->after('washing_machine_id')->constrained('machines')->nullOnDelete(); }); } public function down(): void { Schema::table('service_requests', function (Blueprint $table) { $table->dropForeign(['washing_machine_id']); $table->dropForeign(['dryer_machine_id']); $table->dropColumn(['washing_machine_id', 'dryer_machine_id']); }); } };
