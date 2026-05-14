<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VvipDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Celestial Cards
        $cards = [
            ['numeral' => 'I', 'symbol' => '👑', 'name' => 'The Crown', 'meaning' => 'Kekuasaan dan wibawa menghampiri Anda. Ambil kendali.'],
            ['numeral' => 'II', 'symbol' => '🔥', 'name' => 'The Inferno', 'meaning' => 'Semangat membara. Transformasi besar sedang terjadi.'],
            ['numeral' => 'III', 'symbol' => '🌊', 'name' => 'The Abyss', 'meaning' => 'Kedalaman jiwa. Rahasia besar akan terungkap.'],
            ['numeral' => 'IV', 'symbol' => '⚡', 'name' => 'The Surge', 'meaning' => 'Energi tak terduga. Peluang datang secepat kilat.'],
            ['numeral' => 'V', 'symbol' => '🌙', 'name' => 'The Eclipse', 'meaning' => 'Perubahan fase. Biarkan yang lama pergi.'],
            ['numeral' => 'VI', 'symbol' => '💎', 'name' => 'The Prism', 'meaning' => 'Kejelasan pikiran. Keputusan Anda tepat hari ini.'],
            ['numeral' => 'VII', 'symbol' => '🦅', 'name' => 'The Ascent', 'meaning' => 'Ambisi tinggi. Anda sedang naik ke puncak.'],
            ['numeral' => 'VIII', 'symbol' => '⏳', 'name' => 'The Epoch', 'meaning' => 'Kesabaran menghasilkan buah. Waktu ada di pihak Anda.'],
            ['numeral' => 'IX', 'symbol' => '🗝️', 'name' => 'The Key', 'meaning' => 'Jawaban yang Anda cari sudah ada di tangan.'],
            ['numeral' => 'X', 'symbol' => '🌀', 'name' => 'The Vortex', 'meaning' => 'Perputaran takdir. Terima arusnya, jangan melawan.'],
            ['numeral' => 'XI', 'symbol' => '🛡️', 'name' => 'The Bastion', 'meaning' => 'Perlindungan kuat. Orang-orang Anda setia.'],
            ['numeral' => 'XII', 'symbol' => '🌟', 'name' => 'The Nova', 'meaning' => 'Ledakan potensi. Saatnya bersinar terang.'],
            ['numeral' => 'XIII', 'symbol' => '🐍', 'name' => 'The Serpent', 'meaning' => 'Kebijaksanaan tersembunyi. Perhatikan tanda-tanda.'],
            ['numeral' => 'XIV', 'symbol' => '⚖️', 'name' => 'The Balance', 'meaning' => 'Harmoni sempurna. Semuanya selaras hari ini.'],
            ['numeral' => 'XV', 'symbol' => '🔮', 'name' => 'The Oracle', 'meaning' => 'Intuisi paling tajam. Percayai insting Anda.'],
            ['numeral' => 'XVI', 'symbol' => '🏔️', 'name' => 'The Summit', 'meaning' => 'Anda di ambang pencapaian terbesar. Jangan berhenti.'],
            ['numeral' => 'XVII', 'symbol' => '🌹', 'name' => 'The Rose', 'meaning' => 'Keindahan dalam duri. Cinta dan pengorbanan berjalan bersama.'],
            ['numeral' => 'XVIII', 'symbol' => '🦁', 'name' => 'The Legion', 'meaning' => 'Kekuatan kolektif. Pasukan Anda siap bertempur.'],
            ['numeral' => 'XIX', 'symbol' => '🎭', 'name' => 'The Masque', 'meaning' => 'Ada topeng yang perlu dilepas. Tunjukkan wajah asli.'],
            ['numeral' => 'XX', 'symbol' => '🧭', 'name' => 'The Compass', 'meaning' => 'Arah sudah jelas. Ikuti kompas internal Anda.'],
            ['numeral' => 'XXI', 'symbol' => '🕊️', 'name' => 'The Dove', 'meaning' => 'Perdamaian datang setelah badai. Tenangkan hati.'],
            ['numeral' => 'XXII', 'symbol' => '⚔️', 'name' => 'The Blade', 'meaning' => 'Ketajaman logika. Potong semua yang menghambat.'],
            ['numeral' => 'XXIII', 'symbol' => '🌋', 'name' => 'The Eruption', 'meaning' => 'Energi yang tertahan meledak. Gunakan dengan bijak.'],
            ['numeral' => 'XXIV', 'symbol' => '🦊', 'name' => 'The Fox', 'meaning' => 'Kecerdikan adalah senjata utama. Bergerak dengan halus.'],
            ['numeral' => 'XXV', 'symbol' => '🏛️', 'name' => 'The Pillar', 'meaning' => 'Anda adalah fondasi. Tanpa Anda, segalanya runtuh.'],
            ['numeral' => 'XXVI', 'symbol' => '🌌', 'name' => 'The Cosmos', 'meaning' => 'Perspektif lebih besar. Lihat gambaran utuh semesta.'],
            ['numeral' => 'XXVII', 'symbol' => '🐺', 'name' => 'The Wolf', 'meaning' => 'Naluri berburu yang kuat. Percaya pada insting liar.'],
            ['numeral' => 'XXVIII', 'symbol' => '🪶', 'name' => 'The Quill', 'meaning' => 'Kata-kata Anda punya kuasa. Tulis sejarah Anda sendiri.'],
            ['numeral' => 'XXIX', 'symbol' => '⚓', 'name' => 'The Anchor', 'meaning' => 'Stabilitas di tengah badai. Anda adalah jangkar tim.'],
            ['numeral' => 'XXX', 'symbol' => '🔔', 'name' => 'The Bell', 'meaning' => 'Sinyal penting akan datang. Dengarkan baik-baik.'],
            ['numeral' => 'XXXI', 'symbol' => '🌿', 'name' => 'The Root', 'meaning' => 'Kembali ke akar. Kekuatan terbesar ada di asal usul.'],
            ['numeral' => 'XXXII', 'symbol' => '🎯', 'name' => 'The Mark', 'meaning' => 'Fokus tanpa kompromi. Bidik sekali, tembak sekali.'],
            ['numeral' => 'XXXIII', 'symbol' => '🕰️', 'name' => 'The Hour', 'meaning' => 'Momentum sempurna. Jam ini adalah jam Anda.'],
            ['numeral' => 'XXXIV', 'symbol' => '🦋', 'name' => 'The Chrysalis', 'meaning' => 'Metamorfosis. Versi terbaik Anda sedang dilahirkan.'],
            ['numeral' => 'XXXV', 'symbol' => '🏹', 'name' => 'The Arrow', 'meaning' => 'Ditarik mundur untuk melesat jauh ke depan.'],
            ['numeral' => 'XXXVI', 'symbol' => '🌅', 'name' => 'The Dawn', 'meaning' => 'Babak baru dimulai. Tinggalkan kegelapan di belakang.'],
            ['numeral' => 'XXXVII', 'symbol' => '🐉', 'name' => 'The Dragon', 'meaning' => 'Kekuatan legendaris bangkit dari dalam diri.'],
            ['numeral' => 'XXXVIII', 'symbol' => '💫', 'name' => 'The Comet', 'meaning' => 'Langka dan bercahaya. Kehadiran Anda tak terlupakan.'],
            ['numeral' => 'XXXIX', 'symbol' => '🔱', 'name' => 'The Trident', 'meaning' => 'Tiga kekuatan bersatu: pikiran, hati, dan tindakan.'],
            ['numeral' => 'XL', 'symbol' => '🧊', 'name' => 'The Glacier', 'meaning' => 'Tenang di permukaan, dahsyat di kedalaman.'],
            ['numeral' => 'XLI', 'symbol' => '🌠', 'name' => 'The Wish', 'meaning' => 'Harapan terdalam akan segera terwujud. Tetap yakin.'],
            ['numeral' => 'XLII', 'symbol' => '♾️', 'name' => 'The Infinite', 'meaning' => 'Tidak ada batas. Potensi Anda melampaui imajinasi.'],
        ];

        $this->db->table('celestial_cards')->insertBatch($cards);

        // 2. Divine Verses
        $verses = [
            ['ar' => 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا', 'lt' => "Fa inna ma'al 'usri yusraa", 'mn' => 'Maka sesungguhnya bersama kesulitan ada kemudahan.', 'src' => 'QS. Al-Insyirah: 5'],
            ['ar' => 'إِنَّ اللَّهَ مَعَ الصَّابِرِينَ', 'lt' => "Innallaha ma'as-shaabirin", 'mn' => 'Sesungguhnya Allah bersama orang-orang yang sabar.', 'src' => 'QS. Al-Baqarah: 153'],
            ['ar' => 'وَتَوَكَّلْ عَلَى اللَّهِ ۚ وَكَفَىٰ بِاللَّهِ وَكِيلًا', 'lt' => "Wa tawakkal 'alallah, wa kafaa billahi wakeelaa", 'mn' => 'Dan bertawakallah kepada Allah. Dan cukuplah Allah sebagai pelindung.', 'src' => 'QS. Al-Ahzab: 3'],
            ['ar' => 'وَمَن يَتَّقِ اللَّهَ يَجْعَل لَّهُ مَخْرَجًا', 'lt' => 'Wa man yattaqillaha yaj\'al lahu makhrajaa', 'mn' => 'Barangsiapa bertakwa kepada Allah, niscaya Dia akan membukakan jalan keluar baginya.', 'src' => 'QS. At-Talaq: 2'],
            ['ar' => 'رَبِّ اشْرَحْ لِي صَدْرِي', 'lt' => 'Rabbisy-rahli shadri', 'mn' => 'Ya Tuhanku, lapangkanlah dadaku.', 'src' => 'QS. Taha: 25'],
            ['ar' => 'وَلَسَوْفَ يُعْطِيكَ رَبُّكَ فَتَرْضَىٰ', 'lt' => 'Wa lasaufa yu\'tiika rabbuka fatardaa', 'mn' => 'Dan kelak Tuhanmu pasti memberikan karunia-Nya kepadamu, sehingga engkau menjadi puas.', 'src' => 'QS. Ad-Duha: 5'],
            ['ar' => 'إِنَّ اللَّهَ لَا يُضِيعُ أَجْرَ الْمُحْسِنِينَ', 'lt' => 'Innallaha laa yudii\'u ajral muhsiniin', 'mn' => 'Sesungguhnya Allah tidak menyia-nyiakan pahala orang yang berbuat baik.', 'src' => 'QS. At-Taubah: 120'],
            ['ar' => 'وَاللَّهُ خَيْرُ الرَّازِقِينَ', 'lt' => 'Wallahu khairur-raaziqiin', 'mn' => 'Dan Allah adalah sebaik-baik pemberi rezeki.', 'src' => 'QS. Al-Jumu\'ah: 11'],
            ['ar' => 'لَا تَحْزَنْ إِنَّ اللَّهَ مَعَنَا', 'lt' => 'Laa tahzan innallaha ma\'anaa', 'mn' => 'Janganlah engkau bersedih, sesungguhnya Allah bersama kita.', 'src' => 'QS. At-Taubah: 40'],
            ['ar' => 'وَنَحْنُ أَقْرَبُ إِلَيْهِ مِنْ حَبْلِ الْوَرِيدِ', 'lt' => 'Wa nahnu aqrabu ilaihi min hablil wariid', 'mn' => 'Dan Kami lebih dekat kepadanya daripada urat lehernya sendiri.', 'src' => 'QS. Qaf: 16'],
            ['ar' => 'ادْعُونِي أَسْتَجِبْ لَكُمْ', 'lt' => 'Ud\'uunii astajib lakum', 'mn' => 'Berdoalah kepada-Ku, niscaya akan Aku perkenankan bagimu.', 'src' => 'QS. Ghafir: 60'],
            ['ar' => 'إِنَّ اللَّهَ يُحِبُّ الْمُتَوَكِّلِينَ', 'lt' => 'Innallaha yuhibbul mutawakkiliin', 'mn' => 'Sesungguhnya Allah menyukai orang-orang yang bertawakal.', 'src' => 'QS. Ali Imran: 159'],
            ['ar' => 'فَاذْكُرُونِي أَذْكُرْكُمْ', 'lt' => 'Fadzkuruunii adzkurkum', 'mn' => 'Maka ingatlah kepada-Ku, niscaya Aku akan mengingat kalian.', 'src' => 'QS. Al-Baqarah: 152'],
            ['ar' => 'لَا يُكَلِّفُ اللَّهُ نَفْسًا إِلَّا وُسْعَهَا', 'lt' => 'Laa yukallifullaahu nafsan illaa wus\'ahaa', 'mn' => 'Allah tidak membebani seseorang melainkan sesuai kesanggupannya.', 'src' => 'QS. Al-Baqarah: 286'],
            ['ar' => 'وَعَسَىٰ أَن تَكْرَهُوا شَيْئًا وَهُوَ خَيْرٌ لَّكُمْ', 'lt' => 'Wa \'asaa an takrahuu syai\'an wa huwa khairul lakum', 'mn' => 'Boleh jadi kamu membenci sesuatu padahal ia amat baik bagimu.', 'src' => 'QS. Al-Baqarah: 216'],
        ];

        $this->db->table('divine_verses')->insertBatch($verses);

        // 3. Tarbiyah Mentors
        $mentors = [
            [
                'name' => 'Dr. Muhammad Ilham',
                'role' => 'CEO & Founder, Zenith Corp',
                'description' => 'Siap membimbing 3 orang untuk program intensif kepemimpinan korporat islami dan manajemen risiko.',
                'avatar_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=200&auto=format&fit=crop',
                'slots' => 3
            ],
            [
                'name' => 'Aisyah Rahman, M.Sc',
                'role' => 'Direktur FinTech Syariah',
                'description' => 'Fokus pada strategi startup, legalitas syariah, dan ekspansi pasar digital. Slot tersisa 1 orang.',
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop',
                'slots' => 1
            ],
            [
                'name' => 'Ustadz Hasan Al-Banna',
                'role' => 'Dewan Syuro Eksekutif',
                'description' => 'Tarbiyah intensif via Majlis eksklusif mengenai Adab Muamalah dan menjaga keseimbangan dunia-akhirat.',
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop',
                'slots' => 5
            ],
        ];

        $this->db->table('tarbiyah_mentors')->insertBatch($mentors);

        // 4. Tarbiyah Tenders
        $tenders = [
            [
                'title' => 'Sistem ERP Syariah',
                'company' => 'PT. Sovereign Teknologi Investama',
                'description' => 'Mencari vendor internal angkatan untuk pengembangan Modul Keuangan Syariah dengan nilai kontrak klasifikasi [A].',
                'classification' => 'A'
            ],
            [
                'title' => 'Ekspansi Jaringan Klinik',
                'company' => 'Sifa Medika Group',
                'description' => 'Dibuka porsi saham eksklusif (Mudarabah) untuk pembangunan 3 klinik cabang di Jawa Barat. Khusus anggota terverifikasi.',
                'classification' => 'B'
            ],
        ];

        $this->db->table('tarbiyah_tenders')->insertBatch($tenders);
    }
}
