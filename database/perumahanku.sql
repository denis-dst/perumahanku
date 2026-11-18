/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 8.0.30 : Database - perumahanku
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `blog_posts`;

CREATE TABLE `blog_posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` text COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `blog_posts` */

insert  into `blog_posts`(`id`,`title`,`slug`,`content`,`thumbnail`,`category`,`seo_title`,`seo_description`,`is_published`,`published_at`,`created_at`,`updated_at`) values 
(1,'5 Tips Jitu Memilih Rumah Pertama Bagi Pasangan Muda di 2026','5-tips-jitu-memilih-rumah-pertama-pasangan-muda','<p>Membeli rumah pertama merupakan salah satu keputusan finansial terbesar dalam hidup sebuah keluarga muda. Seringkali, godaan diskon atau desain cantik membuat calon pembeli lupa mempertimbangkan faktor jangka panjang seperti legalitas, biaya perawatan, dan kemudahan akses harian.</p><h3>1. Cek Kredibilitas Pengembang dan Sertifikat</h3><p>Pastikan sertifikat lahan sudah split per kavling (SHM / HGB murni) dan IMB/PBG sudah terbit. Jangan tergiur harga di bawah pasar jika legalitas masih mengambang.</p><h3>2. Hitung Kemampuan Cicilan Maksimal 30% dari Penghasilan</h3><p>Rasio aman angsuran KPR bulanan adalah sepertiga dari total take-home pay gabungan suami dan istri agar kebutuhan masa depan tetap terlindungi.</p><h3>3. Perhatikan Fasilitas dan Akses Transportasi</h3><p>Jarak bukan sekadar kilometer, tapi waktu tempuh. Rumah dekat stasiun KRL atau gerbang tol lingkar luar seringkali lebih menghemat energi dan biaya perjalanan dibanding yang terisolasi.</p>','https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80','Panduan Beli Rumah','5 Tips Jitu Memilih Rumah Pertama Bagi Pasangan Muda - Perumahanku','Simak 5 tips penting membeli rumah pertama bagi keluarga muda: cara hitung KPR, cek legalitas developer, hingga memilih lokasi strategis.',1,'2026-11-13 12:58:41','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(2,'Panduan Lengkap Syarat dan Cara Lolos Pengajuan KPR Bank','panduan-lengkap-syarat-dan-cara-lolos-kpr-bank','<p>Pengajuan Kredit Pemilikan Rumah (KPR) seringkali ditolak hanya karena masalah sepele dalam riwayat perbankan. Ketahui syarat administrasi dan tips agar skor SLIK OJK (BI Checking) Anda berstatus Kol 1 (lancar).</p><h3>Syarat Berkas Wajib:</h3><ul><li>Fotokopi KTP Pemohon & Pasangan</li><li>Kartu Keluarga & Surat Nikah</li><li>NPWP Pribadi</li><li>Slip Gaji 3 Bulan Terakhir & Surat Keterangan Kerja Asli</li><li>Rekening Koran 3-6 Bulan Terakhir</li></ul><p>Pastikan Anda tidak memiliki tunggakan pinjaman online (pinjol) atau kartu kredit sebelum mengajukan KPR ke bank rekanan.</p>','https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80','Tips KPR','Panduan Lengkap Syarat & Cara Lolos Pengajuan KPR 2026 - Perumahanku','Ketahui syarat dokumen KPR, tips lolos BI checking OJK, dan simulasi cicilan rumah paling akurat di sini.',1,'2026-11-09 12:58:41','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(3,'Mengapa Kawasan Penyangga Seperti Bogor & Bekasi Jadi Incaran Milenial?','alasan-bogor-bekasi-jadi-incaran-rumah-milenial','<p>Tingginya harga properti di pusat kota Jakarta membuat daerah penyangga seperti Bogor, Depok, Tangerang, dan Bekasi (Bodetabek) mengalami lonjakan peminat. Dengan pembangunan LRT Jabodebek dan jalan tol baru, mobilitas kini semakin singkat dan praktis.</p><p>Selain harga yang jauh lebih ramah di kantong, kawasan ini menawarkan lingkungan udara yang lebih segar dan ruang terbuka hijau yang ramah bagi tumbuh kembang anak.</p>','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80','Analisis Properti','Mengapa Bogor dan Bekasi Jadi Incaran Rumah Milenial - Perumahanku','Tren kepemilikan rumah di kawasan penyangga Jabodetabek dengan akses transportasi modern.',0,'2026-11-04 12:58:41','2026-11-16 12:58:41','2026-11-18 15:34:47'),
(4,'Panduan Lengkap Memilih Perumahan di Lampung 2026: Lokasi Strategis, Harga, dan Simulasi KPR','panduan-memilih-perumahan-di-lampung-strategis-kpr','
<p>Mencari hunian impian di Provinsi Lampung kini semakin menjanjikan. Perkembangan infrastruktur yang masif, mulai dari tersambungnya <strong>Jalan Tol Trans Sumatera (JTTS)</strong>, modernisasi Bandara Radin Inten II, hingga tumbuhnya kawasan pendidikan dan pusat niaga baru, menjadikan pasar properti di Lampung kian bergairah bagi generasi milenial dan keluarga muda.</p>

<h2>Mengapa Memilih Tinggal dan Berinvestasi Perumahan di Lampung?</h2>
<p>Bagi Anda yang sedang merencanakan kepemilikan rumah pertama, Provinsi Lampung menawarkan keunggulan yang sulit ditandingi oleh kota-kota metropolitan lainnya:</p>
<ul>
    <li><strong>Keterjangkauan Harga:</strong> Harga tanah dan unit rumah di Lampung masih sangat rasional dengan potensi kenaikan nilai investasi (<em>capital gain</em>) mencapai 10-15% per tahun.</li>
    <li><strong>Aksesibilitas Tinggi:</strong> Akses antarkota kini jauh lebih cepat melalui jaringan jalan tol dan jalur lingkar bypass yang menghubungkan pusat kota ke sentra industri dan pariwisata.</li>
    <li><strong>Lingkungan Sehat & Asri:</strong> Udara yang lebih bersih, ruang terbuka hijau yang luas, serta suasana komunitas yang ramah menjamin tumbuh kembang keluarga yang optimal.</li>
</ul>

<h2>4 Kawasan Perumahan Paling Populer di Lampung</h2>

<h3>1. Kota Bandar Lampung (Pusat Aktivitas & Fasilitas Lengkap)</h3>
<p>Sebagai ibu kota provinsi, <a href=\"/perumahan-di-kota-bandar-lampung\" class=\"text-brand-600 font-semibold hover:underline\">Bandar Lampung</a> tetap menjadi pilihan utama bagi pekerja kantoran dan pebisnis. Kawasan seperti <strong>Rajabasa, Kedaton, dan Sukarame</strong> sangat diminati karena dekat dengan kampus-kampus ternama seperti Unila, UIN Raden Intan, dan pusat perbelanjaan modern.</p>

<h3>2. Kabupaten Lampung Selatan (Kawasan Penyangga Strategis)</h3>
<p>Kecamatan <strong>Natar dan Jati Agung</strong> di <a href=\"/perumahan-di-kabupaten-lampung-selatan\" class=\"text-brand-600 font-semibold hover:underline\">Kabupaten Lampung Selatan</a> menjadi primadona pengembangan perumahan baru skala besar. Keberadaan gerbang tol, Bandara Radin Inten II, dan kampus ITERA membuat kawasan ini berkembang pesat menjadi kota mandiri baru.</p>

<h3>3. Kota Metro (Kota Pendidikan yang Nyaman & Tertata)</h3>
<p>Dikenal dengan tata ruang kotanya yang rapi dan fasilitas pendidikan berkualitas, <a href=\"/perumahan-di-kota-metro\" class=\"text-brand-600 font-semibold hover:underline\">Kota Metro</a> menawarkan perumahan dengan lingkungan tenang dan biaya hidup yang sangat ramah keluarga.</p>

<h3>4. Kabupaten Pesawaran & Pringsewu</h3>
<p>Untuk Anda yang menginginkan udara segar pegunungan dan panorama asri, perumahan di koridor <a href=\"/perumahan-di-kabupaten-pesawaran\" class=\"text-brand-600 font-semibold hover:underline\">Pesawaran</a> dan <a href=\"/perumahan-di-kabupaten-pringsewu\" class=\"text-brand-600 font-semibold hover:underline\">Pringsewu</a> menawarkan keseimbangan sempurna antara kenyamanan tempat tinggal dan kemudahan akses ke Bandar Lampung.</p>

<h2>Pilihan Skema Pembiayaan: Rumah Subsidi FLPP vs Komersil</h2>
<p>Sebelum memutuskan membeli, tentukan skema yang paling cocok dengan kondisi finansial Anda:</p>
<ul>
    <li><strong>Rumah Subsidi (FLPP):</strong> Bunga flat 5% sampai lunas dengan tenor hingga 20 tahun, bebas PPN, dan angsuran mulai Rp 1 jutaan/bulan. Sangat pas untuk masyarakat berpenghasilan tetap di bawah batas maksimal pemerintah.</li>
    <li><strong>Rumah Komersil Modern:</strong> Menawarkan spesifikasi premium (lantai granit, genteng beton flat, smart door lock) serta fasilitas cluster lengkap seperti kolam renang, clubhouse, dan sistem satu gerbang (<em>one gate system</em>).</li>
</ul>

<h2>Tips Cerdas Sebelum Membayar Booking Fee</h2>
<ol>
    <li><strong>Cek Legalitas Sertifikat:</strong> Pastikan lahan sudah pecah sertifikat (SHM/HGB) dan izin Persetujuan Bangunan Gedung (PBG) sudah terbit.</li>
    <li><strong>Gunakan Simulasi KPR:</strong> Manfaatkan fitur <a href=\"/simulasi-kpr\" class=\"text-brand-600 font-semibold hover:underline\">Kalkulator Simulasi KPR Interaktif</a> kami untuk menghitung perkiraan cicilan bulanan dan DP yang ideal.</li>
    <li><strong>Survei Lokasi Langsung:</strong> Manfaatkan kemudahan fitur booking survei online untuk melihat kavling pilihan, pencahayaan matahari, dan kualitas material bangunan di lapangan.</li>
</ol>

<p>Siap menemukan rumah idaman keluarga Anda di Lampung? Jelajahi seluruh pilihan perumahan baru berkualitas melalui <a href=\"/perumahan\" class=\"text-brand-600 font-bold hover:underline\">Katalog Perumahan Terbaru</a> sekarang juga!</p>
','https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80','Panduan Beli Rumah','Panduan Memilih Perumahan di Lampung 2026: Lokasi Strategis & KPR','Cari perumahan baru di Lampung? Simak rekomendasi lokasi strategis di Bandar Lampung, Natar Lampung Selatan & Metro, perbandingan harga, dan tips pengajuan KPR.',1,'2026-11-17 15:34:47','2026-11-18 15:34:47','2026-11-18 15:34:47'),
(5,'Syarat dan Cara Pengajuan KPR Rumah Subsidi di Lampung: Gaji, Dokumen, dan Tips Lolos Cepat','syarat-cara-pengajuan-kpr-rumah-subsidi-lampung','
<p>Mendapatkan rumah impian dengan angsuran seringan biaya sewa kos bukan lagi sekadar angan-angan. Program <strong>Kredit Pemilikan Rumah (KPR) Bersubsidi</strong> skema Fasilitas Likuiditas Pembiayaan Perumahan (FLPP) pemerintah kini hadir di berbagai kawasan berkembang di Provinsi Lampung, seperti Natar, Jati Agung, dan Kota Metro.</p>

<p>Agar pengajuan KPR Anda tidak terganjal di meja analis perbankan, pahami syarat berkas, batasan gaji, dan strategi lolos verifikasi berikut ini.</p>

<h2>Apa Saja Keuntungan KPR Subsidi di Lampung?</h2>
<ul>
    <li><strong>Suku Bunga Super Ringan:</strong> Bunga flat 5% per tahun tetap sampai cicilan lunas.</li>
    <li><strong>Uang Muka (DP) Rendah:</strong> DP mulai 1% atau bahkan 0% melalui promo pengembang mitra.</li>
    <li><strong>Bebas PPN:</strong> Pembebasan Pajak Pertambahan Nilai sebesar 11% sehingga biaya awal jauh lebih hemat.</li>
    <li><strong>Tenor Panjang:</strong> Masa pinjaman fleksibel hingga 20 tahun, menjaga cicilan tetap di kisaran Rp 1 jutaan/bulan.</li>
</ul>

<h2>Syarat Utama Calon Penerima KPR Bersubsidi</h2>
<ol>
    <li>Warga Negara Indonesia (WNI) berusia minimal 21 tahun atau sudah menikah.</li>
    <li>Maksimal penghasilan pokok pemohon belum pernah memiliki rumah sebelumnya dan belum pernah menerima subsidi perumahan dari pemerintah.</li>
    <li>Memiliki masa kerja atau usaha produktif minimal 1 tahun berjalan.</li>
    <li>Memiliki Nomor Pokok Wajib Pajak (NPWP) dan Surat Pemberitahuan Tahunan (SPT) PPh 21.</li>
</ol>

<h2>Daftar Dokumen Wajib yang Harus Disiapkan</h2>
<ul>
    <li>Fotokopi e-KTP Pemohon dan Pasangan (bagi yang sudah menikah).</li>
    <li>Fotokopi Kartu Keluarga (KK) dan Surat Nikah/Cerai.</li>
    <li>Fotokopi NPWP Pribadi.</li>
    <li>Slip Gaji 3 bulan terakhir yang dilegalisir perusahaan.</li>
    <li>Surat Keterangan Kerja Asli (menyatakan status karyawan dan masa kerja).</li>
    <li>Rekening Koran bank penyalur gaji minimal 3 sampai 6 bulan terakhir.</li>
    <li>Surat Keterangan Belum Memiliki Rumah dari kelurahan/desa setempat.</li>
</ul>

<h2>Kunci Sukses Lolos BI Checking (SLIK OJK)</h2>
<p>Sebagian besar penolakan berkas KPR bukan disebabkan oleh besaran penghasilan, melainkan catatan riwayat kredit di <strong>Sistem Layanan Informasi Keuangan (SLIK OJK)</strong>. Berikut langkah pencegahannya:</p>
<ul>
    <li><strong>Lunasi Paylater & Pinjaman Online:</strong> Hindari keterlambatan pembayaran cicilan paylater, kartu kredit, atau pinjaman multiguna minimal 6 bulan sebelum mengajukan KPR.</li>
    <li><strong>Pertahankan Skor Kolektibilitas 1 (Lancar):</strong> Pastikan tidak ada tunggakan berjalan agar nama Anda bersih di mata perbankan.</li>
    <li><strong>Jaga Rasio Utang di Bawah 30%:</strong> Pastikan total seluruh angsuran berjalan Anda tidak melebihi sepertiga dari pendapatan bulanan.</li>
</ul>

<p>Ingin tahu perkiraan cicilan bulanan yang pas untuk anggaran Anda? Coba langsung <a href=\"/simulasi-kpr\" class=\"text-brand-600 font-bold hover:underline\">Simulasi Kalkulator KPR Kami</a> untuk melihat estimasi angsuran dan biaya awal secara transparan!</p>
','https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1200&q=80','Tips KPR','Syarat & Cara Pengajuan KPR Rumah Subsidi Lampung 2026 - Panduan Lengkap','Panduan lengkap syarat KPR subsidi di Lampung: berkas wajib, batasan gaji, cara cek SLIK OJK, dan strategi pengajuan cepat disetujui bank.',1,'2026-11-16 15:34:47','2026-11-18 15:34:47','2026-11-18 15:34:47'),
(6,'Kawasan Penyangga Bandar Lampung: Mengapa Natar dan Jati Agung Jadi Incaran Pemburu Rumah Idaman?','kawasan-penyangga-bandar-lampung-natar-jati-agung-incaran-rumah','
<p>Keterbatasan lahan dan tingginya harga rumah di pusat Kota Bandar Lampung mendorong pergeseran preferensi para pencari properti ke kawasan penyangga. Dua wilayah yang saat ini mengalami ledakan peminat paling tinggi adalah <strong>Kecamatan Natar dan Kecamatan Jati Agung</strong> di Kabupaten Lampung Selatan.</p>

<p>Lantas, apa saja daya tarik utama yang membuat kedua kawasan ini menjadi incaran utama keluarga muda dan investor properti?</p>

<h2>1. Aksesibilitas Kilat Berkat Jalan Tol Trans Sumatera</h2>
<p>Hadirnya Gerbang Tol Natar dan Gerbang Tol Kotabaru (Jati Agung) merevolusi mobilitas warga. Perjalanan menuju pusat Kota Bandar Lampung, Pelabuhan Bakauheni, maupun Bandara Radin Inten II kini dapat ditempuh hanya dalam waktu 15 hingga 25 menit tanpa hambatan macet jalan arteri.</p>

<h2>2. Magnet Kampus & Pusat Pendidikan Bertaraf Nasional</h2>
<p>Kawasan Jati Agung mendapat keuntungan besar dengan berdirinya <strong>Institut Teknologi Sumatera (ITERA)</strong> dan kompleks perkantoran baru. Ribuan mahasiswa, dosen, dan tenaga pengajar baru setiap tahunnya menciptakan kebutuhan hunian serta potensi sewa rumah dan kost yang sangat menguntungkan.</p>

<h2>3. Harga Tanah Lebih Terjangkau dengan Luas Lahan Lebih Lega</h2>
<p>Dengan anggaran yang sama untuk membeli rumah tipe studio atau kavling sempit di tengah kota, di Natar dan Jati Agung Anda sudah bisa memiliki rumah berhalaman luas (luas tanah 72-100 m²) dengan fasilitas carport 2 mobil dan sisa tanah belakang untuk pengembangan di masa depan.</p>

<h2>4. Konsep Perumahan Modern Bernuansa Asri</h2>
<p>Pengembang di kawasan penyangga kini tidak hanya menawarkan rumah subsidi biasa, melainkan klaster modern berkonsep <em>eco-living</em> dengan jalan lingkungan paving block lebar 8-10 meter, sistem drainase bawah tanah bebas banjir, instalasi fiber optic, serta taman bermain keluarga.</p>

<h2>Kesimpulan: Saat Tepat untuk Membeli Sebelum Harga Meroket</h2>
<p>Dengan terus bertambahnya proyek infrastruktur dan rencana reaktivasi pusat pemerintahan baru di Kotabaru, nilai tanah di koridor Natar dan Jati Agung diprediksi akan terus melonjak. Membeli unit rumah sekarang adalah langkah strategis untuk mengunci harga perdana terbaik.</p>

<p>Ingin melihat langsung lokasi perumahan asri di Lampung Selatan? Jadwalkan <a href=\"/perumahan\" class=\"text-brand-600 font-bold hover:underline\">Survei Lokasi Perumahan Sekarang</a> dan wujudkan hunian impian keluarga Anda!</p>
','https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80','Analisis Properti','Mengapa Natar & Jati Agung Lampung Jadi Incaran Rumah Idaman 2026','Ulasan potensi perumahan di kawasan penyangga Bandar Lampung (Natar & Jati Agung). Akses tol cepat, dekat bandara & kampus, serta harga rumah terjangkau.',1,'2026-11-15 15:34:47','2026-11-18 15:34:47','2026-11-18 15:34:47');

/*Table structure for table `bookings` */

DROP TABLE IF EXISTS `bookings`;

CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `house_type_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `preferred_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','done','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_project_id_foreign` (`project_id`),
  KEY `bookings_house_type_id_foreign` (`house_type_id`),
  CONSTRAINT `bookings_house_type_id_foreign` FOREIGN KEY (`house_type_id`) REFERENCES `house_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bookings` */

insert  into `bookings`(`id`,`project_id`,`house_type_id`,`name`,`phone`,`email`,`preferred_date`,`preferred_time`,`message`,`status`,`admin_notes`,`created_at`,`updated_at`) values 
(1,1,2,'Budi Santoso','081298765432','budisantoso88@gmail.com','2026-11-18','10:00 WIB (Pagi)','Halo min, saya tertarik survei Tipe 45 di Grand Harmony Residence, apakah bisa sekalian konsultasi simulasi KPR Bank BTN?','confirmed','Sudah dikonfirmasi via WhatsApp. Janji temu dengan Sales Mas Rian di Marketing Gallery.','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(2,1,3,'Citra Anggraini','085712348899','citra.anggraini@corporate.co.id','2026-11-20','14:00 WIB (Siang)','Mau lihat unit Hook C-01 yang 2 lantai beserta POV pemandangannya.','pending',NULL,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(3,2,4,'Dedy Pratama','081390901212','dedy.pratama@yahoo.com','2026-11-15','11:00 WIB','Survei lokasi Bukit Serpong Asri.','done','Survei selesai, calon buyer sedang siapkan berkas slip gaji untuk BI Checking KPR BCA.','2026-11-16 12:58:41','2026-11-16 12:58:41');

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

insert  into `cache`(`key`,`value`,`expiration`) values 
('perumahanku-cache-setting.company_address','s:27:\"Jl. Bandar Lampung, Lampung\";',2110283102),
('perumahanku-cache-setting.company_email','s:26:\"info@alamdamaiproperty.com\";',2110283102),
('perumahanku-cache-setting.company_name','s:24:\"Puri Alam Damai Property\";',2110283102),
('perumahanku-cache-setting.company_phone','s:4:\"072-\";',2110283102),
('perumahanku-cache-setting.facebook_url','s:52:\"https://facebook.com/property_murahlampungalamdamai/\";',2110283102),
('perumahanku-cache-setting.hero_subtitle','s:146:\"Pilihan perumahan baru terbaik dengan legalitas aman, cicilan KPR terjangkau, siteplan interaktif, dan survei lokasi langsung dari genggaman Anda.\";',2110283102),
('perumahanku-cache-setting.hero_title','s:50:\"Temukan Hunian Nyaman & Strategis untuk Masa Depan\";',2110283119),
('perumahanku-cache-setting.instagram_url','s:53:\"https://instagram.com/property_murahlampungalamdamai/\";',2110283102),
('perumahanku-cache-setting.office_hours','s:70:\"Senin - Minggu: 08.30 - 17.30 WIB (Marketing Gallery Buka Setiap Hari)\";',2110283102),
('perumahanku-cache-setting.site_name','s:10:\"Alam Damai\";',2110283098),
('perumahanku-cache-setting.site_tagline','s:40:\"Katalog Perumahan Baru & Impian Keluarga\";',2110283102),
('perumahanku-cache-setting.tiktok_url','s:51:\"https://tiktok.com/@property_murahlampungalamdamai/\";',2110283102),
('perumahanku-cache-setting.whatsapp_number','s:13:\"6282182860707\";',2110283102);

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `house_types` */

DROP TABLE IF EXISTS `house_types`;

CREATE TABLE `house_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `building_area` int DEFAULT NULL,
  `land_area` int DEFAULT NULL,
  `bedrooms` int NOT NULL DEFAULT '0',
  `bathrooms` int NOT NULL DEFAULT '0',
  `floors` int NOT NULL DEFAULT '1',
  `price` decimal(15,0) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `specifications` json DEFAULT NULL,
  `floor_plan_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `house_types_project_id_slug_unique` (`project_id`,`slug`),
  CONSTRAINT `house_types_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `house_types` */

insert  into `house_types`(`id`,`project_id`,`name`,`slug`,`building_area`,`land_area`,`bedrooms`,`bathrooms`,`floors`,`price`,`description`,`specifications`,`floor_plan_image`,`is_available`,`sort_order`,`created_at`,`updated_at`) values 
(1,1,'Tipe 36/60 (Asoka)','tipe-36-60-asoka',36,60,2,1,1,385000000,'Pilihan ideal bagi pasangan muda atau keluarga baru. Memiliki 2 kamar tidur fungsional, ruang keluarga lega terhubung ke taman belakang, serta carport luas untuk 1 mobil dan 2 motor.','{\"Air\": \"Sumur Bor + Pompa Otomatis & Toren Air 500L\", \"Atap\": \"Rangka Baja Ringan + Genteng Beton Flat\", \"Lantai\": \"Granit Homogeneous Tile 60x60\", \"Dinding\": \"Bata Ringan (Hebel) diplester & diaci, cat Weathercoat\", \"Listrik\": \"PLN 1.300 Watt Prabayar (Token)\", \"Pondasi\": \"Batu Kali & Beton Bertulang\", \"Sanitair\": \"Kloset Duduk Toto / setara + Shower\", \"Kusen & Pintu\": \"Alumunium Powder Coating & Solid Wood Panel\"}','https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',1,1,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(2,1,'Tipe 45/72 (Bougenville)','tipe-45-72-bougenville',45,72,2,1,1,520000000,'Desain kompak dengan langit-langit tinggi (high ceiling 4.2m) yang menghadirkan suasana ruangan sejuk dan mewah. Dilengkapi dapur bersih modern dan area servis belakang.','{\"Air\": \"PDAM Mandiri + Filter Air Baku\", \"Lantai\": \"Granit Tile 60x60 Polished\", \"Dinding\": \"Bata Merah Double Dinding\", \"Listrik\": \"PLN 2.200 Watt\", \"Pondasi\": \"Batu Kali & Pondasi Cakar Ayam (Siap 2 Lantai)\", \"Sanitair\": \"American Standard Toto Sanitary\", \"Kusen & Pintu\": \"Aluminium Hitam Anodized + Smart Door Lock\"}','https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',1,2,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(3,1,'Tipe 68/90 (Cempaka 2 Lantai)','tipe-68-90-cempaka-2-lantai',68,90,3,2,2,795000000,'Rumah 2 lantai premium dengan 3 kamar tidur lega, balkon pribadi lantai atas menghadap pemandangan hijau cluster, serta carport 2 mobil paralel.','{\"Pintu\": \"Solid Engineering Wood + Smart Digital Lockset (PIN/Fingerprint)\", \"Lantai\": \"Granite Tile 60x60 + Parquet Wood di Kamar Utama\", \"Tangga\": \"Railing Kaca Tempered + Kayu Merbau\", \"Listrik\": \"PLN 3.500 Watt Underground Cable\", \"Struktur\": \"Beton Bertulang K-250 Standar SNI\", \"Smart Home\": \"Smart Switch Lampu & CCTV Outdoor Terkoneksi Smartphone\"}','https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80',1,3,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(4,2,'Tipe 54/84 (Emerald)','tipe-54-84-emerald',54,84,3,2,1,745000000,'Hunian tropis minimalis modern dengan layout lapang, 3 kamar tidur, dan taman belakang yang cocok untuk santai sore.','{\"Kusen\": \"Alumunium YKK Standar Jepang\", \"Dinding\": \"Bata Merah Finishing Cat Dulux Weathershield\", \"Pondasi\": \"Pancang Mini Pile + Sloof Beton Bertulang\", \"Sanitair\": \"Grohe & Toto Flush System\"}',NULL,1,1,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(5,2,'Tipe 88/105 (Sapphire 2 Lantai)','tipe-88-105-sapphire-2-lantai',88,105,4,3,2,1180000000,'Hunian prestisius 2 lantai bagi keluarga mapan, dilengkapi master bedroom dengan ensuite bathroom dan walk-in wardrobe.','{\"Listrik\": \"PLN 4.400 Watt\", \"Struktur\": \"Beton Bertulang K-300 Siap Gempa\", \"Smart Home\": \"Door Lock, IP Camera, Sensor Gerak, Smart Lighting\"}',NULL,1,2,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(6,3,'Tipe 30/60 (Subsidi FLPP)','tipe-30-60-subsidi-flpp',30,60,2,1,1,185000000,'Rumah subsidi pemerintah program FLPP dengan suku bunga tetap 5% hingga 20 tahun. Cicilan mulai dari 1 jutaan per bulan.','{\"Air\": \"Sumur Pantek Bersih\", \"Dinding\": \"Batako Press Plester & Cat\", \"Listrik\": \"PLN 900 / 1.300 Watt\", \"Pondasi\": \"Batu Kali\", \"Rangka Atap\": \"Baja Ringan + Genteng Metal\"}',NULL,1,1,'2026-11-16 12:58:41','2026-11-16 12:58:41');

/*Table structure for table `images` */

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `imageable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imageable_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'exterior',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `images_imageable_type_imageable_id_index` (`imageable_type`,`imageable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `images` */

insert  into `images`(`id`,`imageable_type`,`imageable_id`,`path`,`caption`,`is_primary`,`sort_order`,`category`,`created_at`,`updated_at`) values 
(1,'App\\Models\\Project',1,'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80','Fasad Modern Tropis Grand Harmony',1,0,'exterior','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(2,'App\\Models\\Project',1,'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80','Suasana Gerbang Cluster & Lingkungan Hijau',0,0,'exterior','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(3,'App\\Models\\Project',1,'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80','Ruang Tamu & Desain Open-Space Nyaman',0,0,'interior','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(4,'App\\Models\\Project',1,'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1200&q=80','Area Kamar Tidur Utama dengan Pencahayaan Alami',0,0,'interior','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(12,'App\\Models\\Project',2,'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80','Fasad Elegan Bukit Serpong Asri',1,0,'exterior','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(13,'App\\Models\\Project',2,'https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?auto=format&fit=crop&w=1200&q=80','Interior Living Room Scandinavian',0,0,'interior','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(14,'App\\Models\\Project',3,'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80','Fasad Griya Permata Cikarang',1,0,'exterior','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(85,'App\\Models\\Unit',50,'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80','POV Nyata dari Kavling A-01',1,1,'pov','2026-11-16 13:51:17','2026-11-16 13:51:17');

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `kpr_banks` */

DROP TABLE IF EXISTS `kpr_banks`;

CREATE TABLE `kpr_banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interest_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `max_tenor` int NOT NULL DEFAULT '20',
  `min_dp_percent` decimal(5,2) NOT NULL DEFAULT '10.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kpr_banks` */

insert  into `kpr_banks`(`id`,`bank_name`,`logo`,`interest_rate`,`max_tenor`,`min_dp_percent`,`is_active`,`sort_order`,`created_at`,`updated_at`) values 
(1,'Bank BTN (Spesialis KPR)',NULL,4.50,30,5.00,1,1,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(2,'Bank Mandiri',NULL,4.88,25,5.00,1,2,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(3,'Bank BCA',NULL,5.25,20,10.00,1,3,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(4,'Bank BSI (Syariah Tanpa Riba)',NULL,4.75,25,5.00,1,4,'2026-11-16 12:58:41','2026-11-16 12:58:41'),
(5,'Bank BRI',NULL,4.99,25,5.00,1,5,'2026-11-16 12:58:41','2026-11-16 12:58:41');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2024_01_01_000001_create_settings_table',1),
(5,'2024_01_01_000002_create_projects_table',1),
(6,'2024_01_01_000003_create_house_types_table',1),
(7,'2024_01_01_000004_create_units_table',1),
(8,'2024_01_01_000005_create_images_table',1),
(9,'2024_01_01_000006_create_bookings_table',1),
(10,'2024_01_01_000007_create_kpr_banks_table',1),
(11,'2024_01_01_000008_create_blog_posts_table',1),
(12,'2024_01_01_000009_add_siteplan_slice_to_units_table',2);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `facilities` json DEFAULT NULL,
  `brochure_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `siteplan_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','coming_soon','sold_out') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` text COLLATE utf8mb4_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `projects` */

insert  into `projects`(`id`,`name`,`slug`,`tagline`,`description`,`address`,`city`,`district`,`province`,`latitude`,`longitude`,`facilities`,`brochure_url`,`siteplan_image`,`status`,`seo_title`,`seo_description`,`is_featured`,`sort_order`,`created_at`,`updated_at`) values 
(1,'Grand Harmony Residence','grand-harmony-residence','Hunian Teduh Ramah Lingkungan dengan Konsep Smart Eco Living','Grand Harmony Residence merupakan kawasan perumahan modern eksklusif yang memadukan keasrian alam pegunungan dengan kemudahan akses perkotaan. Mengusung konsep Smart Eco Living dengan sistem pencahayaan dan sirkulasi alami, clubhouse keluarga, keamanan 24 jam one-gate system, serta dekat stasiun KRL dan pintu tol lingkar luar.','Jl. ZA. Pagar Alam No. 45, Gedong Meneng','Kota Bandar Lampung','Rajabasa','Lampung',-5.37890000,105.24580000,'[\"Keamanan 24 Jam & One Gate Access Card\", \"CCTV Terintegrasi di Seluruh Cluster\", \"Clubhouse & Kolam Renang Anak\", \"Masjid Jamie dalam Cluster\", \"Taman Bermain Anak & Gazebo Santai\", \"Jogging Track & Lapangan Olahraga 3-on-3\", \"Jaringan Listrik & Fiber Optic Bawah Tanah\", \"Row Jalan Lebar (8 - 10 Meter) Paving Block\"]',NULL,'storage/sample-siteplan.jpg','active','Grand Harmony Residence - Perumahan Modern Asri di Kota Bandar Lampung','Cari perumahan baru di Bandar Lampung? Grand Harmony Residence menawarkan rumah subsidi & komersil cicilan mulai 2 jutaan/bulan dengan fasilitas clubhouse lengkap.',1,1,'2026-11-16 12:58:41','2026-11-18 22:24:00'),
(2,'Bukit Serpong Asri','bukit-serpong-asri','Hunian Eksklusif 15 Menit ke Stasiun Rawabuntu & Tol BSD','Bukit Serpong Asri berlokasi strategis di kawasan penyangga BSD Serpong. Menawarkan kemudahan mobilitas kerja ke Jakarta melalui KRL dan akses tol langsung. Dilengkapi fasilitas modern komersil, underground utility, jogging track tepi danau buatan, dan keamanan 24 jam berbasis kartu RFID.','Jl. Raya Lintas Sumatera KM 18, Natar','Kabupaten Lampung Selatan','Natar','Lampung',-5.31298000,105.18921000,'[\"Akses Tol Trans Sumatera & Bandara Radin Inten II\", \"10 Menit ke Stasiun Kereta Api Rejosari\", \"Underground Cable Drainage Bebas Banjir\", \"Access Card One Gate & CCTV 24 Jam\", \"Danau Komunal & Tepi Jogging Track\", \"Commercial Area & Minimarket 24 Jam\"]',NULL,'storage/siteplans/siteplan_bukit-serpong-asri_1794836015.jpg','active','Bukit Serpong Asri - Rumah Nyaman Dekat Bandara Radin Inten II Lampung Selatan','Hunian strategis dekat pintu tol dan bandara di Natar, Lampung Selatan. Cicilan ringan dan lingkungan asri ramah keluarga.',1,2,'2026-11-16 12:58:41','2026-11-18 22:24:00'),
(3,'Griya Permata Cikarang','griya-permata-cikarang','Rumah Subsidi & Komersil Terjangkau Siap Huni Bebas Banjir','Griya Permata Cikarang hadir menjawab kebutuhan para pekerja kawasan industri dan keluarga muda yang mendambakan rumah sendiri dengan angsuran seringan bayar kost. Lokasi dekat kawasan GIIC, akses tol Cikarang Barat, dan sentra perbelanjaan.','Jl. AH Nasution No. 88, Yosodadi','Kota Metro','Metro Timur','Lampung',-5.11320000,105.30680000,'[\"Dekat Kawasan Pendidikan & Kampus Ternama\", \"Angsuran Flat KPR Subsidi mulai 1 Jutaan/bulan\", \"Jalan Lingkungan Cor Beton Tebal\", \"Bebas Banjir dan Dataran Tinggi\", \"Dekat RSUD Ahmad Yani & Pusat Perbelanjaan\"]',NULL,'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80','active','Griya Permata Cikarang - Rumah Murah & Nyaman di Kota Metro Lampung','Perumahan subsidi dan komersil terjangkau di Kota Metro Lampung. Dekat fasilitas umum, angsuran flat mulai 1 jutaan/bulan.',1,3,'2026-11-16 12:58:41','2026-11-18 22:24:00');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('4bXHM9LwrTrQYnwJF2oSvaFeqdWoWCnNF56DPuuo',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiI3c0hJYXBrS2J0MVQ4OGU5OHM1UGRmQmUxcmJSaDI3OFJIZ0o0bzhOIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1794834663),
('AMR9weqy35eViZvaTZyTQjDQMMbPw6gp7qupSIpB',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','eyJfdG9rZW4iOiJOWU5Ta0FlNEM2SzhoOXphcmhzcFRISDVnOW5rY3VTMDBSeUZkQUZCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3BlcnVtYWhhbmt1LnRlc3RcL3BlcnVtYWhhblwvYnVraXQtc2VycG9uZy1hc3JpIiwicm91dGUiOiJwcm9qZWN0cy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiYzJlMjUyMzRmZmY5MDY3NzQwNDI3NjVkODE1OGMyNWNjNzFhYjhiZTg1OWZiNDZjOTRmODNlNmY0Y2QwNGI4NyJ9',1794927159),
('azkf3XQ0BigEzktS0i3STthS1jsKp6MDr2hcvBwh',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJ4cTVxWmNVRjBKRmVOOElvMHRzblZTR3Qwa2xQWGQ1REtTS2pKYzJNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1794834626),
('b5Hsh8fx6AdJHzadypRWp9rvvttCtXnKHe8JKS5Y',1,'127.0.0.1','Symfony','eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiSVp1c2dmdDcydDlpbEJ1c3RkNFJYcTlzMDk3TkpXU0FLcDZ0TjJhcyIsInBhc3N3b3JkX2hhc2hfd2ViIjoiYzJlMjUyMzRmZmY5MDY3NzQwNDI3NjVkODE1OGMyNWNjNzFhYjhiZTg1OWZiNDZjOTRmODNlNmY0Y2QwNGI4NyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FkbWluXC9wcm9qZWN0c1wvMVwvc2l0ZXBsYW4tbWFwcGVyIiwicm91dGUiOiJhZG1pbi5wcm9qZWN0cy5zaXRlcGxhbi5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1794835495),
('DLtbmdgWBGfpHMwhhrMdRPDrCiqa5pKEGFFPNral',1,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJtUXNPUTA4SnRqNWt5TlRLR3Q2cjRrSDZIaFFwTGcwdm5XaFVxMXNHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvYWRtaW5cL2Rhc2hib2FyZCIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9hZG1pbiJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiJjMmUyNTIzNGZmZjkwNjc3NDA0Mjc2NWQ4MTU4YzI1Y2M3MWFiOGJlODU5ZmI0NmM5NGY4M2U2ZjRjZDA0Yjg3In0=',1794838741),
('EgTkWsSYAo2IMdxHU5naCIsGStGfmQeKKPGttXuc',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJVaXRQZVBkTTR5Q1dqZHdTcUVPeE5FWVBlcEV2QmxtbFdHM2prS0RkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvcGVydW1haGFuXC9ncmFuZC1oYXJtb255LXJlc2lkZW5jZSIsInJvdXRlIjoicHJvamVjdHMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1794835502),
('fgI9mujEMQHHSsoCL5swpdWC3JsTqqKxly6XZixS',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJVcUlJdmszbmFTdTc3R3d4eG8wcWljVHRkbkxNc0ZTOGxqVjRjNXFtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1794838738),
('IscEqdn2NNkgdchweJh8kQeA9VR7lFg84Hi2CteG',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJNbU9oSlhKeGJwcmhkMjVIR2RNVkhyaldGRWpyaWw4M2RZQUJpTVlnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1794834678),
('KBOdmgiLx75H39aTPdnLbwk8I1Siuis9rQC3eQUs',1,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJBUjhTdTNxVWk3TzRTTEdGVGxIb3dtbUVyejhyNWZrVGZxanhnZTc2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvYWRtaW5cL2Rhc2hib2FyZCIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiYzJlMjUyMzRmZmY5MDY3NzQwNDI3NjVkODE1OGMyNWNjNzFhYjhiZTg1OWZiNDZjOTRmODNlNmY0Y2QwNGI4NyJ9',1794834678),
('Rp6ITlCCoMpqjtXeEhW92SagAxUn9Xdy3sx0FHbA',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','eyJfdG9rZW4iOiJBWFhhajZCeTVFUFE4cTdvYmNjMDlaa0ZJZk1sbDFsTDkxMXhlak5iIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3BlcnVtYWhhbmt1LnRlc3RcL2FkbWluXC9iYW5rcyIsInJvdXRlIjoiYWRtaW4uYmFua3MuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOltdLCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiJjMmUyNTIzNGZmZjkwNjc3NDA0Mjc2NWQ4MTU4YzI1Y2M3MWFiOGJlODU5ZmI0NmM5NGY4M2U2ZjRjZDA0Yjg3In0=',1794839327),
('WH8uQPNzCCjlKWm5vEYp5Pzop51Gm4gyWwQWCKAi',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJnOERWanQzSVZYVDE4V1RWRGRHSmpJVFNGTkhWaXZOSVlKYVZ5cGNsIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9hZG1pbiJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9hZG1pbiIsInJvdXRlIjoiYWRtaW4uIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1794835800),
('zGLX9NbDQh6t1j93RWYqFNV7jiYp6IcVLexm9MF7',1,'127.0.0.1','Symfony','eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiclA0d2tYYXNxT1BjdDlScXBIY25uQ042QmYwNWdLaDFXMFBxTmVEViIsInBhc3N3b3JkX2hhc2hfd2ViIjoiYzJlMjUyMzRmZmY5MDY3NzQwNDI3NjVkODE1OGMyNWNjNzFhYjhiZTg1OWZiNDZjOTRmODNlNmY0Y2QwNGI4NyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FkbWluXC9wcm9qZWN0c1wvZ3JhbmQtaGFybW9ueS1yZXNpZGVuY2VcL3NpdGVwbGFuLW1hcHBlciIsInJvdXRlIjoiYWRtaW4ucHJvamVjdHMuc2l0ZXBsYW4uaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1794836849);

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`key`,`value`,`group`,`type`,`label`,`created_at`,`updated_at`) values 
(1,'site_name','Alam Damai','general','text','Nama Website / Brand','2026-11-16 12:58:41','2026-11-17 13:44:58'),
(2,'site_tagline','Katalog Perumahan Baru & Impian Keluarga','general','text','Tagline','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(3,'company_name','Puri Alam Damai Property','company','text','Nama Perusahaan Pengembang','2026-11-16 12:58:41','2026-11-17 13:38:13'),
(4,'company_phone','072-','company','text','Telepon Kantor','2026-11-16 12:58:41','2026-11-17 13:38:13'),
(5,'whatsapp_number','6282182860707','company','text','Nomor WhatsApp Hotline','2026-11-16 12:58:41','2026-11-17 13:38:13'),
(6,'company_email','info@alamdamaiproperty.com','company','text','Email Resmi','2026-11-16 12:58:41','2026-11-17 13:38:13'),
(7,'company_address','Kantor Pemasaran Perumahan Alam Damai Residence, Lampung','company','text','Alamat Kantor','2026-11-16 12:58:41','2026-11-18 22:43:00'),
(8,'hero_title','Temukan Hunian Nyaman & Strategis untuk Masa Depan','home','text','Judul Hero Banner','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(9,'hero_subtitle','Pilihan perumahan baru terbaik dengan legalitas aman, cicilan KPR terjangkau, siteplan interaktif, dan survei lokasi langsung dari genggaman Anda.','home','text','Subjudul Hero Banner','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(10,'instagram_url','https://instagram.com/property_murahlampungalamdamai/','social','text','Instagram URL','2026-11-16 12:58:41','2026-11-17 13:38:13'),
(11,'facebook_url','https://facebook.com/property_murahlampungalamdamai/','social','text','Facebook URL','2026-11-16 12:58:41','2026-11-17 13:38:13'),
(12,'tiktok_url','https://tiktok.com/@property_murahlampungalamdamai/','social','text','TikTok URL','2026-11-16 12:58:41','2026-11-17 13:38:13'),
(13,'office_hours','Senin - Minggu: 08.30 - 17.30 WIB (Marketing Gallery Buka Setiap Hari)','company','text','Jam Operasional','2026-11-16 12:58:41','2026-11-16 12:58:41'),
(14,'google_maps_url','https://maps.app.goo.gl/NgnnxiPaY64W2LMR9','company','text','Tautan Google Maps Kantor','2026-11-18 22:43:00','2026-11-18 22:43:00'),
(15,'google_maps_embed','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127111.64558455754!2d105.11298312531257!3d-5.380499958849488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40db2025f8e163%3A0xa1f8aa0293849664!2sKantor%20Pemasaran%20Perumahan%20Alam%20Damai%20Residence!5e0!3m2!1sen!2sid!4v1795016553251!5m2!1sen!2sid','company','text','Embed Google Maps URL (Iframe)','2026-11-18 22:43:00','2026-11-18 22:43:00');

/*Table structure for table `units` */

DROP TABLE IF EXISTS `units`;

CREATE TABLE `units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `house_type_id` bigint unsigned NOT NULL,
  `block` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `land_area` int DEFAULT NULL,
  `price` decimal(15,0) NOT NULL DEFAULT '0',
  `facing` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','booked','sold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `pov_description` text COLLATE utf8mb4_unicode_ci,
  `position_x` decimal(8,4) DEFAULT NULL,
  `position_y` decimal(8,4) DEFAULT NULL,
  `polygon_coords` text COLLATE utf8mb4_unicode_ci,
  `slice_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_pct` decimal(8,4) DEFAULT NULL,
  `height_pct` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `units_project_id_foreign` (`project_id`),
  KEY `units_house_type_id_foreign` (`house_type_id`),
  CONSTRAINT `units_house_type_id_foreign` FOREIGN KEY (`house_type_id`) REFERENCES `house_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `units_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `units` */

insert  into `units`(`id`,`project_id`,`house_type_id`,`block`,`number`,`land_area`,`price`,`facing`,`status`,`pov_description`,`position_x`,`position_y`,`polygon_coords`,`slice_image`,`width_pct`,`height_pct`,`created_at`,`updated_at`) values 
(50,2,4,'A','01',NULL,0,'Utara','available','Menghadap Danau Buatan yg asri',44.5700,75.3700,'[[43.65,74.28],[45.48,74.28],[45.48,76.45],[43.65,76.45]]','slices/slice_bukit-serpong-asri_a-01_1794837077.jpg',1.8300,2.1700,'2026-11-16 13:51:17','2026-11-16 13:51:17');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`avatar`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Administrator Perumahanku','admin@perumahanku.id',NULL,'2026-11-16 12:58:41','$2y$12$PFz7mTF9lsfl1Hm.lb1v7O8sqQwETzfkhmqU7iHkQBPdowzHBHu6i',NULL,'2026-11-16 12:58:41','2026-11-16 12:58:41');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
