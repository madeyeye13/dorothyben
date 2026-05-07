<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('currency', 10)->default('NGN'); // NGN or USD
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('sort_code')->nullable();
            $table->string('routing_number')->nullable(); // For USD
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
