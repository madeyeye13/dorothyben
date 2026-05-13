<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SiteSetting;

return new class extends Migration {
    public function up(): void
    {
        SiteSetting::firstOrCreate(['key' => 'our_story_hero'], ['value' => '']);
    }

    public function down(): void
    {
        SiteSetting::where('key', 'our_story_hero')->delete();
    }
};