<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
         | KREDENSIAL DEVELOPMENT - WAJIB DIGANTI DI PRODUCTION
         | admin@petcarehouse.test / password  -> akun admin
         | budi@mail.test / password          -> akun customer contoh
         |
         | Jalankan `php artisan db:seed --class=Database\\Seeders\\UserSeeder`
         | ulang HANYA di lingkungan lokal/development.
         | Untuk production: hapus block ini atau ubah password secara manual.
         */
        $admin = User::updateOrCreate(
            ['email' => 'admin@petcarehouse.test'],
            [
                'name' => 'Administrator PETCARE',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ],
        );

        $customers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@mail.test', 'phone' => '081298765432'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@mail.test', 'phone' => '085611223344'],
        ];

        foreach ($customers as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_CUSTOMER,
                    'status' => User::STATUS_ACTIVE,
                    'email_verified_at' => now(),
                ],
            );
        }

        // Beberapa pelanggan tambahan agar grafik & laporan terlihat nyata
        User::factory()
            ->count(8)
            ->create(['role' => User::ROLE_CUSTOMER]);

        // Satu akun nonaktif untuk mendemonstrasikan proteksi middleware
        User::factory()->inactive()->create([
            'email' => 'nonaktif@mail.test',
        ]);
    }
}