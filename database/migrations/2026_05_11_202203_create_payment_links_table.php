<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // e.g. "Gift us via Zelle (USD)"
            $table->string('url');          // e.g. "https://paypal.me/dorothy"
            $table->string('description')->nullable(); // e.g. "For US & UK friends"
            $table->string('currency_tag')->nullable(); // e.g. "USD", "GBP" — just a label
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_links');
    }
};