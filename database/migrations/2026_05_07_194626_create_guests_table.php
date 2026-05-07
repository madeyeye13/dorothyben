<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('attending', ['yes', 'no'])->default('yes');
            $table->text('decline_reason')->nullable();
            $table->json('relationship')->nullable(); // ['groom_friend','bride_friend','family']
            $table->string('qr_token')->unique()->nullable();
            $table->boolean('qr_used')->default(false);
            $table->timestamp('qr_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
