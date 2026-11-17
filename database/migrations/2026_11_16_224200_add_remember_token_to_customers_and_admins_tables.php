<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('customers', 'remember_token')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->rememberToken()->after('security_answer');
            });
        }

        if (!Schema::hasColumn('admins', 'remember_token')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->rememberToken()->after('password');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'remember_token')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropRememberToken();
            });
        }

        if (Schema::hasColumn('admins', 'remember_token')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropRememberToken();
            });
        }
    }
};

