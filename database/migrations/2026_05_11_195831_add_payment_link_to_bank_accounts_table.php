<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('payment_link')->nullable()->after('routing_number');
            $table->string('payment_link_label')->nullable()->after('payment_link');
            // e.g. label: "Send via Zelle", link: "https://enroll.zellepay.com/..."
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn(['payment_link', 'payment_link_label']);
        });
    }
};