<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SiteSetting;

return new class extends Migration {
    public function up(): void
    {
        // Just seed the default — site_settings table already exists
        SiteSetting::firstOrCreate(['key' => 'venue_pin'], ['value' => '']);
    }

    public function down(): void
    {
        \App\Models\SiteSetting::where('key', 'venue_pin')->delete();
    }
};