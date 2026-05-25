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
            ['arabic' => 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا', 'latin' => "Fa inna ma'al 'usri yusraa", 'meaning' => 'Maka sesungguhnya bersama kesulitan ada kemudahan.', 'source' => 'QS. Al-Insyirah: 5'],
            ['arabic' => 'إِنَّ اللَّهَ مَعَ الصَّابِرِينَ', 'latin' => "Innallaha ma'as-shaabirin", 'meaning' => 'Sesungguhnya Allah bersama orang-orang yang sabar.', 'source' => 'QS. Al-Baqarah: 153'],
            ['arabic' => 'وَتَوَكَّلْ عَلَى اللَّهِ ۚ وَكَفَىٰ بِاللَّهِ وَكِيلًا', 'latin' => "Wa tawakkal 'alallah, wa kafaa billahi wakeelaa", 'meaning' => 'Dan bertawakallah kepada Allah. Dan cukuplah Allah sebagai pelindung.', 'source' => 'QS. Al-Ahzab: 3'],
            ['arabic' => 'وَمَن يَتَّقِ اللَّهَ يَجْعَل لَّهُ مَخْرَجًا', 'latin' => 'Wa man yattaqillaha yaj\'al lahu makhrajaa', 'meaning' => 'Barangsiapa bertakwa kepada Allah, niscaya Dia akan membukakan jalan keluar baginya.', 'source' => 'QS. At-Talaq: 2'],
            ['arabic' => 'رَبِّ اشْرَحْ لِي صَدْرِي', 'latin' => 'Rabbisy-rahli shadri', 'meaning' => 'Ya Tuhanku, lapangkanlah dadaku.', 'source' => 'QS. Taha: 25'],
            ['arabic' => 'وَلَسَوْفَ يُعْطِيكَ رَبُّكَ فَتَرْضَىٰ', 'latin' => 'Wa lasaufa yu\'tiika rabbuka fatardaa', 'meaning' => 'Dan kelak Tuhanmu pasti memberikan karunia-Nya kepadamu, sehingga engkau menjadi puas.', 'source' => 'QS. Ad-Duha: 5'],
            ['arabic' => 'إِنَّ اللَّهَ لَا يُضِيعُ أَجْرَ الْمُحْسِنِينَ', 'latin' => 'Innallaha laa yudii\'u ajral muhsiniin', 'meaning' => 'Sesungguhnya Allah tidak menyia-nyiakan pahala orang yang berbuat baik.', 'source' => 'QS. At-Taubah: 120'],
            ['arabic' => 'وَاللَّهُ خَيْرُ الرَّازِقِينَ', 'latin' => 'Wallahu khairur-raaziqiin', 'meaning' => 'Dan Allah adalah sebaik-baik pemberi rezeki.', 'source' => 'QS. Al-Jumu\'ah: 11'],
            ['arabic' => 'لَا تَحْزَنْ إِنَّ اللَّهَ مَعَنَا', 'latin' => 'Laa tahzan innallaha ma\'anaa', 'meaning' => 'Janganlah engkau bersedih, sesungguhnya Allah bersama kita.', 'source' => 'QS. At-Taubah: 40'],
            ['arabic' => 'وَنَحْنُ أَقْرَبُ إِلَيْهِ مِنْ حَبْلِ الْوَرِيدِ', 'latin' => 'Wa nahnu aqrabu ilaihi min hablil wariid', 'meaning' => 'Dan Kami lebih dekat kepadanya daripada urat lehernya sendiri.', 'source' => 'QS. Qaf: 16'],
            ['arabic' => 'ادْعُونِي أَسْتَجِبْ لَكُمْ', 'latin' => 'Ud\'uunii astajib lakum', 'meaning' => 'Berdoalah kepada-Ku, niscaya akan Aku perkenankan bagimu.', 'source' => 'QS. Ghafir: 60'],
            ['arabic' => 'إِنَّ اللَّهَ يُحِبُّ الْمُتَوَكِّلِينَ', 'latin' => 'Innallaha yuhibbul mutawakkiliin', 'meaning' => 'Sesungguhnya Allah menyukai orang-orang yang bertawakal.', 'source' => 'QS. Ali Imran: 159'],
            ['arabic' => 'فَاذْكُرُونِي أَذْكُرْكُمْ', 'latin' => 'Fadzkuruunii adzkurkum', 'meaning' => 'Maka ingatlah kepada-Ku, niscaya Aku akan mengingat kalian.', 'source' => 'QS. Al-Baqarah: 152'],
            ['arabic' => 'لَا يُكَلِّفُ اللَّهُ نَفْسًا إِلَّا وُسْعَهَا', 'latin' => 'Laa yukallifullaahu nafsan illaa wus\'ahaa', 'meaning' => 'Allah tidak membebani seseorang melainkan sesuai kesanggupannya.', 'source' => 'QS. Al-Baqarah: 286'],
            ['arabic' => 'وَعَسَىٰ أَن تَكْرَهُوا شَيْئًا وَهُوَ خَيْرٌ لَّكُمْ', 'latin' => 'Wa \'asaa an takrahuu syai\'an wa huwa khairul lakum', 'meaning' => 'Boleh jadi kamu membenci sesuatu padahal ia amat baik bagimu.', 'source' => 'QS. Al-Baqarah: 216'],
            ['arabic' => 'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ', 'latin' => "Iyyaaka na'budu wa iyyaaka nasta'iin", 'meaning' => 'Hanya kepada Engkaulah kami menyembah dan hanya kepada Engkaulah kami mohon pertolongan.', 'source' => 'QS. Al-Fatihah: 5'],
            ['arabic' => 'فَبِأَيِّ آلَاءِ رَبِّكُمَا تُكَذِّبَانِ', 'latin' => "Fa bi'ayyi aalaa'i rabbikumaa tukadzdzibaan", 'meaning' => 'Maka nikmat Tuhan kamu yang manakah yang kamu dustakan?', 'source' => 'QS. Ar-Rahman: 13'],
            ['arabic' => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ', 'latin' => "Allahu laa ilaaha illaa huwal hayyul qayyuum", 'meaning' => 'Allah, tidak ada tuhan selain Dia. Yang Mahahidup, Yang terus-menerus mengurus (makhluk-Nya).', 'source' => 'QS. Al-Baqarah: 255'],
            ['arabic' => 'قُلْ هُوَ اللَّهُ أَحَدٌ', 'latin' => "Qul huwallahu ahad", 'meaning' => 'Katakanlah (Muhammad), "Dialah Allah, Yang Maha Esa".', 'source' => 'QS. Al-Ikhlas: 1'],
            ['arabic' => 'وَإِذَا سَأَلَكَ عِبَادِي عَنِّي فَإِنِّي قَرِيبٌ', 'latin' => "Wa idzaa sa'alaka 'ibaadii 'annii fa innii qariib", 'meaning' => 'Dan apabila hamba-hamba-Ku bertanya kepadamu (Muhammad) tentang Aku, maka sesungguhnya Aku dekat.', 'source' => 'QS. Al-Baqarah: 186'],
            ['arabic' => 'لَئِن شَكَرْتُمْ لَأَزِيدَنَّكُمْ', 'latin' => "La'in syakartum la aziidannakum", 'meaning' => 'Sesungguhnya jika kamu bersyukur, niscaya Aku akan menambah (nikmat) kepadamu.', 'source' => 'QS. Ibrahim: 7'],
            ['arabic' => 'أَحَسِبَ النَّاسُ أَن يُتْرَكُوا أَن يَقُولُوا آمَنَّا وَهُمْ لَا يُفْتَنُونَ', 'latin' => "Ahasiban naasu ayyutrokuu ayyaquuluu aamannaa wa hum laa yuftanuun", 'meaning' => 'Apakah manusia mengira bahwa mereka dibiarkan hanya dengan mengatakan, "Kami telah beriman," dan mereka tidak diuji?', 'source' => 'QS. Al-\'Ankabut: 2'],
            ['arabic' => 'لَا تَقْنَطُوا مِن رَّحْمَةِ اللَّهِ', 'latin' => "Laa taqnathuu mir rahmatillaah", 'meaning' => 'Janganlah kamu berputus asa dari rahmat Allah.', 'source' => 'QS. Az-Zumar: 53'],
            ['arabic' => 'وَلَا تَهِنُوا وَلَا تَحْزَنُوا وَأَنتُمُ الْأَعْلَوْنَ إِن كُنتُم مُّؤْمِنِينَ', 'latin' => "Wa laa tahinuu wa laa tahzanuu wa antumul a'launa in kuntum mu'miniin", 'meaning' => 'Janganlah kamu bersikap lemah, dan janganlah pula kamu bersedih hati, padahal kamulah orang-orang yang paling tinggi derajatnya, jika kamu orang-orang yang beriman.', 'source' => 'QS. Ali Imran: 139'],
            ['arabic' => 'وَاصْبِرُوا ۚ إِنَّ اللَّهَ مَعَ الصَّابِرِينَ', 'latin' => "Wasbiruu innallaha ma'as-shaabiriin", 'meaning' => 'Dan bersabarlah. Sesungguhnya Allah beserta orang-orang yang sabar.', 'source' => 'QS. Al-Anfal: 46'],
            ['arabic' => 'وَأُفَوِّضُ أَمْرِي إِلَى اللَّهِ', 'latin' => "Wa ufawwidu amrii ilallaah", 'meaning' => 'Dan aku menyerahkan urusanku kepada Allah.', 'source' => 'QS. Ghafir: 44'],
            ['arabic' => 'وَاسْتَعِينُوا بِالصَّبْرِ وَالصَّلَاةِ', 'latin' => "Wasta'iinuu bis shabri was shalaah", 'meaning' => 'Dan mohonlah pertolongan (kepada Allah) dengan sabar dan salat.', 'source' => 'QS. Al-Baqarah: 45'],
            ['arabic' => 'فَاصْبِرْ صَبْرًا جَمِيلًا', 'latin' => "Fashbir shabran jamiilaa", 'meaning' => 'Maka bersabarlah engkau (Muhammad) dengan kesabaran yang baik.', 'source' => 'QS. Al-Ma\'arij: 5'],
            ['arabic' => 'قُل لَّن يُصِيبَنَا إِلَّا مَا كَتَبَ اللَّهُ لَنَا', 'latin' => "Qul lan yushiibanaa illaa maa kataballahu lanaa", 'meaning' => 'Katakanlah: "Sekali-kali tidak akan menimpa kami melainkan apa yang telah ditetapkan oleh Allah bagi kami."', 'source' => 'QS. At-Taubah: 51'],
            ['arabic' => 'رَبَّنَا لَا تُزِغْ قُلُوبَنَا بَعْدَ إِذْ هَدَيْتَنَا', 'latin' => "Rabbanaa laa tuzigh quluubanaa ba'da idz hadaitanaa", 'meaning' => 'Ya Tuhan kami, janganlah Engkau condongkan hati kami kepada kesesatan setelah Engkau berikan petunjuk kepada kami.', 'source' => 'QS. Ali Imran: 8'],
            ['arabic' => 'رَبَّنَا هَبْ لَنَا مِنْ أَزْوَاجِنَا وَذُرِّيَّاتِنَا قُرَّةَ أَعْيُنٍ', 'latin' => "Rabbanaa hab lanaa min azwaajinaa wa dzurriyyaatinaa qurrata a'yun", 'meaning' => 'Ya Tuhan kami, anugerahkanlah kepada kami pasangan kami dan keturunan kami sebagai penyenang hati.', 'source' => 'QS. Al-Furqan: 74'],
            ['arabic' => 'الَّذِي خَلَقَ الْمَوْتَ وَالْحَيَاةَ لِيَبْلُوَكُمْ أَيُّكُمْ أَحْسَنُ عَمَلًا', 'latin' => "Alladzii khalaqal mawta wal hayaata liyabluwakum ayyukum ahsanu 'amalaa", 'meaning' => 'Yang menciptakan mati dan hidup, untuk menguji kamu, siapa di antara kamu yang lebih baik amalnya.', 'source' => 'QS. Al-Mulk: 2'],
            ['arabic' => 'أَلَا بِذِكْرِ اللَّهِ تَطْمَئِنُّ الْقُلُوبُ', 'latin' => "Alaa bidzikrillaahi tathma'innul quluub", 'meaning' => 'Ingatlah, hanya dengan mengingat Allah hati menjadi tenteram.', 'source' => 'QS. Ar-Ra\'d: 28'],
            ['arabic' => 'رَبَّنَا آتِنَا فِي الدُّنْيَا حَسَنَةً وَفِي الْآخِرَةِ حَسَنَةً', 'latin' => "Rabbanaa aatinaa fid-dunyaa hasanah wa fil-aakhirati hasanah", 'meaning' => 'Ya Tuhan kami, berilah kami kebaikan di dunia dan kebaikan di akhirat.', 'source' => 'QS. Al-Baqarah: 201'],
            ['arabic' => 'اللَّهُ نُورُ السَّمَاوَاتِ وَالْأَرْضِ', 'latin' => "Allahu nuurus-samaawaati wal ardh", 'meaning' => 'Allah (Pemberi) cahaya (kepada) langit dan bumi.', 'source' => 'QS. An-Nur: 35'],
            ['arabic' => 'وَقُل رَّبِّ زِدْنِي عِلْمًا', 'latin' => "Wa qul rabbi zidnii 'ilmaa", 'meaning' => 'Dan katakanlah, "Ya Tuhanku, tambahkanlah ilmu kepadaku."', 'source' => 'QS. Taha: 114'],
            ['arabic' => 'رَبِّ لَا تَذَرْنِي فَرْدًا وَأَنتَ خَيْرُ الْوَارِثِينَ', 'latin' => "Rabbi laa tadzarnii fardaw wa anta khairul waaritsiin", 'meaning' => 'Ya Tuhanku, janganlah Engkau biarkan aku hidup seorang diri (tanpa keturunan) dan Engkaulah pewaris yang terbaik.', 'source' => 'QS. Al-Anbiya: 89'],
            ['arabic' => 'رَبِّ إِنِّي لِمَا أَنزَلْتَ إِلَيَّ مِنْ خَيْرٍ فَقِيرٌ', 'latin' => "Rabbi innii limaa anzalta ilayya min khairin faqiir", 'meaning' => 'Ya Tuhanku, sesungguhnya aku sangat memerlukan sesuatu kebaikan (makanan) yang Engkau turunkan kepadaku.', 'source' => 'QS. Al-Qasas: 24'],
            ['arabic' => 'يَا أَيُّهَا الَّذِينَ آمَنُوا اتَّقُوا اللَّهَ وَلْتَنظُرْ نَفْسٌ مَّا قَدَّمَتْ لِغَدٍ', 'latin' => "Yaa ayyuhal ladziina aamanut-taqullaha wal tanzhur nafsum maa qaddamat lighad", 'meaning' => 'Wahai orang-orang yang beriman! Bertakwalah kepada Allah dan hendaklah setiap orang memperhatikan apa yang telah diperbuatnya untuk hari esok.', 'source' => 'QS. Al-Hasyr: 18'],
            ['arabic' => 'وَمَا تُقَدِّمُوا لِأَنفُسِكُم مِّنْ خَيْرٍ تَجِدُوهُ عِندَ اللَّهِ هُوَ خَيْرًا وَأَعْظَمَ أَجْرًا', 'latin' => "Wamaa tuqaddimuu li'anfusikum min khairin tajiduuhu 'indallaahi huwa khairaw wa a'zhama ajraa", 'meaning' => 'Dan kebaikan apa saja yang kamu perbuat untuk dirimu, niscaya kamu memperoleh (balasan)nya di sisi Allah sebagai balasan yang paling baik dan yang paling besar pahalanya.', 'source' => 'QS. Al-Muzzammil: 20'],
            ['arabic' => 'رَّبِّ ارْحَمْهُمَا كَمَا رَبَّيَانِي صَغِيرًا', 'latin' => "Rabbir-hamhumaa kamaa rabbayaanii shaghiiraa", 'meaning' => 'Ya Tuhanku, sayangilah keduanya sebagaimana mereka berdua telah mendidik aku pada waktu kecil.', 'source' => 'QS. Al-Isra: 24'],
            ['arabic' => 'رَبَّنَا آتِنَا مِن لَّدُنكَ رَحْمَةً وَهَيِّئْ لَنَا مِنْ أَمْرِنَا رَشَدًا', 'latin' => "Rabbanaa aatinaa mil ladunka rahmatan wa hayyi' lanaa min amrinaa rasyadaa", 'meaning' => 'Ya Tuhan kami, berikanlah rahmat kepada kami dari sisi-Mu dan sempurnakanlah petunjuk yang lurus bagi kami dalam urusan kami.', 'source' => 'QS. Al-Kahf: 10'],
            ['arabic' => 'وَإِنَّكَ لَعَلَىٰ خُلُقٍ عَظِيمٍ', 'latin' => "Wa innaka la'alaa khuluqin 'azhiim", 'meaning' => 'Dan sesungguhnya engkau (Muhammad) benar-benar berbudi pekerti yang agung.', 'source' => 'QS. Al-Qalam: 4'],
            ['arabic' => 'إِنَّا هَدَيْنَاهُ السَّبِيلَ إِمَّا شَاكِرًا وَإِمَّا كَفُورًا', 'latin' => "Innaa hadainaahus-sabiila immaa syaakiraw wa immaa kafuuraa", 'meaning' => 'Sungguh, Kami telah menunjukkan kepadanya jalan yang lurus; ada yang bersyukur dan ada pula yang kufur.', 'source' => 'QS. Al-Insan: 3'],
            ['arabic' => 'إِنَّمَا الْمُؤْمِنُونَ الَّذِينَ إِذَا ذُكِرَ اللَّهُ وَجِلَتْ قُلُوبُهُمْ', 'latin' => "Innamal mu'minuunal-ladziina idzaa dzukirallahu wajilat quluubuhum", 'meaning' => 'Sesungguhnya orang-orang yang beriman adalah mereka yang apabila disebut nama Allah gemetar hatinya.', 'source' => 'QS. Al-Anfal: 2'],
            ['arabic' => 'وَأْمُرْ بِالْمَعْرُوفِ وَانْهَ عَنِ الْمُنكَرِ وَاصْبِرْ عَلَىٰ مَا أَصَابَكَ', 'latin' => "Wamur bil-ma'ruufi wanha 'anil-munkari wasbir 'alaa maa ashaabak", 'meaning' => 'Suruhlah (manusia) berbuat yang makruf dan cegahlah (mereka) dari yang mungkar dan bersabarlah terhadap apa yang menimpamu.', 'source' => 'QS. Luqman: 17'],
            ['arabic' => 'فَلَا تَعْلَمُ نَفْسٌ مَّا أُخْفِيَ لَهُم مِّن قُرَّةِ أَعْيُنٍ', 'latin' => "Falaa ta'lamu nafsum maa ukhfiya lahum min qurrati a'yun", 'meaning' => 'Maka tidak seorang pun mengetahui apa yang disembunyikan untuk mereka yaitu (bermacam-macam nikmat) yang menyedapkan pandangan mata.', 'source' => 'QS. As-Sajdah: 17'],
            ['arabic' => 'إِنَّ الَّذِينَ قَالُوا رَبُّنَا اللَّهُ ثُمَّ اسْتَقَامُوا تَتَنَزَّلُ عَلَيْهِمُ الْمَلَائِكَةُ', 'latin' => "Innalladziina qaaluu rabbunallahu tsummas taqaamuu tatanazzalu 'alaihimul malaa'ikah", 'meaning' => 'Sesungguhnya orang-orang yang berkata, "Tuhan kami adalah Allah" kemudian mereka meneguhkan pendirian mereka, maka malaikat-malaikat akan turun kepada mereka.', 'source' => 'QS. Fussilat: 30'],
            ['arabic' => 'إِنَّا فَتَحْنَا لَكَ فَتْحًا مُّبِينًا', 'latin' => "Innaa fatahnaa laka fat-ham mubiinaa", 'meaning' => 'Sungguh, Kami telah memberikan kepadamu kemenangan yang nyata.', 'source' => 'QS. Al-Fath: 1'],
            ['arabic' => 'إِنَّمَا أَمْرُهُ إِذَا أَرَادَ شَيْئًا أَن يَقُولَ لَهُ كُن فَيَكُونُ', 'latin' => "Innamaa amruhuu idzaa araada syai'an ay-yaquula lahuu kun fa yakuun", 'meaning' => 'Sesungguhnya urusan-Nya apabila Dia menghendaki sesuatu Dia hanya berkata kepadanya, "Jadilah!" Maka jadilah sesuatu itu.', 'source' => 'QS. Yasin: 82']
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
