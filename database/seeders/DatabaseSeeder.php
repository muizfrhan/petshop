<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder utama: menjalankan seluruh seeder dalam urutan yang benar
 * (master data -> transaksi -> konten).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            ArticleSeeder::class,
            CouponSeeder::class,
            TransactionSeeder::class,
            SettingSeeder::class,
        ]);

        $this->command?->newLine();
        $this->command?->info('=======================================================');
        $this->command?->info(' PETCARE HOUSE - Seeder selesai');
        $this->command?->info('=======================================================');
        $this->command?->info(' Admin    : admin@petcarehouse.test / password');
        $this->command?->info(' Customer : budi@mail.test / password');
        $this->command?->newLine();
        $this->command?->warn(' KREDENSIAL INI KHUSUS DEVELOPMENT.');
        $this->command?->warn(' Ganti password sebelum deploy ke production.');
        $this->command?->info('=======================================================');
    }
}