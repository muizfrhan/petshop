<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Layanan sesuai template. Prompt menyebut minimal 5 layanan
 * (Grooming, Pet Hotel, Veterinary, Vaksinasi, Spa, Pet Taxi, Home Service);
 * ketujuhnya dipakai agar section "Layanan Care" terisi penuh.
 */
class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Grooming Salon & Spa',
                'icon' => 'shower',
                'price' => 65_000,
                'duration_minutes' => 90,
                'description' => 'Layanan grooming lengkap untuk anjing dan kucing: mandi antiseptik, potong kuku, pembersihan telinga, perapihan bulu, dan blow dry dengan wangi tahan hingga 7 hari.',
                'includes' => "Mandi antiseptik\nPotong kuku dan rawat cakar\nPembersihan telinga\nTrimming dan perapian bulu\nBlow dry plus parfum 7 hari",
                'featured' => true,
            ],
            [
                'name' => 'Pet Hotel Mewah',
                'icon' => 'hotel',
                'price' => 175_000,
                'duration_minutes' => 1440,
                'description' => 'Penitipan harian dengan kamar ber-AC, kandang yang bersih, dan pemantauan tiga kali sehari oleh pet handler berpengalaman.',
                'includes' => "Kamar privat ber-AC\nMakan sesuai jadwal\nPemantauan tiga kali sehari\nMainan dan permainan interaktif\nLaporan foto harian",
                'featured' => true,
            ],
            [
                'name' => 'Klinik & Dokter Hewan',
                'icon' => 'medical_services',
                'price' => 150_000,
                'duration_minutes' => 60,
                'description' => 'Pemeriksaan kesehatan menyeluruh oleh dokter hewan berlisensi, termasuk pemeriksaan rutin dan diagnosis yang tepat.',
                'includes' => "Konsultasi dokter hewan\nPemeriksaan fisik lengkap\nPemeriksaan darah dan urine\nDiagnosis dan rencana perawatan\nObat atau tindakan sesuai kebutuhan",
                'featured' => true,
            ],
            [
                'name' => 'Vaksinasi & Microchip',
                'icon' => 'vaccines',
                'price' => 250_000,
                'duration_minutes' => 45,
                'description' => 'Vaksinasi rabies dan kombinasi, serta pemasangan microchip agar Sahabat lebih terlindungi dan mudah teridentifikasi.',
                'includes' => "Vaksinasi sesuai jadwal\nPemasangan microchip\nSertifikat dan buku vaksinasi\nKonsultasi pencegahan",
                'featured' => true,
            ],
            [
                'name' => 'Spa & Perawatan Bulu',
                'icon' => 'spa',
                'price' => 95_000,
                'duration_minutes' => 75,
                'description' => 'Perawatan ekstra untuk bulu dan kulit: masker bulu, conditioning, dan shampoo anti kutu serta jamur.',
                'includes' => "Masker dan conditioning bulu\nPembersihan kuping dan cakar\nPangkas kuku\nPerawatan kutu dan jamur\nPewangi premium",
                'featured' => true,
            ],
            [
                'name' => 'Pet Taxi Antar-Jemput',
                'icon' => 'local_shipping',
                'price' => 45_000,
                'duration_minutes' => 60,
                'description' => 'Layanan antar-jemput Sahabat ke klinik, grooming, atau rumah dengan armada yang nyaman dan aman.',
                'includes' => "Antar-jemput dalam kota\nKandang portabel dan aman\nSopir terlatih\nPemantauan rute langsung",
                'featured' => false,
            ],
            [
                'name' => 'Home Service Perawatan',
                'icon' => 'home',
                'price' => 120_000,
                'duration_minutes' => 90,
                'description' => 'Perawatan datang ke rumah Anda: grooming ringan, perapihan kuku, dan pemeriksaan dasar untuk Sahabat yang lebih nyaman di rumah.',
                'includes' => "Kunjungan ke rumah\nMandi ringan dan pengeringan\nPotong kuku dan rawat cakar\nPemeriksaan kondisi tubuh\nLaporan hasil perawatan",
                'featured' => false,
            ],
        ];

        foreach ($services as $index => $data) {
            Service::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'includes' => $data['includes'],
                    'price' => $data['price'],
                    'duration_minutes' => $data['duration_minutes'],
                    'icon' => $data['icon'],
                    'status' => 'active',
                    'featured' => $data['featured'],
                    'sort_order' => $index,
                ],
            );
        }
    }
}
