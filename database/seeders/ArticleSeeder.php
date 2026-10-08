<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Nutrisi', 'description' => 'Panduan pemberian makan dan memilih makanan hewan.'],
            ['name' => 'Perawatan', 'description' => 'Tips grooming dan perawatan harian Sahabat.'],
            ['name' => 'Kesehatan', 'description' => 'Informasi kesehatan dan pencegahan penyakit.'],
        ])->mapWithKeys(function ($data) {
            $category = ArticleCategory::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                $data,
            );

            return [$data['name'] => $category];
        });

        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $articles = [
            [
                'title' => 'Panduan Memilih Makanan Kucing yang Tepat',
                'category' => 'Nutrisi',
                'excerpt' => 'Kenali kebutuhan nutrisi kucing sesuai tahap hidupnya agar bulu tetap sehat dan berat badan tetap ideal.',
                'thumbnail' => 'artikel-1',
                'content' => '<p>Memilih makanan kucing yang tepat dimulai dari membaca label kandungan gizi. Perhatikan kadar protein, lemak, serat, dan kelembapan yang tercantum pada kemasan.</p>'
                    .'<h3>Sesuaikan dengan Tahap Hidup</h3>'
                    .'<p>Anak kucing memerlukan protein tinggi dan tambahan kalori karena masih tumbuh. Pilih makanan khusus kitten sampai usia 12 bulan. Kucing dewasa umumnya membutuhkan makanan dengan kadar lemak lebih rendah untuk menjaga berat badan ideal, terutama bila ia lebih banyak berada di dalam ruangan.</p>'
                    .'<h3>Ciri Produk Berkualitas</h3>'
                    .'<ul>'
                    .'<li>Mencantumkan sumber protein utama pada daftar bahan pertama</li>'
                    .'<li>Mengandung taurin untuk kesehatan jantung dan retina</li>'
                    .'<li>Tidak mengandung pewarna buatan berlebihan</li>'
                    .'</ul>'
                    .'<p>Berikan porsi sesuai anjuran pada kemasan dan sesuaikan dengan tingkat aktivitas kucing Anda.</p>',
            ],
            [
                'title' => 'Tips Grooming Anjing di Rumah',
                'category' => 'Perawatan',
                'excerpt' => 'Langkah praktis memandukan, memotong kuku, dan menyisir bulu agar anjing tetap sehat dan nyaman.',
                'thumbnail' => 'artikel-2',
                'content' => '<p>Grooming rutin menjaga kulit dan bulu anjing tetap sehat. Lakukan minimal sebulan sekali untuk anjing berbulu pendek dan dua kali sebulan untuk anjing berbulu panjang.</p>'
                    .'<h3>Mandi dengan Shampoo yang Tepat</h3>'
                    .'<p>Gunakan shampoo khusus hewan. Hindari shampoo manusia karena tingkat pH-nya berbeda dan dapat mengiritasi kulit anjing. Basahi bulu secara merata, usap lembut, lalu bilas hingga tidak ada sisa shampoo.</p>'
                    .'<h3>Memotong Kuku</h3>'
                    .'<p>Potong kuku secara berkala agar anjing tidak kesulitan berjalan dan tidak melukai Anda. Potong sedikit demi sedikit pada bagian bening, dan hentikan bila melihat bagian gelap di tengah kuku.</p>'
                    .'<h3>Menyisir Bulu</h3>'
                    .'<p>Sisir mulai dari ujung bulu menuju akar untuk menghindari simpul. Periksa telinga dan lipatan kulit setelah mandi.</p>',
            ],
            [
                'title' => 'Kapan Sahabat Anda Perlu Dokter Hewan',
                'category' => 'Kesehatan',
                'excerpt' => 'Kenali tanda-tanda dini masalah kesehatan hewan peliharaan agar penanganannya tidak terlambat.',
                'thumbnail' => 'artikel-3',
                'content' => '<p>Kunjungan rutin ke dokter hewan sangat penting, terutama untuk vaksinasi dan pemeriksaan kesehatan tahunan.</p>'
                    .'<h3>Tanda yang Perlu Segera Diperiksa</h3>'
                    .'<ul>'
                    .'<li>Nafsu makan menurun drastis selama lebih dari satu hari</li>'
                    .'<li>Muntah atau diare berulang</li>'
                    .'<li>Lendir berlebihan di area mata dan hidung</li>'
                    .'<li>Kesulitan berjalan atau melompat</li>'
                    .'</ul>'
                    .'<h3>Jadwal Pemeriksaan Preventif</h3>'
                    .'<p>Pastikan vaksinasi rabies dan kombinasi diperbarui setiap tahun sesuai jadwal yang diberikan dokter hewan Anda. Hewan yang lebih tua sebaiknya diperiksa setiap enam bulan.</p>',
            ],
            [
                'title' => 'Membedah Makanan Basah vs Makanan Kering',
                'category' => 'Nutrisi',
                'excerpt' => 'Kapan sebaiknya memilih makanan basah dan kapan makanan kering lebih baik untuk kesehatan hewan.',
                'thumbnail' => null,
                'content' => '<p>Makanan basah dan kering sama-sama memiliki kelebihan. Keduanya boleh diberikan asalkan komposisinya sesuai dengan kebutuhan hewan Anda.</p>'
                    .'<h3>Keunggulan Makanan Basah</h3>'
                    .'<p>Kandungan airnya lebih tinggi sehingga membantu menjaga hidrasi, terutama untuk kucing yang rentan masalah saluran kemih. Teksturnya juga lebih mudah dikunyah oleh hewan senior.</p>'
                    .'<h3>Keunggulan Makanan Kering</h3>'
                    .'<p>Lebih praktis disimpan, tidak mudah basi, dan membantu menjaga kesehatan gigi karena teksturnya yang lebih keras.</p>'
                    .'<h3>Kombinasi yang Disarankan</h3>'
                    .'<p>Banyak pemilik memberi makanan kering sebagai menu utama dan makanan basah sebagai pelengkap. Selalu sediakan air minum bersih di dekat area pemberian makan.</p>',
            ],
            [
                'title' => 'Cara Memperkenalkan Makanan Baru pada Hewan',
                'category' => 'Nutrisi',
                'excerpt' => 'Mengganti makanan secara bertahap agar sistem pencernaan hewan peliharaan tidak terganggu.',
                'thumbnail' => null,
                'content' => '<p>Perubahan makanan yang terlalu cepat dapat menyebabkan diare dan gangguan pencernaan. Lakukan transisi bertahap selama sekitar sembilan hari.</p>'
                    .'<h3>Contoh Tahapan Transisi</h3>'
                    .'<ul>'
                    .'<li>25 persen makanan baru selama 3 hari</li>'
                    .'<li>50 persen makanan baru selama 3 hari</li>'
                    .'<li>75 persen makanan baru selama 3 hari</li>'
                    .'<li>100 persen makanan baru setelah hari kesembilan</li>'
                    .'</ul>'
                    .'<p>Pantau kondisi saluran pencernaan selama proses berlangsung. Bila muncul muntah atau diare yang berkepanjangan, kembalikan porsi ke proporsi sebelumnya dan konsultasikan ke dokter hewan.</p>',
            ],
            [
                'title' => 'Menjaga Kebersihan Kandang dan Sangkar',
                'category' => 'Perawatan',
                'excerpt' => 'Kebersihan kandang dan sangkar menentukan kesehatan hewan peliharaan Anda setiap hari.',
                'thumbnail' => null,
                'content' => '<p>Kandang dan sangkar perlu dibersihkan secara rutin untuk mencegah bau dan pertumbuhan bakteri.</p>'
                    .'<h3>Frekuensi Pembersihan</h3>'
                    .'<p>Bersihkan bagian dasar sangkar setiap hari dan cuci seluruh kandang minimal seminggu sekali. Ganti alas kandang setiap dua minggu atau lebih cepat bila sudah lembap.</p>'
                    .'<h3>Menjaga Sirkulasi Udara</h3>'
                    .'<p>Pastikan udara mengalir dengan baik dan hindari paparan sinar matahari langsung yang berlebihan. Letakkan kandang di tempat teduh dengan suhu ruangan yang stabil.</p>'
                    .'<h3>Alat Pembersih yang Aman</h3>'
                    .'<p>Gunakan pembersih berbahan lembut dan bilas hingga bersih sebelum kandang dipakai kembali. Hindari pemakaian pemutih yang menyengat karena aromanya dapat mengganggu pernapasan hewan.</p>',
            ],
            [
                'title' => 'Mengenali Tanda Hewan Membutuhkan Perhatian Ekstra',
                'category' => 'Kesehatan',
                'excerpt' => 'Bahasa tubuh hewan membantu Anda mengenali saat Sahabat membutuhkan perhatian lebih.',
                'thumbnail' => null,
                'content' => '<p>Hewan berkomunikasi melalui perilaku dan bahasa tubuh. Memahami tanda-tanda ini membantu Anda bertindak lebih cepat.</p>'
                    .'<h3>Tanda Umum yang Perlu Diwaspadai</h3>'
                    .'<ul>'
                    .'<li>Menyembunyikan diri dan mengurangi interaksi sosial</li>'
                    .'<li>Ekor atau telinga turun tanpa alasan yang jelas</li>'
                    .'<li>Lemas, tidak bermain, dan banyak tidur</li>'
                    .'</ul>'
                    .'<p>Jika tanda-tanda tersebut berlanjut lebih dari dua hari, segera konsultasikan ke dokter hewan. Catat kapan gejala mulai muncul untuk membantu proses diagnosa.</p>',
            ],
            [
                'title' => 'Memberi Nutrisi pada Anak Kucing dan Anjing',
                'category' => 'Nutrisi',
                'excerpt' => 'Kebutuhan nutrisi pada tahap awal kehidupan hewan sangat spesifik dan berbeda dari hewan dewasa.',
                'thumbnail' => null,
                'content' => '<p>Anak hewan yang baru lahir memerlukan ASI indukan atau susu pengganti khusus sampai berusia sekitar enam minggu.</p>'
                    .'<h3>Transisi ke Makanan Padat</h3>'
                    .'<p>Mulai perkenalkan makanan padat secara bertahap pada usia empat hingga enam minggu agar peralihannya lancar. Gunakan makanan basah khusus kitten yang dilembutkan dengan sedikit air hangat.</p>'
                    .'<h3>Porsi dan Frekuensi</h3>'
                    .'<ul>'
                    .'<li>Usia 4-8 minggu: 4-6 kali sehari dalam porsi kecil</li>'
                    .'<li>Usia 2-6 bulan: 3 kali sehari</li>'
                    .'<li>Usia 6 bulan ke atas: 2 kali sehari</li>'
                    .'</ul>'
                    .'<p>Pantau berat badan setiap minggu. Kenaikan berat yang terlalu lambat perlu dikonsultasikan ke dokter hewan.</p>',
            ],
            [
                'title' => 'Pencegahan Parasit: Kutu, Cacing, dan Jamur',
                'category' => 'Kesehatan',
                'excerpt' => 'Pengendalian parasit yang rutin menghemat biaya perawatan di kemudian hari.',
                'thumbnail' => null,
                'content' => '<p>Parasit eksternal maupun internal dapat mengganggu kesehatan hewan dan bahkan menular ke manusia.</p>'
                    .'<h3>Program Pencegahan</h3>'
                    .'<p>Gunakan obat kutu dan penghilang cacing secara rutin sesuai jadwal yang diberikan dokter hewan. Pilih produk sesuai berat badan dan usia hewan agar aman digunakan.</p>'
                    .'<h3>Kebersihan Lingkungan</h3>'
                    .'<p>Rajin mencuci alas tidur dan menyapu area yang sering ditempati hewan untuk mengurangi risiko kambuhnya infestasi.</p>'
                    .'<h3>Tanda Infeksi</h3>'
                    .'<ul>'
                    .'<li>Gatal berlebihan dan bulu rontok</li>'
                    .'<li>Perut buncit pada hewan yang kurus</li>'
                    .'<li>Ada bagian yang terlihat di sekitar anus</li>'
                    .'</ul>',
            ],
            [
                'title' => 'Membiasakan Hewan dengan Perawatan Rutin',
                'category' => 'Perawatan',
                'excerpt' => 'Latihan bertahap membuat pengalaman grooming menjadi hal yang menyenangkan bagi hewan Anda.',
                'thumbnail' => null,
                'content' => '<p>Tidak semua hewan langsung menyukai proses grooming. Dengan latihan bertahap, Sahabat Anda dapat terbiasa dan lebih tenang.</p>'
                    .'<h3>Mulai dari Sentuhan Ringan</h3>'
                    .'<p>Ajarkan hewan menyentuh bagian tubuh tertentu secara singkat dan berulang, lalu berikan hadiah berupa camilan atau pujian sebagai bentuk positif.</p>'
                    .'<h3>Konsisten dan Tenang</h3>'
                    .'<p>Hindari terburu-buru. Kesabaran adalah kunci agar hewan tidak trauma terhadap proses perawatan.</p>'
                    .'<h3>Gunakan Jadwal Tetap</h3>'
                    .'<p>Lakukan perawatan pada jam yang sama setiap kali. Hewan cenderung lebih tenang ketika tahu apa yang akan terjadi berikutnya.</p>',
            ],
        ];

        foreach ($articles as $data) {
            $thumbnailPath = null;

            if ($data['thumbnail']) {
                $source = public_path("images/{$data['thumbnail']}.jpg");
                $relative = "articles/{$data['thumbnail']}.jpg";

                if (is_file($source)) {
                    Storage::disk('public')->put($relative, file_get_contents($source));
                    $thumbnailPath = $relative;
                }
            }

            Article::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'article_category_id' => $categories[$data['category']]?->id,
                    'author_id' => $admin?->id,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'thumbnail' => $thumbnailPath,
                    'status' => Article::STATUS_PUBLISHED,
                    'published_at' => now()->subDays(random_int(1, 90)),
                    'views' => random_int(50, 3000),
                ],
            );
        }
    }
}
