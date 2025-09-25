<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) $table->string('first_name')->nullable()->after('id');
            if (!Schema::hasColumn('users', 'last_name')) $table->string('last_name')->nullable()->after('first_name');
            if (!Schema::hasColumn('users', 'phone')) $table->string('phone')->nullable()->after('email');
            if (!Schema::hasColumn('users', 'address')) $table->string('address')->nullable()->after('phone');
            if (!Schema::hasColumn('users', 'date_of_birth')) $table->date('date_of_birth')->nullable()->after('address');
            if (!Schema::hasColumn('users', 'role')) $table->string('role')->default('membre')->after('date_of_birth');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'first_name')) $table->dropColumn('first_name');
            if (Schema::hasColumn('users', 'last_name')) $table->dropColumn('last_name');
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('users', 'address')) $table->dropColumn('address');
            if (Schema::hasColumn('users', 'date_of_birth')) $table->dropColumn('date_of_birth');
            if (Schema::hasColumn('users', 'role')) $table->dropColumn('role');
        });
    }
};
