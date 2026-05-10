<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SiteSetting;

return new class extends Migration {
    public function up(): void
    {
        SiteSetting::firstOrCreate(['key' => 'memories_activation_date'], ['value' => '']);
    }

    public function down(): void
    {
        SiteSetting::where('key', 'memories_activation_date')->delete();
    }
};