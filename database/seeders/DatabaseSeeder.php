<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Spatie Roles ──
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
        $scanner    = Role::firstOrCreate(['name' => 'scanner',     'guard_name' => 'web']);

        // ── Primary Admin ──
        $user = User::firstOrCreate(
            ['email' => 'doroegede@yahoo.com'],
            [
                'name'     => 'Dorothy Egede',
                'password' => Hash::make('Qwertyuiop123'),
            ]
        );
        $user->assignRole($superAdmin);

        // ── Default Site Settings ──
        $defaults = [
            'rsvp_enabled'     => '1',
            'gallery_password' => '',
            'memories_active'  => '0',
            'hero_image'       => '',
        ];
        foreach ($defaults as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // ── Bank Accounts ──
        if (BankAccount::count() === 0) {
            // NGN — Kuda
            BankAccount::create([
                'currency'     => 'NGN',
                'bank_name'    => 'Kuda Bank',
                'account_name' => 'Dorothy Egede',
                'account_number' => '2001718997',
                'sort_order'   => 1,
            ]);
            // USD — Zelle
            BankAccount::create([
                'currency'           => 'USD',
                'bank_name'          => 'Zelle',
                'account_name'       => 'Ifechukwu Nwogu',
                'account_number'     => '206-607-7405',
                'payment_link'       => 'https://enroll.zellepay.com/',
                'payment_link_label' => 'Send via Zelle',
                'sort_order'         => 2,
            ]);
        }
    }
}