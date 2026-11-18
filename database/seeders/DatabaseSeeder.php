<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Booking;
use App\Models\HouseType;
use App\Models\KprBank;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@perumahanku.id'],
            [
                'name' => 'Administrator Perumahanku',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Settings (dapat diedit di panel admin kapan saja)
        $settings = [
            ['key' => 'site_name', 'value' => 'Perumahanku', 'group' => 'general', 'label' => 'Nama Website / Brand'],
            ['key' => 'site_tagline', 'value' => 'Katalog Perumahan Baru & Impian Keluarga', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'company_name', 'value' => 'PT Perumahanku Graha Nusantara', 'group' => 'company', 'label' => 'Nama Perusahaan Pengembang'],
            ['key' => 'company_phone', 'value' => '021-88997766', 'group' => 'company', 'label' => 'Telepon Kantor'],
            ['key' => 'whatsapp_number', 'value' => '6281234567890', 'group' => 'company', 'label' => 'Nomor WhatsApp Hotline'],
            ['key' => 'company_email', 'value' => 'info@perumahanku.id', 'group' => 'company', 'label' => 'Email Resmi'],
            ['key' => 'company_address', 'value' => 'Jl. Boulevard Hijau No. 128, Sentral Bisnis, Jawa Barat', 'group' => 'company', 'label' => 'Alamat Kantor'],
            ['key' => 'hero_title', 'value' => 'Temukan Hunian Nyaman & Strategis untuk Masa Depan', 'group' => 'home', 'label' => 'Judul Hero Banner'],
            ['key' => 'hero_subtitle', 'value' => 'Pilihan perumahan baru terbaik dengan legalitas aman, cicilan KPR terjangkau, siteplan interaktif, dan survei lokasi langsung dari genggaman Anda.', 'group' => 'home', 'label' => 'Subjudul Hero Banner'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/perumahanku.official', 'group' => 'social', 'label' => 'Instagram URL'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/perumahanku.official', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'tiktok_url', 'value' => 'https://tiktok.com/@perumahanku.id', 'group' => 'social', 'label' => 'TikTok URL'],
            ['key' => 'office_hours', 'value' => 'Senin - Minggu: 08.30 - 17.30 WIB (Marketing Gallery Buka Setiap Hari)', 'group' => 'company', 'label' => 'Jam Operasional'],
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }

        // 3. KPR Banks
        $banks = [
            [
                'bank_name' => 'Bank BTN (Spesialis KPR)',
                'interest_rate' => 4.50,
                'max_tenor' => 30,
                'min_dp_percent' => 5.0,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'interest_rate' => 4.88,
                'max_tenor' => 25,
                'min_dp_percent' => 5.0,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'bank_name' => 'Bank BCA',
                'interest_rate' => 5.25,
                'max_tenor' => 20,
                'min_dp_percent' => 10.0,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'bank_name' => 'Bank BSI (Syariah Tanpa Riba)',
                'interest_rate' => 4.75,
                'max_tenor' => 25,
                'min_dp_percent' => 5.0,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'bank_name' => 'Bank BRI',
                'interest_rate' => 4.99,
                'max_tenor' => 25,
                'min_dp_percent' => 5.0,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($banks as $b) {
            KprBank::firstOrCreate(['bank_name' => $b['bank_name']], $b);
        }

        // 4. Projects (Contoh Proyek Perumahan Realistis)
        $p1 = Project::updateOrCreate(
            ['slug' => 'grand-harmony-residence'],
            [
                'name' => 'Grand Harmony Residence',
                'tagline' => 'Hunian Teduh Ramah Lingkungan dengan Konsep Smart Eco Living',
                'description' => 'Grand Harmony Residence merupakan kawasan perumahan modern eksklusif yang memadukan keasrian alam pegunungan dengan kemudahan akses perkotaan. Mengusung konsep Smart Eco Living dengan sistem pencahayaan dan sirkulasi alami, clubhouse keluarga, keamanan 24 jam one-gate system, serta dekat stasiun KRL dan pintu tol lingkar luar.',
                'address' => 'Jl. Raya Tajur Raya No. 45, Katulampa',
                'city' => 'Bogor',
                'district' => 'Bogor Timur',
                'province' => 'Jawa Barat',
                'latitude' => -6.62312,
                'longitude' => 106.82834,
                'facilities' => [
                    'Keamanan 24 Jam & One Gate Access Card',
                    'CCTV Terintegrasi di Seluruh Cluster',
                    'Clubhouse & Kolam Renang Anak',
                    'Masjid Jamie dalam Cluster',
                    'Taman Bermain Anak & Gazebo Santai',
                    'Jogging Track & Lapangan Olahraga 3-on-3',
                    'Jaringan Listrik & Fiber Optic Bawah Tanah',
                    'Row Jalan Lebar (8 - 10 Meter) Paving Block',
                ],
                'siteplan_image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80',
                'status' => 'active',
                'seo_title' => 'Grand Harmony Residence - Perumahan Modern Asri di Bogor Timur',
                'seo_description' => 'Cari perumahan baru di Bogor? Grand Harmony Residence menawarkan rumah subsidi & komersil cicilan mulai 2 jutaan/bulan dengan fasilitas clubhouse lengkap.',
                'is_featured' => true,
                'sort_order' => 1,
            ]
        );

        // Project 1 Images
        $p1Images = [
            ['path' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Fasad Modern Tropis Grand Harmony', 'is_primary' => true, 'category' => 'exterior'],
            ['path' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Suasana Gerbang Cluster & Lingkungan Hijau', 'is_primary' => false, 'category' => 'exterior'],
            ['path' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Ruang Tamu & Desain Open-Space Nyaman', 'is_primary' => false, 'category' => 'interior'],
            ['path' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Area Kamar Tidur Utama dengan Pencahayaan Alami', 'is_primary' => false, 'category' => 'interior'],
        ];
        foreach ($p1Images as $img) {
            $p1->images()->firstOrCreate(['path' => $img['path']], $img);
        }

        // Tipe Rumah P1
        $t1 = HouseType::updateOrCreate(
            ['project_id' => $p1->id, 'slug' => 'tipe-36-60-asoka'],
            [
                'name' => 'Tipe 36/60 (Asoka)',
                'building_area' => 36,
                'land_area' => 60,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floors' => 1,
                'price' => 385000000,
                'description' => 'Pilihan ideal bagi pasangan muda atau keluarga baru. Memiliki 2 kamar tidur fungsional, ruang keluarga lega terhubung ke taman belakang, serta carport luas untuk 1 mobil dan 2 motor.',
                'specifications' => [
                    'Pondasi' => 'Batu Kali & Beton Bertulang',
                    'Dinding' => 'Bata Ringan (Hebel) diplester & diaci, cat Weathercoat',
                    'Lantai' => 'Granit Homogeneous Tile 60x60',
                    'Atap' => 'Rangka Baja Ringan + Genteng Beton Flat',
                    'Kusen & Pintu' => 'Alumunium Powder Coating & Solid Wood Panel',
                    'Sanitair' => 'Kloset Duduk Toto / setara + Shower',
                    'Listrik' => 'PLN 1.300 Watt Prabayar (Token)',
                    'Air' => 'Sumur Bor + Pompa Otomatis & Toren Air 500L',
                ],
                'floor_plan_image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        $t2 = HouseType::updateOrCreate(
            ['project_id' => $p1->id, 'slug' => 'tipe-45-72-bougenville'],
            [
                'name' => 'Tipe 45/72 (Bougenville)',
                'building_area' => 45,
                'land_area' => 72,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floors' => 1,
                'price' => 520000000,
                'description' => 'Desain kompak dengan langit-langit tinggi (high ceiling 4.2m) yang menghadirkan suasana ruangan sejuk dan mewah. Dilengkapi dapur bersih modern dan area servis belakang.',
                'specifications' => [
                    'Pondasi' => 'Batu Kali & Pondasi Cakar Ayam (Siap 2 Lantai)',
                    'Dinding' => 'Bata Merah Double Dinding',
                    'Lantai' => 'Granit Tile 60x60 Polished',
                    'Kusen & Pintu' => 'Aluminium Hitam Anodized + Smart Door Lock',
                    'Sanitair' => 'American Standard Toto Sanitary',
                    'Listrik' => 'PLN 2.200 Watt',
                    'Air' => 'PDAM Mandiri + Filter Air Baku',
                ],
                'floor_plan_image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
                'sort_order' => 2,
            ]
        );

        $t3 = HouseType::updateOrCreate(
            ['project_id' => $p1->id, 'slug' => 'tipe-68-90-cempaka-2-lantai'],
            [
                'name' => 'Tipe 68/90 (Cempaka 2 Lantai)',
                'building_area' => 68,
                'land_area' => 90,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'floors' => 2,
                'price' => 795000000,
                'description' => 'Rumah 2 lantai premium dengan 3 kamar tidur lega, balkon pribadi lantai atas menghadap pemandangan hijau cluster, serta carport 2 mobil paralel.',
                'specifications' => [
                    'Struktur' => 'Beton Bertulang K-250 Standar SNI',
                    'Lantai' => 'Granite Tile 60x60 + Parquet Wood di Kamar Utama',
                    'Tangga' => 'Railing Kaca Tempered + Kayu Merbau',
                    'Pintu' => 'Solid Engineering Wood + Smart Digital Lockset (PIN/Fingerprint)',
                    'Listrik' => 'PLN 3.500 Watt Underground Cable',
                    'Smart Home' => 'Smart Switch Lampu & CCTV Outdoor Terkoneksi Smartphone',
                ],
                'floor_plan_image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',
                'is_available' => true,
                'sort_order' => 3,
            ]
        );

        // Kavling / Units untuk P1 dengan KOORDINAT SITEPLAN & POV (Point of View)
        $unitsDataP1 = [
            [
                'project_id' => $p1->id,
                'house_type_id' => $t1->id,
                'block' => 'A',
                'number' => '01',
                'land_area' => 60,
                'price' => 385000000,
                'facing' => 'Timur (Matahari Pagi)',
                'status' => 'available',
                'pov_description' => 'POV dari Teras Kavling A-01: Langsung menghadap matahari terbit dengan latar bukit hijau dan area taman entrance. Bebas polusi dan posisi paling dekat dengan pos security utama.',
                'position_x' => 18.5,
                'position_y' => 32.0,
                'pov_photo' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'project_id' => $p1->id,
                'house_type_id' => $t1->id,
                'block' => 'A',
                'number' => '02',
                'land_area' => 60,
                'price' => 385000000,
                'facing' => 'Timur',
                'status' => 'available',
                'pov_description' => 'POV dari Teras Kavling A-02: View jalan cluster row 8 meter yang rapi dipadu pemandangan pepohonan rindang. Suasana tenang dan ramah anak.',
                'position_x' => 24.5,
                'position_y' => 32.0,
                'pov_photo' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'project_id' => $p1->id,
                'house_type_id' => $t1->id,
                'block' => 'A',
                'number' => '03',
                'land_area' => 60,
                'price' => 385000000,
                'facing' => 'Timur',
                'status' => 'booked',
                'pov_description' => 'POV Kavling A-03: View sudut pandang terbuka ke arah taman bundaran cluster.',
                'position_x' => 30.5,
                'position_y' => 32.0,
                'pov_photo' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'project_id' => $p1->id,
                'house_type_id' => $t2->id,
                'block' => 'B',
                'number' => '05',
                'land_area' => 72,
                'price' => 520000000,
                'facing' => 'Utara',
                'status' => 'available',
                'pov_description' => 'POV Spesial Kavling B-05: Posisi tepat berhadapan dengan Fasilitas Taman Tematik & Gazebo Keluarga! Suasana sangat sejuk dan anak-anak bisa langsung bermain di depan rumah.',
                'position_x' => 45.0,
                'position_y' => 48.0,
                'pov_photo' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'project_id' => $p1->id,
                'house_type_id' => $t2->id,
                'block' => 'B',
                'number' => '06',
                'land_area' => 72,
                'price' => 520000000,
                'facing' => 'Utara',
                'status' => 'sold',
                'pov_description' => 'POV Kavling B-06 (Terjual): View area taman komunal.',
                'position_x' => 51.5,
                'position_y' => 48.0,
                'pov_photo' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'project_id' => $p1->id,
                'house_type_id' => $t3->id,
                'block' => 'C',
                'number' => '01 (Hook)',
                'land_area' => 110,
                'price' => 895000000,
                'facing' => 'Selatan & Barat (Dua Muka)',
                'status' => 'available',
                'pov_description' => 'POV Premium Kavling Hook C-01: Posisi hook premium dengan kelebihan tanah samping 20m², balkon lantai 2 memiliki sudut pandang luas 180° menghadap pemandangan clubhouse dan perbukitan Selatan.',
                'position_x' => 72.0,
                'position_y' => 64.0,
                'pov_photo' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'project_id' => $p1->id,
                'house_type_id' => $t3->id,
                'block' => 'C',
                'number' => '02',
                'land_area' => 90,
                'price' => 795000000,
                'facing' => 'Selatan',
                'status' => 'available',
                'pov_description' => 'POV Kavling C-02: View langsung ke arah area clubhouse & kolam renang cluster. Akses jalan paling lengang di ujung jalan buntu (cul-de-sac) yang aman untuk anak-anak.',
                'position_x' => 78.0,
                'position_y' => 64.0,
                'pov_photo' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1000&q=80',
            ],
        ];

        foreach ($unitsDataP1 as $ud) {
            $povPhoto = $ud['pov_photo'];
            unset($ud['pov_photo']);

            $u = Unit::updateOrCreate(
                [
                    'project_id' => $ud['project_id'],
                    'block' => $ud['block'],
                    'number' => $ud['number'],
                ],
                $ud
            );

            // Simpan foto POV ke tabel images polymorphic
            $u->images()->firstOrCreate(
                ['path' => $povPhoto],
                [
                    'caption' => 'Sudut Pandang (POV) Nyata dari Kavling '.$u->block.'-'.$u->number,
                    'is_primary' => true,
                    'category' => 'pov',
                    'sort_order' => 1,
                ]
            );
        }

        // Project 2: Bukit Serpong Asri
        $p2 = Project::updateOrCreate(
            ['slug' => 'bukit-serpong-asri'],
            [
                'name' => 'Bukit Serpong Asri',
                'tagline' => 'Hunian Eksklusif 15 Menit ke Stasiun Rawabuntu & Tol BSD',
                'description' => 'Bukit Serpong Asri berlokasi strategis di kawasan penyangga BSD Serpong. Menawarkan kemudahan mobilitas kerja ke Jakarta melalui KRL dan akses tol langsung. Dilengkapi fasilitas modern komersil, underground utility, jogging track tepi danau buatan, dan keamanan 24 jam berbasis kartu RFID.',
                'address' => 'Jl. Pahlawan Serpong No. 89, Ciater',
                'city' => 'Tangerang Selatan',
                'district' => 'Serpong',
                'province' => 'Banten',
                'latitude' => -6.31298,
                'longitude' => 106.68921,
                'facilities' => [
                    'Akses Tol Serpong-Balaraja & Tol JORR 2',
                    '12 Menit ke Stasiun KRL Rawa Buntu',
                    'Underground Cable Drainage Bebas Banjir',
                    'Access Card One Gate & CCTV 24 Jam',
                    'Danau Komunal & Tepi Jogging Track',
                    'Commercial Area & Minimarket 24 Jam',
                ],
                'siteplan_image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80',
                'status' => 'active',
                'seo_title' => 'Bukit Serpong Asri - Rumah Mewah 2 Lantai Dekat Stasiun BSD',
                'seo_description' => 'Perumahan baru Bukit Serpong Asri Tangerang Selatan. Akses super strategis tol & KRL, harga perdana promo bebas biaya KPR & BPHTB.',
                'is_featured' => true,
                'sort_order' => 2,
            ]
        );

        $p2Images = [
            ['path' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Fasad Elegan Bukit Serpong Asri', 'is_primary' => true, 'category' => 'exterior'],
            ['path' => 'https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Interior Living Room Scandinavian', 'is_primary' => false, 'category' => 'interior'],
        ];
        foreach ($p2Images as $img) {
            $p2->images()->firstOrCreate(['path' => $img['path']], $img);
        }

        $t21 = HouseType::updateOrCreate(
            ['project_id' => $p2->id, 'slug' => 'tipe-54-84-emerald'],
            [
                'name' => 'Tipe 54/84 (Emerald)',
                'building_area' => 54,
                'land_area' => 84,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'floors' => 1,
                'price' => 745000000,
                'description' => 'Hunian tropis minimalis modern dengan layout lapang, 3 kamar tidur, dan taman belakang yang cocok untuk santai sore.',
                'specifications' => [
                    'Pondasi' => 'Pancang Mini Pile + Sloof Beton Bertulang',
                    'Dinding' => 'Bata Merah Finishing Cat Dulux Weathershield',
                    'Kusen' => 'Alumunium YKK Standar Jepang',
                    'Sanitair' => 'Grohe & Toto Flush System',
                ],
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        $t22 = HouseType::updateOrCreate(
            ['project_id' => $p2->id, 'slug' => 'tipe-88-105-sapphire-2-lantai'],
            [
                'name' => 'Tipe 88/105 (Sapphire 2 Lantai)',
                'building_area' => 88,
                'land_area' => 105,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'floors' => 2,
                'price' => 1180000000,
                'description' => 'Hunian prestisius 2 lantai bagi keluarga mapan, dilengkapi master bedroom dengan ensuite bathroom dan walk-in wardrobe.',
                'specifications' => [
                    'Struktur' => 'Beton Bertulang K-300 Siap Gempa',
                    'Smart Home' => 'Door Lock, IP Camera, Sensor Gerak, Smart Lighting',
                    'Listrik' => 'PLN 4.400 Watt',
                ],
                'is_available' => true,
                'sort_order' => 2,
            ]
        );

        // Project 3: Griya Permata Cikarang
        $p3 = Project::updateOrCreate(
            ['slug' => 'griya-permata-cikarang'],
            [
                'name' => 'Griya Permata Cikarang',
                'tagline' => 'Rumah Subsidi & Komersil Terjangkau Siap Huni Bebas Banjir',
                'description' => 'Griya Permata Cikarang hadir menjawab kebutuhan para pekerja kawasan industri dan keluarga muda yang mendambakan rumah sendiri dengan angsuran seringan bayar kost. Lokasi dekat kawasan GIIC, akses tol Cikarang Barat, dan sentra perbelanjaan.',
                'address' => 'Jl. Sukatani Raya KM 12',
                'city' => 'Bekasi',
                'district' => 'Cikarang Utara',
                'province' => 'Jawa Barat',
                'latitude' => -6.24151,
                'longitude' => 107.15284,
                'facilities' => [
                    'Dekat Kawasan Industri EJIP, GIIC, MM2100',
                    'Angsuran Flat KPR Subsidi mulai 1 Jutaan/bulan',
                    'Jalan Lingkungan Cor Beton Tebal',
                    'Bebas Banjir dan Dataran Tinggi',
                    'Dilalui Angkutan Umum 24 Jam',
                ],
                'siteplan_image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80',
                'status' => 'active',
                'seo_title' => 'Griya Permata Cikarang - Perumahan Subsidi Murah Cicilan 1 Juta',
                'seo_description' => 'Mau beli rumah murah di Cikarang Bekasi? Griya Permata menawarkan perumahan subsidi bunga flat 5% sampai lunas, DP 0% langsung proses KPR.',
                'is_featured' => true,
                'sort_order' => 3,
            ]
        );

        $p3Images = [
            ['path' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80', 'caption' => 'Fasad Griya Permata Cikarang', 'is_primary' => true, 'category' => 'exterior'],
        ];
        foreach ($p3Images as $img) {
            $p3->images()->firstOrCreate(['path' => $img['path']], $img);
        }

        $t31 = HouseType::updateOrCreate(
            ['project_id' => $p3->id, 'slug' => 'tipe-30-60-subsidi-flpp'],
            [
                'name' => 'Tipe 30/60 (Subsidi FLPP)',
                'building_area' => 30,
                'land_area' => 60,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floors' => 1,
                'price' => 185000000,
                'description' => 'Rumah subsidi pemerintah program FLPP dengan suku bunga tetap 5% hingga 20 tahun. Cicilan mulai dari 1 jutaan per bulan.',
                'specifications' => [
                    'Pondasi' => 'Batu Kali',
                    'Dinding' => 'Batako Press Plester & Cat',
                    'Rangka Atap' => 'Baja Ringan + Genteng Metal',
                    'Listrik' => 'PLN 900 / 1.300 Watt',
                    'Air' => 'Sumur Pantek Bersih',
                ],
                'is_available' => true,
                'sort_order' => 1,
            ]
        );

        // 5. Dummy Bookings Masuk
        $sampleBookings = [
            [
                'project_id' => $p1->id,
                'house_type_id' => $t2->id,
                'name' => 'Budi Santoso',
                'phone' => '081298765432',
                'email' => 'budisantoso88@gmail.com',
                'preferred_date' => now()->addDays(2),
                'preferred_time' => '10:00 WIB (Pagi)',
                'message' => 'Halo min, saya tertarik survei Tipe 45 di Grand Harmony Residence, apakah bisa sekalian konsultasi simulasi KPR Bank BTN?',
                'status' => 'confirmed',
                'admin_notes' => 'Sudah dikonfirmasi via WhatsApp. Janji temu dengan Sales Mas Rian di Marketing Gallery.',
            ],
            [
                'project_id' => $p1->id,
                'house_type_id' => $t3->id,
                'name' => 'Citra Anggraini',
                'phone' => '085712348899',
                'email' => 'citra.anggraini@corporate.co.id',
                'preferred_date' => now()->addDays(4),
                'preferred_time' => '14:00 WIB (Siang)',
                'message' => 'Mau lihat unit Hook C-01 yang 2 lantai beserta POV pemandangannya.',
                'status' => 'pending',
                'admin_notes' => null,
            ],
            [
                'project_id' => $p2->id,
                'house_type_id' => $t21->id,
                'name' => 'Dedy Pratama',
                'phone' => '081390901212',
                'email' => 'dedy.pratama@yahoo.com',
                'preferred_date' => now()->subDay(),
                'preferred_time' => '11:00 WIB',
                'message' => 'Survei lokasi Bukit Serpong Asri.',
                'status' => 'done',
                'admin_notes' => 'Survei selesai, calon buyer sedang siapkan berkas slip gaji untuk BI Checking KPR BCA.',
            ],
        ];

        foreach ($sampleBookings as $b) {
            Booking::create($b);
        }

        // 6. Blog Posts SEO
        $posts = [
            [
                'title' => '5 Tips Jitu Memilih Rumah Pertama Bagi Pasangan Muda di 2026',
                'slug' => '5-tips-jitu-memilih-rumah-pertama-pasangan-muda',
                'category' => 'Panduan Beli Rumah',
                'content' => '<p>Membeli rumah pertama merupakan salah satu keputusan finansial terbesar dalam hidup sebuah keluarga muda. Seringkali, godaan diskon atau desain cantik membuat calon pembeli lupa mempertimbangkan faktor jangka panjang seperti legalitas, biaya perawatan, dan kemudahan akses harian.</p><h3>1. Cek Kredibilitas Pengembang dan Sertifikat</h3><p>Pastikan sertifikat lahan sudah split per kavling (SHM / HGB murni) dan IMB/PBG sudah terbit. Jangan tergiur harga di bawah pasar jika legalitas masih mengambang.</p><h3>2. Hitung Kemampuan Cicilan Maksimal 30% dari Penghasilan</h3><p>Rasio aman angsuran KPR bulanan adalah sepertiga dari total take-home pay gabungan suami dan istri agar kebutuhan masa depan tetap terlindungi.</p><h3>3. Perhatikan Fasilitas dan Akses Transportasi</h3><p>Jarak bukan sekadar kilometer, tapi waktu tempuh. Rumah dekat stasiun KRL atau gerbang tol lingkar luar seringkali lebih menghemat energi dan biaya perjalanan dibanding yang terisolasi.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80',
                'seo_title' => '5 Tips Jitu Memilih Rumah Pertama Bagi Pasangan Muda - Perumahanku',
                'seo_description' => 'Simak 5 tips penting membeli rumah pertama bagi keluarga muda: cara hitung KPR, cek legalitas developer, hingga memilih lokasi strategis.',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Panduan Lengkap Syarat dan Cara Lolos Pengajuan KPR Bank',
                'slug' => 'panduan-lengkap-syarat-dan-cara-lolos-kpr-bank',
                'category' => 'Tips KPR',
                'content' => '<p>Pengajuan Kredit Pemilikan Rumah (KPR) seringkali ditolak hanya karena masalah sepele dalam riwayat perbankan. Ketahui syarat administrasi dan tips agar skor SLIK OJK (BI Checking) Anda berstatus Kol 1 (lancar).</p><h3>Syarat Berkas Wajib:</h3><ul><li>Fotokopi KTP Pemohon & Pasangan</li><li>Kartu Keluarga & Surat Nikah</li><li>NPWP Pribadi</li><li>Slip Gaji 3 Bulan Terakhir & Surat Keterangan Kerja Asli</li><li>Rekening Koran 3-6 Bulan Terakhir</li></ul><p>Pastikan Anda tidak memiliki tunggakan pinjaman online (pinjol) atau kartu kredit sebelum mengajukan KPR ke bank rekanan.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
                'seo_title' => 'Panduan Lengkap Syarat & Cara Lolos Pengajuan KPR 2026 - Perumahanku',
                'seo_description' => 'Ketahui syarat dokumen KPR, tips lolos BI checking OJK, dan simulasi cicilan rumah paling akurat di sini.',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Mengapa Kawasan Penyangga Seperti Bogor & Bekasi Jadi Incaran Milenial?',
                'slug' => 'alasan-bogor-bekasi-jadi-incaran-rumah-milenial',
                'category' => 'Analisis Properti',
                'content' => '<p>Tingginya harga properti di pusat kota Jakarta membuat daerah penyangga seperti Bogor, Depok, Tangerang, dan Bekasi (Bodetabek) mengalami lonjakan peminat. Dengan pembangunan LRT Jabodebek dan jalan tol baru, mobilitas kini semakin singkat dan praktis.</p><p>Selain harga yang jauh lebih ramah di kantong, kawasan ini menawarkan lingkungan udara yang lebih segar dan ruang terbuka hijau yang ramah bagi tumbuh kembang anak.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                'seo_title' => 'Mengapa Bogor dan Bekasi Jadi Incaran Rumah Milenial - Perumahanku',
                'seo_description' => 'Tren kepemilikan rumah di kawasan penyangga Jabodetabek dengan akses transportasi modern.',
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
