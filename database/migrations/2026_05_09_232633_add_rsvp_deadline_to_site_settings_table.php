<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SiteSetting;

return new class extends Migration {
    public function up(): void
    {
        SiteSetting::firstOrCreate(['key' => 'rsvp_deadline'], ['value' => '']);
    }

    public function down(): void
    {
        \App\Models\SiteSetting::where('key', 'rsvp_deadline')->delete();
    }
};