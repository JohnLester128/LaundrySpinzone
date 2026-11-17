<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->string('status', 25)->default('open')->after('nature'); // 'open', 'in_progress', 'resolved', 'closed'
            $table->timestamp('resolved_at')->nullable()->after('aspects');
            $table->foreignId('resolved_by')->nullable()->after('resolved_at')->constrained('admins')->nullOnDelete();
            $table->text('resolution_note')->nullable()->after('resolved_by');
        });

        Schema::create('feedback_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('feedbacks')->cascadeOnDelete();
            $table->string('sender_type', 20); // 'admin' or 'customer'
            $table->unsignedBigInteger('sender_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_replies');

        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropColumn(['status', 'resolved_at', 'resolved_by', 'resolution_note']);
        });
    }
};
