<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Migrate existing full_name data
        DB::statement("UPDATE guests SET first_name = SUBSTRING_INDEX(full_name, ' ', 1), last_name = SUBSTRING(full_name, LOCATE(' ', full_name) + 1) WHERE full_name IS NOT NULL AND full_name != ''");
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};