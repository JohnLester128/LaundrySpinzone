<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('service_request_addons', function (Blueprint $table) { $table->id(); $table->foreignId('service_request_id')->constrained()->cascadeOnDelete(); $table->foreignId('addon_id')->constrained()->restrictOnDelete(); $table->decimal('price', 10, 2); $table->timestamps(); $table->unique(['service_request_id', 'addon_id']); }); }
    public function down(): void { Schema::dropIfExists('service_request_addons'); }
};
