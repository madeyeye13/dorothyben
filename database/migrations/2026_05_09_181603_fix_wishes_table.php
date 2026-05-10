<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Fix wishes table - add missing columns
        Schema::table('wishes', function (Blueprint $table) {
            if (!Schema::hasColumn('wishes', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('wishes', 'message')) {
                $table->text('message')->after('name');
            }
            if (!Schema::hasColumn('wishes', 'approved')) {
                $table->boolean('approved')->default(false)->after('message');
            }
            if (!Schema::hasColumn('wishes', 'admin_reply')) {
                $table->text('admin_reply')->nullable()->after('approved');
            }
            if (!Schema::hasColumn('wishes', 'replied_at')) {
                $table->timestamp('replied_at')->nullable()->after('admin_reply');
            }
            if (!Schema::hasColumn('wishes', 'heart_count')) {
                $table->unsignedInteger('heart_count')->default(0)->after('replied_at');
            }
            if (!Schema::hasColumn('wishes', 'congrats_count')) {
                $table->unsignedInteger('congrats_count')->default(0)->after('heart_count');
            }
        });

        // Create wish_reactions table if it doesn't exist
        if (!Schema::hasTable('wish_reactions')) {
            Schema::create('wish_reactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wish_id')->constrained()->cascadeOnDelete();
                $table->string('reaction_type'); // heart | congrats
                $table->string('session_id');
                $table->timestamps();
                $table->unique(['wish_id', 'reaction_type', 'session_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wish_reactions');
        Schema::table('wishes', function (Blueprint $table) {
            $table->dropColumn(['admin_reply', 'replied_at', 'heart_count', 'congrats_count']);
        });
    }
};