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
            $table->string('nature', 20)->default('feedback')->after('branch_id'); // 'feedback' or 'complaint'
            $table->tinyInteger('rating')->unsigned()->nullable()->change(); // Nullable for complaints
            $table->string('urgency', 20)->nullable()->after('rating'); // 'normal' or 'urgent'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn(['nature', 'urgency']);
            $table->tinyInteger('rating')->unsigned()->nullable(false)->change();
        });
    }
};
