<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Response;
use App\Exports\KuesionerAlumniExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MasterAlumni;
use App\Imports\AlumniImport;

class KuesionerController extends Controller
{
    public function import(Request $request)
{
    // 1. Validasi pastikan berkas wajib diisi dan bertipe excel
    $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file_excel.required' => 'Silakan pilih berkas Excel terlebih dahulu.',
            'file_excel.mimes' => 'Format berkas harus berupa .xlsx, .xls, atau .csv',
            'file_excel.max' => 'Ukuran berkas maksimal adalah 10 MB.'
        ]);

        try {
            // 2. Proses pembacaan atau import berkas Excel Anda di sini
            // Contoh jika menggunakan library Maatwebsite\Excel:
             Excel::import(new AlumniImport, $request->file('file_excel'));
            
            // SEMENTARA: Jika logic import Anda belum siap, gunakan ini untuk tes upload berhasil:
            // $file = $request->file('file_excel');

            return redirect()->back()->with('success', '⚡ Data master acuan alumni berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file_excel' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()]);
        }
    }
        public function index()
        {
            return view('kuesioner');
        }
        
        public function store(Request $request)
        {
            // 1. Validasi
            $request->validate([
                'no_mahasiswa' => 'required',
                'nama' => 'required',
                'f8_status_saat_ini' => 'required',
                'f301' => 'required',
            ]);

            $nimInput  = $request->no_mahasiswa;
            $nikInput  = $request->nik;
            $namaInput = $request->nama;
            $tahunlulus = $request->tahun_lulus;
            $kodeprodi= $request->kode_prodi;

            // Cek kecocokan data ke tabel master_alumnis
            $alumniValid = MasterAlumni::where('no_mahasiswa', $nimInput)
                                        ->where('nik', $nikInput)
                                        ->where('tahun_lulus', $tahunlulus)
                                        ->where('kode_prodi', $kodeprodi)
                                        ->first();

            // Jika kombinasi NIM dan NIK tidak ditemukan di database master
            if (!$alumniValid) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['autentikasi' => '❌ Maaf, No. Mahasiswa (NIM), NIK, Nama, Tahun Lulus, Kode Prodi Anda Salah Huruf/Angka atau tidak terdaftar sebagai data acuan alumni kelulusan Periksa Semua Pertanyaan Kembali. Ulangi Pengisian atau Hubungi Admin Tracer Study']);
            }

            // Jika nama tidak sesuai (toleran terhadap perbedaan huruf besar/kecil)
            if (strcasecmp(trim($alumniValid->nama), trim($namaInput)) !== 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['autentikasi' => '❌ Nama yang Anda masukkan tidak sesuai dengan data pemilik NIM ini.']);
            }

            // 2. String checklist f16
            $alasan_terpilih = [];
            for ($i = 1; $i <= 12; $i++) {
                $key = 'f16' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if ($request->has($key)) {
                    $alasan_terpilih[] = $key;
                }
            }
            $f16_alasan_string = !empty($alasan_terpilih) ? implode(',', $alasan_terpilih) : null;

            // 3. Direct Insert ke phpMyAdmin
            DB::table('kuesioner_alumnis')->updateOrInsert(
                ['no_mahasiswa' => $request->no_mahasiswa],
                [
                'user_id' => 1,
                'kode_PT' => $request->kode_PT ?? '072004',
                'tahun_lulus' => $request->tahun_lulus,
                'kode_prodi' => $request->kode_prodi,
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'f8_status_saat_ini' => match ($request->f8_status_saat_ini) {
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        default => $request->f8_status_saat_ini,
                    },
                'f504_mendapat_pekerjaan_6_bulan' => match ($request->f504_mendapat_pekerjaan_6_bulan) {
                        '1' => '1',
                        '2' => '2',
                        default => $request->f504_mendapat_pekerjaan_6_bulan,
                    },
                'f502_bulan_dapat_kerja' => $request->f502_bulan_dapat_kerja_ya,
                'f505_pendapatan_per_bulan' => $request->f505_pendapatan_per_bulan,
                'f506_bulan_dapat_kerja_setelahnya' => $request->f502_bulan_dapat_kerja_tidak,
                'f510_provinsi' => match ($request->f510_provinsi) {
                        'Prov. D.K.I. Jakarta'=> '10000',
                        'Prov. Jambi'=> '100000',
                        'Prov. Sumatera Selatan'=>'110000',
                        'Prov. Lampung'=> '120000',
                        'Prov. Kalimantan Barat'=> '130000',
                        'Prov. Kalimantan Tengah'=> '140000',
                        'Prov. Kalimantan Selatan'=> '150000',
                        'Prov. Kalimantan Timur'=> '160000',
                        'Prov. Sulawesi Utara'=> '170000',
                        'Prov. Sulawesi Tengah'=> '180000',
                        'Prov. Sulawesi Selatan'=> '190000',
                        'Prov. Jawa Barat'=> '20000',
                        'Prov. Sulawesi Tenggara'=> '200000',
                        'Prov. Maluku'=> '210000',
                        'Prov. Bali'=> '220000',
                        'Prov. Nusa Tenggara Barat'=> '230000',
                        'Prov. Nusa Tenggara Timur'=> '240000',
                        'Prov. Papua'=> '250000',
                        'Prov. Bengkulu'=> '260000',
                        'Prov. Maluku Utara'=> '270000',
                        'Prov. Banten'=> '280000',
                        'Prov. Kepulauan Bangka Belitung'=> '290000',
                        'Prov. Jawa Tengah'=> '30000',
                        'Prov. Gorontalo'=> '300000',
                        'Prov. Kepulauan Riau'=> '310000',
                        'Prov. Papua Barat'=> '320000',
                        'Prov. Sulawesi Barat'=> '330000',
                        'Prov. Kalimantan Utara'=> '340000',
                        'Luar Negeri'=> '350000',
                        'Prov. D.I. Yogyakarta'=> '40000',
                        'Prov. Jawa Timur'=> '50000',
                        'Prov. Aceh'=> '60000',
                        'Prov. Sumatera Utara'=> '70000',
                        'Prov. Sumatera Barat'=> '80000',
                        'Prov. Riau'=> '90000',
                        default => '0'
                },

                'f510_kab_kota' => match ($request->f510_kab_kota) {
                    'Kab. Kepulauan Seribu' => '10100',
                    'Kota Jakarta Selatan' => '16200',
                    'Kota Jakarta Timur' => '16000',
                    'Kota Jakarta Pusat' => '16300',
                    'Kota Jakarta Barat' => '16400',
                    'Kota Jakarta Utara' => '16100',
                    'Kab. Batanghari' => '100100',
                    'Kab. Bungo' => '100200',
                    'Kab. Kerinci' => '100500',
                    'Kab. Merangin' => '100900',
                    'Kab. Muaro Jambi' => '100700',
                    'Kab. Sarolangun' => '100300',
                    'Kab. Tanjung Jabung Barat' => '100400',
                    'Kab. Tanjung Jabung Timur' => '100800',
                    'Kab. Tebo' => '100600',
                    'Kota Jambi' => '106000',
                    'Kota Sungai Penuh' => '106100',
                    'Kab. Banyuasin' => '110700',
                    'Kab. Empat Lawang' => '111100',
                    'Kab. Lahat' => '110500',
                    'Kab. Muara Enim' => '110400',
                    'Kab. Musi Banyuasin' => '110100',
                    'Kab. Musi Rawas' => '110600',
                    'Kab. Musi Rawas Utara' => '111300',
                    'Kab. Ogan Ilir' => '111000',
                    'Kab. Ogan Komering Ilir' => '110200',
                    'Kab. Ogan Komering Ulu' => '110300',
                    'Kab. Ogan Komering Ulu Selatan' => '110900',
                    'Kab. Ogan Komering Ulu Timur' => '110800',
                    'Kab. Penukal Abab Lematang Ilir' => '111200',
                    'Kota Lubuk Linggau' => '116200',
                    'Kota Pagar Alam' => '116300',
                    'Kota Palembang' => '116000',
                    'Kota Prabumulih' => '116100',
                    'Kab. Lampung Barat' => '120400',
                    'Kab. Lampung Selatan' => '120100',
                    'Kab. Lampung Tengah' => '120200',
                    'Kab. Lampung Timur' => '120700',
                    'Kab. Lampung Utara' => '120300',
                    'Kab. Mesuji' => '121100',
                    'Kab. Pesawaran' => '120900',
                    'Kab. Pesisir Barat' => '121300',
                    'Kab. Pringsewu' => '121000',
                    'Kab. Tanggamus' => '120600',
                    'Kab. Tulang Bawang' => '120500',
                    'Kab. Tulang Bawang Barat' => '121200',
                    'Kab. Way Kanan' => '120800',
                    'Kota Bandar Lampung' => '126000',
                    'Kota Metro' => '126100',
                    'Kab. Bengkayang' => '130800',
                    'Kab. Kapuas Hulu' => '130500',
                    'Kab. Kayong Utara' => '131200',
                    'Kab. Ketapang' => '130600',
                    'Kab. Kubu Raya' => '131300',
                    'Kab. Landak' => '130900',
                    'Kab. Melawi' => '131100',
                    'Kab. Mempawah' => '130200',
                    'Kab. Sambas' => '130100',
                    'Kab. Sanggau' => '130300',
                    'Kab. Sekadau' => '131000',
                    'Kab. Sintang' => '130400',
                    'Kota Pontianak' => '136000',
                    'Kota Singkawang' => '136100',
                    'Kab. Barito Selatan' => '140200',
                    'Kab. Barito Timur' => '141300',
                    'Kab. Barito Utara' => '140300',
                    'Kab. Gunung Mas' => '141000',
                    'Kab. Kapuas' => '140100',
                    'Kab. Katingan' => '140600',
                    'Kab. Kotawaringin Barat' => '140500',
                    'Kab. Kotawaringin Timur' => '140400',
                    'Kab. Lamandau' => '140900',
                    'Kab. Murung Raya' => '141200',
                    'Kab. Pulang Pisau' => '141100',
                    'Kab. Seruyan' => '140700',
                    'Kab. Sukamara' => '140800',
                    'Kota Palangka Raya' => '146000',
                    'Kab. Balangan' => '151000',
                    'Kab. Banjar' => '150100',
                    'Kab. Barito Kuala' => '150300',
                    'Kab. Hulu Sungai Selatan' => '150500',
                    'Kab. Hulu Sungai Tengah' => '150600',
                    'Kab. Hulu Sungai Utara' => '150700',
                    'Kab. Kotabaru' => '150900',
                    'Kab. Tabalong' => '150800',
                    'Kab. Tanah Bumbu' => '151100',
                    'Kab. Tanah Laut' => '150200',
                    'Kab. Tapin' => '150400',
                    'Kota Banjarbaru' => '156100',
                    'Kota Banjarmasin' => '156000',
                    'Kab. Berau' => '160300',
                    'Kab. Kutai Barat' => '160900',
                    'Kab. Kutai Kartanegara' => '160200',
                    'Kab. Kutai Timur' => '161000',
                    'Kab. Mahakam Ulu' => '161200',
                    'Kab. Paser' => '160100',
                    'Kab. Penajam Paser Utara' => '161100',
                    'Kota Balikpapan' => '166100',
                    'Kota Bontang' => '166300',
                    'Kota Samarinda' => '166000',
                    'Kab. Bolaang Mongondow' => '170100',
                    'Kab. Bolaang Mongondow Selatan' => '171200',
                    'Kab. Bolaang Mongondow Timur' => '171100',
                    'Kab. Bolaang Mongondow Utara' => '170800',
                    'Kab. Kep. Sangihe' => '170300',
                    'Kab. Kepulauan Siau Tagulandang Biaro' => '170900',
                    'Kab. Kepulauan Talaud' => '170400',
                    'Kab. Minahasa' => '170200',
                    'Kab. Minahasa Selatan' => '170500',
                    'Kab. Minahasa Tenggara' => '171000',
                    'Kab. Minahasa Utara' => '170600',
                    'Kota Bitung' => '176100',
                    'Kota Kotamobagu' => '176300',
                    'Kota Manado' => '176000',
                    'Kota Tomohon' => '176200',
                    'Kab. Banggai' => '180400',
                    'Kab. Banggai Kepulauan' => '180100',
                    'Kab. Banggai Laut' => '181100',
                    'Kab. Buol' => '180500',
                    'Kab. Donggala' => '180200',
                    'Kab. Morowali' => '180700',
                    'Kab. Morowali Utara' => '181200',
                    'Kab. Parigi Moutong' => '180800',
                    'Kab. Poso' => '180300',
                    'Kab. Sigi' => '181000',
                    'Kab. Tojo Una-Una' => '180900',
                    'Kab. Tolitoli' => '180600',
                    'Kota Palu' => '186000',
                    'Kab. Bantaeng' => '191000',
                    'Kab. Barru' => '190600',
                    'Kab. Bone' => '190700',
                    'Kab. Bulukumba' => '191100',
                    'Kab. Enrekang' => '191600',
                    'Kab. Gowa' => '190300',
                    'Kab. Jeneponto' => '190500',
                    'Kab. Kepulauan Selayar' => '191300',
                    'Kab. Luwu' => '191700',
                    'Kab. Luwu Timur' => '192600',
                    'Kab. Luwu Utara' => '192400',
                    'Kab. Maros' => '190100',
                    'Kab. Pangkajene Kepulauan' => '190200',
                    'Kab. Pinrang' => '191400',
                    'Kab. Sidenreng Rappang' => '191500',
                    'Kab. Sinjai' => '191200',
                    'Kab. Soppeng' => '190900',
                    'Kab. Takalar' => '190400',
                    'Kab. Tana Toraja' => '191800',
                    'Kab. Toraja Utara' => '192700',
                    'Kab. Wajo' => '190800',
                    'Kota Makassar' => '196000',
                    'Kota Palopo' => '196200',
                    'Kota Parepare' => '196100',
                    'Kab. Bandung' => '20800',
                    'Kab. Bandung Barat' => '22300',
                    'Kab. Bekasi' => '22200',
                    'Kab. Bogor' => '20500',
                    'Kab. Ciamis' => '21400',
                    'Kab. Cianjur' => '20700',
                    'Kab. Cirebon' => '21700',
                    'Kab. Garut' => '21100',
                    'Kab. Indramayu' => '21800',
                    'Kab. Karawang' => '22100',
                    'Kab. Kuningan' => '21500',
                    'Kab. Majalengka' => '21600',
                    'Kab. Pangandaran' => '22500',
                    'Kab. Purwakarta' => '22000',
                    'Kab. Subang' => '21900',
                    'Kab. Sukabumi' => '20600',
                    'Kab. Sumedang' => '21000',
                    'Kab. Tasikmalaya' => '21200',
                    'Kota Bandung' => '26000',
                    'Kota Banjar' => '26900',
                    'Kota Bekasi' => '26500',
                    'Kota Bogor' => '26100',
                    'Kota Cimahi' => '26700',
                    'Kota Cirebon' => '26300',
                    'Kota Depok' => '26600',
                    'Kota Sukabumi' => '26200',
                    'Kota Tasikmalaya' => '26800',
                    'Kab. Bombana' => '200700',
                    'Kab. Buton' => '200300',
                    'Kab. Buton Selatan' => '201400',
                    'Kab. Buton Tengah' => '201600',
                    'Kab. Buton Utara' => '201000',
                    'Kab. Kolaka' => '200400',
                    'Kab. Kolaka Timur' => '201100',
                    'Kab. Kolaka Utara' => '200800',
                    'Kab. Konawe' => '200100',
                    'Kab. Konawe Kepulauan' => '201200',
                    'Kab. Konawe Selatan' => '200500',
                    'Kab. Konawe Utara' => '200900',
                    'Kab. Muna' => '200200',
                    'Kab. Muna Barat' => '201300',
                    'Kab. Wakatobi' => '200600',
                    'Kota Baubau' => '206100',
                    'Kota Kendari' => '206000',
                    'Kab. Buru' => '210300',
                    'Kab. Buru Selatan' => '210900',
                    'Kab. Kepulauan Aru' => '210700',
                    'Kab. Kepulauan Tanimbar' => '210400',
                    'Kab. Maluku Barat Daya' => '210800',
                    'Kab. Maluku Tengah' => '210100',
                    'Kab. Maluku Tenggara' => '210200',
                    'Kab. Seram Bagian Barat' => '210500',
                    'Kab. Seram Bagian Timur' => '210600',
                    'Kota Ambon' => '216000',
                    'Kota Tual' => '216100',
                    'Kab. Badung' => '220400',
                    'Kab. Bangli' => '220700',
                    'Kab. Buleleng' => '220100',
                    'Kab. Gianyar' => '220500',
                    'Kab. Jembrana' => '220200',
                    'Kab. Karang Asem' => '220800',
                    'Kab. Klungkung' => '220600',
                    'Kab. Tabanan' => '220300',
                    'Kota Denpasar' => '226000',
                    'Kab. Bima' => '230600',
                    'Kab. Dompu' => '230500',
                    'Kab. Lombok Barat' => '230100',
                    'Kab. Lombok Tengah' => '230200',
                    'Kab. Lombok Timur' => '230300',
                    'Kab. Lombok Utara' => '230800',
                    'Kab. Sumbawa' => '230400',
                    'Kab. Sumbawa Barat' => '230700',
                    'Kota Bima' => '236100',
                    'Kota Mataram' => '236000',
                    'Kab. Alor' => '240600',
                    'Kab. Belu' => '240500',
                    'Kab. Ende' => '240900',
                    'Kab. Flores Timur' => '240700',
                    'Kab. Kupang' => '240100',
                    'Kab. Lembata' => '241400',
                    'Kab. Malaka' => '242200',
                    'Kab. Manggarai' => '241100',
                    'Kab. Manggarai Barat' => '241600',
                    'Kab. Manggarai Timur' => '242000',
                    'Kab. Nagekeo' => '241700',
                    'Kab. Ngada' => '241000',
                    'Kab. Rote-Ndao' => '241500',
                    'Kab. Sabu Raijua' => '242100',
                    'Kab. Sikka' => '240800',
                    'Kab. Sumba Barat' => '241300',
                    'Kab. Sumba Barat Daya' => '241900',
                    'Kab. Sumba Tengah' => '241800',
                    'Kab. Sumba Timur' => '241200',
                    'Kab. Timor Tengah Selatan' => '240300',
                    'Kab. Timor Tengah Utara' => '240400',
                    'Kota Kupang' => '246000',
                    'Kab. Asmat' => '251500',
                    'Kab. Biak Numfor' => '250200',
                    'Kab. Boven Digoel' => '251300',
                    'Kab. Deiyai' => '253500',
                    'Kab. Dogiyai' => '253400',
                    'Kab. Intan Jaya' => '253600',
                    'Kab. Jayapura' => '250100',
                    'Kab. Jaya Wijaya' => '250800',
                    'Kab. Keerom' => '252000',
                    'Kab. Kepulauan Yapen' => '250300',
                    'Kab. Lanny Jaya' => '253000',
                    'Kab. Mappi' => '251400',
                    'Kab. Mamberamo Raya' => '252800',
                    'Kab. Mamberamo Tengah' => '253100',
                    'Kab. Merauke' => '250700',
                    'Kab. Mimika' => '251200',
                    'Kab. Nabire' => '250900',
                    'Kab. Nduga' => '252900',
                    'Kab. Paniai' => '251000',
                    'Kab. Pegunungan Bintang' => '251700',
                    'Kab. Puncak' => '253300',
                    'Kab. Puncak Jaya' => '251100',
                    'Kab. Sarmi' => '251900',
                    'Kab. Supiori' => '252700',
                    'Kab. Tolikara' => '251800',
                    'Kab. Waropen' => '252600',
                    'Kab. Yahukimo' => '251600',
                    'Kab. Yalimo' => '253200',
                    'Kota Jayapura' => '256000',
                    'Kab. Bengkulu Selatan' => '260300',
                    'Kab. Bengkulu Tengah' => '260900',
                    'Kab. Bengkulu Utara' => '260100',
                    'Kab. Kaur' => '260700',
                    'Kab. Kepahiang' => '260500',
                    'Kab. Lebong' => '260600',
                    'Kab. Muko-muko' => '260400',
                    'Kab. Rejang Lebong' => '260200',
                    'Kab. Seluma' => '260800',
                    'Kota Bengkulu' => '266000',
                    'Kab. Halmahera Barat' => '270300',
                    'Kab. Halmahera Selatan' => '270500',
                    'Kab. Halmahera Tengah' => '270200',
                    'Kab. Halmahera Timur' => '270600',
                    'Kab. Halmahera Utara' => '270400',
                    'Kab. Kepulauan Morotai' => '270800',
                    'Kab. Kepulauan Sula' => '270700',
                    'Kab. Pulau Taliabu' => '270100',
                    'Kota Ternate' => '276000',
                    'Kota Tidore Kepulauan' => '276100',
                    'Kab. Lebak' => '280200',
                    'Kab. Pandeglang' => '280100',
                    'Kab. Serang' => '280400',
                    'Kab. Tangerang' => '280300',
                    'Kota Cilegon' => '286000',
                    'Kota Serang' => '286200',
                    'Kota Tangerang' => '286100',
                    'Kota Tangerang Selatan' => '286300',
                    'Kab. Bangka' => '290100',
                    'Kab. Bangka Barat' => '290400',
                    'Kab. Bangka Selatan' => '290500',
                    'Kab. Bangka Tengah' => '290300',
                    'Kab. Belitung' => '290200',
                    'Kab. Belitung Timur' => '290600',
                    'Kota Pangkalpinang' => '296000',
                    'Kab. Banjarnegara' => '30400',
                    'Kab. Banyumas' => '30200',
                    'Kab. Batang' => '32500',
                    'Kab. Blora' => '31600',
                    'Kab. Boyolali' => '30900',
                    'Kab. Brebes' => '32900',
                    'Kab. Cilacap' => '30100',
                    'Kab. Demak' => '32100',
                    'Kab. Grobogan' => '31500',
                    'Kab. Jepara' => '32000',
                    'Kab. Karanganyar' => '31300',
                    'Kab. Kebumen' => '30500',
                    'Kab. Kendal' => '32400',
                    'Kab. Klaten' => '31000',
                    'Kab. Kudus' => '31900',
                    'Kab. Magelang' => '30800',
                    'Kab. Pati' => '31800',
                    'Kab. Pekalongan' => '32600',
                    'Kab. Pemalang' => '32700',
                    'Kab. Purbalingga' => '30300',
                    'Kab. Purworejo' => '30600',
                    'Kab. Rembang' => '31700',
                    'Kab. Semarang' => '32200',
                    'Kab. Sragen' => '31400',
                    'Kab. Sukoharjo' => '31100',
                    'Kab. Tegal' => '32800',
                    'Kab. Temanggung' => '32300',
                    'Kab. Wonogiri' => '31200',
                    'Kab. Wonosobo' => '30700',
                    'Kota Magelang' => '36000',
                    'Kota Pekalongan' => '36400',
                    'Kota Salatiga' => '36200',
                    'Kota Semarang' => '36300',
                    'Kota Surakarta' => '36100',
                    'Kota Tegal' => '36500',
                    'Kab. Boalemo' => '300100',
                    'Kab. Bone Bolango' => '300400',
                    'Kab. Gorontalo' => '300200',
                    'Kab. Gorontalo Utara' => '300500',
                    'Kab. Pohuwato' => '300300',
                    'Kota Gorontalo' => '306000',
                    'Kab. Bintan' => '310100',
                    'Kab. Karimun' => '310200',
                    'Kab. Kepulauan Anambas' => '310500',
                    'Kab. Lingga' => '310400',
                    'Kab. Natuna' => '310300',
                    'Kota Batam' => '316000',
                    'Kota Tanjungpinang' => '316100',
                    'Kab. Fak-Fak' => '320100',
                    'Kab. Kaimana' => '320200',
                    'Kab. Manokwari' => '320500',
                    'Kab. Manokwari Selatan' => '321200',
                    'Kab. Maybrat' => '321000',
                    'Kab. Pegunungan Arfak' => '321100',
                    'Kab. Raja Ampat' => '320800',
                    'Kab. Sorong' => '320700',
                    'Kab. Sorong Selatan' => '320600',
                    'Kab. Tambrauw' => '320900',
                    'Kab. Teluk Bintuni' => '320400',
                    'Kab. Teluk Wondama' => '320300',
                    'Kota Sorong' => '326000',
                    'Kab. Majene' => '330500',
                    'Kab. Mamasa' => '330400',
                    'Kab. Mamuju' => '330100',
                    'Kab. Mamuju Tengah' => '330600',
                    'Kab. Pasangkayu' => '330200',
                    'Kab. Polewali Mandar' => '330300',
                    'Kab. Bulungan' => '340200',
                    'Kab. Malinau' => '340100',
                    'Kab. Nunukan' => '340500',
                    'Kab. Tana Tidung' => '340300',
                    'Kota Tarakan' => '346000',
                    'Arab Saudi' => '350800',
                    'Belanda' => '350100',
                    'Brunei Darussalam' => '351500',
                    'Cina' => '351400',
                    'Filipina' => '350600',
                    'Japan' => '350200',
                    'Malaysia' => '350400',
                    'Mesir' => '350300',
                    'Myanmar' => '350500',
                    'Rusia' => '350700',
                    'Singapura' => '351000',
                    'Taiwan' => '351300',
                    'Thailand' => '351200',
                    'Kab. Bantul' => '40100',
                    'Kab. Gunung Kidul' => '40300',
                    'Kab. Kulon Progo' => '40400',
                    'Kab. Sleman' => '40200',
                    'Kota Yogyakarta' => '46000',
                    'Kab. Bangkalan' => '52900',
                    'Kab. Banyuwangi' => '52500',
                    'Kab. Blitar' => '51500',
                    'Kab. Bojonegoro' => '50500',
                    'Kab. Bondowoso' => '52200',
                    'Kab. Gresik' => '50100',
                    'Kab. Jember' => '52400',
                    'Kab. Jombang' => '50400',
                    'Kab. Kediri' => '51300',
                    'Kab. Lamongan' => '50700',
                    'Kab. Lumajang' => '52100',
                    'Kab. Madiun' => '50800',
                    'Kab. Magetan' => '51000',
                    'Kab. Malang' => '51800',
                    'Kab. Mojokerto' => '50300',
                    'Kab. Nganjuk' => '51400',
                    'Kab. Ngawi' => '50900',
                    'Kab. Pacitan' => '51200',
                    'Kab. Pamekasan' => '52600',
                    'Kab. Pasuruan' => '51900',
                    'Kab. Ponorogo' => '51100',
                    'Kab. Probolinggo' => '52000',
                    'Kab. Sampang' => '52700',
                    'Kab. Sidoarjo' => '50200',
                    'Kab. Situbondo' => '52300',
                    'Kab. Sumenep' => '52800',
                    'Kab. Trenggalek' => '51700',
                    'Kab. Tuban' => '50600',
                    'Kab. Tulungagung' => '51600',
                    'Kota Batu' => '56800',
                    'Kota Blitar' => '56500',
                    'Kota Kediri' => '56300',
                    'Kota Madiun' => '56200',
                    'Kota Malang' => '56100',
                    'Kota Mojokerto' => '56400',
                    'Kota Pasuruan' => '56600',
                    'Kota Probolinggo' => '56700',
                    'Kota Surabaya' => '56000',
                    'Kab. Aceh Barat' => '60600',
                    'Kab. Aceh Barat Daya' => '61700',
                    'Kab. Aceh Besar' => '60100',
                    'Kab. Aceh Jaya' => '61600',
                    'Kab. Aceh Selatan' => '60700',
                    'Kab. Aceh Singkil' => '61300',
                    'Kab. Aceh Tamiang' => '61400',
                    'Kab. Aceh Tengah' => '60500',
                    'Kab. Aceh Tenggara' => '60800',
                    'Kab. Aceh Timur' => '60400',
                    'Kab. Aceh Utara' => '60300',
                    'Kab. Bener Meriah' => '61900',
                    'Kab. Bireuen' => '61200',
                    'Kab. Gayo Lues' => '61800',
                    'Kab. Nagan Raya' => '61500',
                    'Kab. Pidie' => '60200',
                    'Kab. Pidie Jaya' => '62000',
                    'Kab. Simeulue' => '61100',
                    'Kota Banda Aceh' => '66100',
                    'Kota Langsa' => '66300',
                    'Kota Lhokseumawe' => '66200',
                    'Kota Sabang' => '66000',
                    'Kota Subulussalam' => '66400',
                    'Kab. Asahan' => '70600',
                    'Kab. Batu Bara' => '72200',
                    'Kab. Dairi' => '70500',
                    'Kab. Deli Serdang' => '70100',
                    'Kab. Humbang Hasundutan' => '71900',
                    'Kab. Karo' => '70300',
                    'Kab. Labuhanbatu' => '70700',
                    'Kab. Labuhanbatu Selatan' => '72600',
                    'Kab. Labuhanbatu Utara' => '72500',
                    'Kab. Langkat' => '70200',
                    'Kab. Mandailing Natal' => '71500',
                    'Kab. Nias' => '71100',
                    'Kab. Nias Barat' => '72700',
                    'Kab. Nias Selatan' => '71700',
                    'Kab. Nias Utara' => '72800',
                    'Kab. Padang Lawas' => '72400',
                    'Kab. Padang Lawas Utara' => '72300',
                    'Kab. Pakpak Bharat' => '71800',
                    'Kab. Samosir' => '72000',
                    'Kab. Serdang Bedagai' => '72100',
                    'Kab. Simalungun' => '70400',
                    'Kab. Tapanuli Selatan' => '71000',
                    'Kab. Tapanuli Tengah' => '70900',
                    'Kab. Tapanuli Utara' => '70800',
                    'Kab. Toba Samosir' => '71600',
                    'Kota Binjai' => '76100',
                    'Kota Gunungsitoli' => '76700',
                    'Kota Medan' => '76000',
                    'Kota Padangsidimpuan' => '76600',
                    'Kota Pematangsiantar' => '76300',
                    'Kota Sibolga' => '76500',
                    'Kota Tanjungbalai' => '76400',
                    'Kota Tebing Tinggi' => '76200',
                    'Kab. Agam' => '80100',
                    'Kab. Dharmasraya' => '81200',
                    'Kab. Kepulauan Mentawai' => '81000',
                    'Kab. Lima Puluh Kota' => '80300',
                    'Kab. Padang Pariaman' => '80500',
                    'Kab. Pasaman' => '80200',
                    'Kab. Pasaman Barat' => '81300',
                    'Kab. Pesisir Selatan' => '80600',
                    'Kab. Sijunjung' => '80800',
                    'Kab. Solok' => '80400',
                    'Kab. Solok Selatan' => '81100',
                    'Kab. Tanah Datar' => '80700',
                    'Kota Bukittinggi' => '86000',
                    'Kota Padang' => '86100',
                    'Kota Padang Panjang' => '86200',
                    'Kota Pariaman' => '86600',
                    'Kota Payakumbuh' => '86500',
                    'Kota Sawahlunto' => '86300',
                    'Kota Solok' => '86400',
                    'Kab. Bengkalis' => '90200',
                    'Kab. Indragiri Hilir' => '90500',
                    'Kab. Indragiri Hulu' => '90400',
                    'Kab. Kampar' => '90100',
                    'Kab. Kepulauan Meranti' => '91500',
                    'Kab. Kuantan Singingi' => '91400',
                    'Kab. Pelalawan' => '90800',
                    'Kab. Rokan Hilir' => '91000',
                    'Kab. Rokan Hulu' => '90900',
                    'Kab. Siak' => '91100',
                    'Kota Dumai' => '96200',
                    'Kota Pekanbaru' => '96000',
                    default => '0'
                },
                'f11_jenis_instansi' => match ($request->f11_jenis_instansi) {
                        '1'=> '1',
                        '2'=> '6',
                        '3'=> '7',
                        '4'=> '2',
                        '5'=> '3',
                        '6'=> '4',
                        '7'=> '5',
                        default => $request->f11_jenis_instansi,
                    },
                'f11_jenis_instansi_lainnya' => $request->f11_02,
                'f5b_nama_perusahaan' => $request->f5b_nama_perusahaan,
                'f5c_posisi_wiraswasta' => $request->f5c_posisi,
                'f5d_tingkat_tempat_kerja' => $request->f5d_tingkat,
                'f12_sumber_biaya_kuliah' => match ($request->f12_01) {
                        '1'=> '1',
                        '2'=> '2',
                        '3'=> '3',
                        '4'=> '4',
                        '5'=> '5',
                        '6'=> '6',
                        '7'=> '7',
                        default => $request->f12_01,
                    },
                'f18a_sumber_biaya_studi'      => $request->f18a_sumber_biaya_studi,
                'f18b_perguruan_tinggi_studi'  => $request->f18b_perguruan_tinggi_studi,
                'f18c_program_studi'           => $request->f18c_program_studi,
                'f18d_tanggal_masuk'           => $request->f18d_tanggal_masuk,
                'f12_sumber_biaya_kuliah_lainnya' => $request->f12_02,
                'f14_erat_hubungan_studi' => match ($request->f14) {
                        '1'=> '1',
                        '2'=> '2',
                        '3'=> '3',
                        '4'=> '4',
                        '5'=> '5',
                        default => $request->f14,
                },
                'f15_tingkat_paling_tepat' => match ($request->f15) {
                        '1'=> '1',
                        '2'=> '2',
                        '3'=> '3',
                        '4'=> '4',
                        default => $request->f15,
                },
                'f1701_A' => match($request->f1761_f1762_A ?? $request->comp_a_f1761_f1762) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1702_A' => match($request->f1763_f1764_A ?? $request->comp_a_f1763_f1764) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1703_A' => match($request->f1765_f1766_A ?? $request->comp_a_f1765_f1766) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1704_A' => match($request->f1767_f1768_A ?? $request->comp_a_f1767_f1768) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1705_A' => match($request->f1769_f1770_A ?? $request->comp_a_f1769_f1770) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1706_A' => match($request->f1771_f1772_A ?? $request->comp_a_f1771_f1772) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1707_A' => match($request->f1773_f1774_A ?? $request->comp_a_f1773_f1774) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },

                'f1701_B' => match($request->f1761_f1762_B ?? $request->comp_b_f1761_f1762) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1702_B' => match($request->f1763_f1764_B ?? $request->comp_b_f1763_f1764) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5' 
                },
                'f1703_B' => match($request->f1765_f1766_B ?? $request->comp_b_f1765_f1766) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1704_B' => match($request->f1767_f1768_B ?? $request->comp_b_f1767_f1768) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1705_B' => match($request->f1769_f1770_B ?? $request->comp_b_f1769_f1770) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1706_B' => match($request->f1771_f1772_B ?? $request->comp_b_f1771_f1772) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },
                'f1707_B' => match($request->f1773_f1774_B ?? $request->comp_b_f1773_f1774) {
                    '1' => '1', 
                    '2' => '2', 
                    '3' => '3', 
                    '4' => '4', 
                    '5' => '5', 
                    default => '5'
                },

                'created_at' => now(),
                'updated_at' => now(),

                'f21_perkuliahan' => match ($request->f21 ?? $request->f21_perkuliahan) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => '3'
                },
                'f22_demonstrasi' => match ($request->f22 ?? $request->f22_demonstrasi) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => '3'
                },
                'f23_riset' => match ($request->f22 ?? $request->f23_riset) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => '3'
                },
                'f24_magang' => match ($request->f24 ?? $request->f24_magang) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => '3'
                },
                'f25_praktikum' => match ($request->f25 ?? $request->f25_praktikum) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => '3'
                },
                'f26_kerja_lapangan' => match ($request->f26 ?? $request->f26_kerja_lapangan) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => '3'
                },
                'f27_diskusi' => match ($request->f27 ?? $request->f27_diskusi) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    '4'=> '4',
                    '5'=> '5',
                    default => '3'
                },
                'f301_kapan_mencari_pekerjaan' => match ($request->f301) {
                    '1'=> '1',
                    '2'=> '2',
                    '3'=> '3',
                    default => '3'
                },
                'f302_bulan_sebelum_lulus' => $request->f302,
                'f303_bulan_setelah_lulus' => $request->f303,

                'f401_iklan_koran_brosur'           => $request->has('f401') ? '1' : '0',
                'f402_melamar_tanpa_lowongan'       => $request->has('f402') ? '1' : '0',
                'f403_bursa_pameran_online'         => $request->has('f403') ? '1' : '0',
                'f404_internet_iklan_online'        => $request->has('f404') ? '1' : '0',
                'f405_dihubungi_perusahaan'         => $request->has('f405') ? '1' : '0',
                'f406_menghubungi_kemenakertrans'   => $request->has('f406') ? '1' : '0',
                'f407_agen_tenaga_kerja'            => $request->has('f407') ? '1' : '0',
                'f408_karir_fakultas_universitas'   => $request->has('f408') ? '1' : '0',
                'f409_kantor_kemanusiaan_alumni'    => $request->has('f409') ? '1' : '0',
                'f410_membangun_jejaring_kuliah'    => $request->has('f410') ? '1' : '0',
                'f411_melalui_relasi'               => $request->has('f411') ? '1' : '0',
                'f412_membangun_bisnis_sendiri'     => $request->has('f412') ? '1' : '0',
                'f413_penempatan_kerja_magang'      => $request->has('f413') ? '1' : '0',
                'f414_tempat_kerja_sama_kuliah'     => $request->has('f414') ? '1' : '0',
                'f415_lainnya'                      => $request->has('f415') ? '1' : '0',
                'f416_tuliskan'                      => $request->f416_tuliskan ?? null,

                'f6_perusahaan_dilamar' => $request->f6_jumlah_lamaran,
                'f7_perusahaan_merespon' => $request->f7_jumlah_respons,
                'f7a_mengundang_wawancara' => $request->f17a_jumlah_wawancara,
                'f10_aktif_mencari_kerja' => match ($request->f10_aktif) {
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    default => '1',
                },
                'f10_lainnya' => $request->f10_lainnya ?? null,

                'f1601_pertanyaan_tidak_sesuai'     => $request->has('f1601') ? '1' : '0',
                'f1602_belum_dapat_kerja_sesuai'    => $request->has('f1602') ? '1' : '0',
                'f1603_prospek_karir_baik'          => $request->has('f1603') ? '1' : '0',
                'f1604_suka_area_kerja_tersebut'    => $request->has('f1604') ? '1' : '0',
                'f1605_dipromosikan_posisi_lain'    => $request->has('f1605') ? '1' : '0',
                'f1606_pendapatan_lebih_tinggi'     => $request->has('f1606') ? '1' : '0',
                'f1607_pekerjaan_lebih_aman'        => $request->has('f1607') ? '1' : '0',
                'f1608_pekerjaan_lebih_menarik'     => $request->has('f1608') ? '1' : '0',
                'f1609_mungkinkan_kerja_tambahan'   => $request->has('f1609') ? '1' : '0',
                'f1610_lokasi_dekat_rumah'          => $request->has('f1610') ? '1' : '0',
                'f1611_menjamin_kebutuhan_keluarga' => $request->has('f1611') ? '1' : '0',
                'f1612_awal_menitip_karir'          => $request->has('f1612') ? '1' : '0',
                'f1613_lainnya'                       => $request->has('f1613') ? '1' : '0',
                'f1614_tuliskan'                    => $request->input('f1614'),
            ]);

        return redirect()->back()->with('success', 'Kuesioner Anda berhasil dikirim! Terima kasih atas partisipasinya.');
        }

        public function dashboard(Request $request)
        {
            // 1. Ambil data unik Tahun Lulus & Prodi untuk isi pilihan Dropdown Filter
            $listTahun = DB::table('kuesioner_alumnis')->distinct()->pluck('tahun_lulus')->sort()->values();
            
            $prodiLabels = [
                '54211' => 'Agroteknologi', '62201' => 'Akuntansi', '74201' => 'Ilmu Hukum',
                '61201' => 'Manajemen', '88201' => 'Pend. Bahasa Indonesia', '88203' => 'Pend. Bahasa Inggris',
                '84205' => 'Pend. Biologi', '87203' => 'Pend. Ekonomi', '54231' => 'Peternakan',
                '84202' => 'Pend. Matematika', '57401' => 'Manajemen Informatika', '54201' => 'Agribisnis'
            ];

            // 2. Tangkap filter dari Request Admin
            $tahunTerpilih = $request->input('tahun_lulus');
            $prodiTerpilih = $request->input('kode_prodi');

            // Buat query dasar pencarian data kuesioner
            $query = DB::table('kuesioner_alumnis');

            // Jika filter diisi oleh admin, saring datanya
            if ($tahunTerpilih) {
                $query->where('tahun_lulus', $tahunTerpilih);
            }
            if ($prodiTerpilih) {
                $query->where('kode_prodi', $prodiTerpilih);
            }

            $dataAlumni = $query->get();
            $totalAlumni = $dataAlumni->count();

            // --- PROSES DATA GRAFIK (HANYA DIHITUNG JIKA TOTAL DATA > 0) ---
            
            // A. Status Bekerja (f8_status_saat_ini)
            $statusKerja = ['Bekerja' => 0, 'Wiraswasta' => 0, 'Lanjut Kuliah' => 0, 'Cari Kerja' => 0, 'Belum Bekerja' => 0];

            foreach ($dataAlumni as $d) {
                if (isset($d->f8_status_saat_ini)) {
                    // Ubah string ke huruf kecil sekali saja agar proses pengecekan lebih ringan
                    $status_val = strtolower($d->f8_status_saat_ini);

                    // 1. Jika nilainya 1, berarti Bekerja
                    if ($status_val === '1') {
                        $statusKerja['Bekerja']++;
                    } 
                    // 2. Jika nilainya 3, berarti Wiraswasta
                    elseif ($status_val === '3') {
                        $statusKerja['Wiraswasta']++;
                    } 
                    // 3. Jika nilainya 4, berarti Melanjutkan Pendidikan/Studi
                    elseif ($status_val === '4') {
                        $statusKerja['Lanjut Kuliah']++;
                    }
                    // 4. Jika nilainya 5, berarti Mencari Kerja
                    elseif ($status_val === '5') {
                        $statusKerja['Cari Kerja']++;
                    }
                    // 5. Jika nilainya 2, berarti Belum Bekerja
                    elseif ($status_val === '2') {
                        $statusKerja['Belum Bekerja']++;
                    }
                    // 6. Opsi cadangan jika ada data kosong atau tidak cocok dengan kriteria
                    else {
                        $statusKerja['Cari Kerja']++;
                    }
                }
            }

            // B. Pendapatan Per Bulan (f505_pendapatan_per_bulan)
            $pendapatan = ['< 2 Juta' => 0, '2 - 5 Juta' => 0, '> 5 Juta' => 0];
            foreach ($dataAlumni as $d) {
                $val = (int)($d->f505_pendapatan_per_bulan ?? 0);
                if ($val > 0 && $val < 2000000) $pendapatan['< 2 Juta']++;
                elseif ($val >= 2000000 && $val <= 5000000) $pendapatan['2 - 5 Juta']++;
                elseif ($val > 5000000) $pendapatan['> 5 Juta']++;
            }

            $statusPerusahaanKerja = ['Instansi Pemerintah' => 0, 'BUMN/BUMD' => 0, 'Institusi' => 0, 'Lembaga Swadaya' => 0, 'Swasta' => 0, 'Wiraswasta' => 0, 'Lainnya' => 0];

            foreach ($dataAlumni as $d) {
                if (isset($d->f11_jenis_instansi)) {
                    // Ubah string ke huruf kecil sekali saja agar proses pengecekan lebih ringan
                    $status_val = strtolower($d->f11_jenis_instansi);

                    // 1. Jika nilainya 1, berarti Bekerja
                    if ($status_val === '1') {
                        $statusPerusahaanKerja['Instansi Pemerintah']++;
                    } 
                    // 2. Jika nilainya 3, berarti Wiraswasta
                    elseif ($status_val === '6') {
                        $statusPerusahaanKerja['BUMN/BUMD']++;
                    } 
                    // 3. Jika nilainya 4, berarti Melanjutkan Pendidikan/Studi
                    elseif ($status_val === '7') {
                        $statusPerusahaanKerja['Institusi']++;
                    }
                    // 4. Jika nilainya 5, berarti Mencari Kerja
                    elseif ($status_val === '2') {
                        $statusPerusahaanKerja['Lembaga Swadaya']++;
                    }
                    // 5. Jika nilainya 2, berarti Belum Bekerja
                    elseif ($status_val === '3') {
                        $statusPerusahaanKerja['Swasta']++;
                    }
                    elseif ($status_val === '4') {
                        $statusPerusahaanKerja['Wiraswasta']++;
                    }
                    elseif ($status_val === '5') {
                        $statusPerusahaanKerja['Lainnya']++;
                    } 
                }
            }

            $SumberDana = ['Biaya Sendiri' => 0, 'Beasiswa ADIK' => 0, 'Beasiswa BIDIKMISI' => 0, 'Beasiswa PPA' => 0, 'Beasiswa AFIRMASI' => 0, 'Beasiswa Swasta' => 0, 'Lainnya' => 0];

            foreach ($dataAlumni as $d) {
                if (isset($d->f12_sumber_biaya_kuliah)) {
                    // Ubah string ke huruf kecil sekali saja agar proses pengecekan lebih ringan
                    $status_val = strtolower($d->f12_sumber_biaya_kuliah);

                    // 1. Jika nilainya 1, berarti Bekerja
                    if ($status_val === '1') {
                        $SumberDana['Biaya Sendiri']++;
                    }elseif ($status_val === '2') {
                        $SumberDana['Beasiswa ADIK']++;
                    }elseif ($status_val === '3') {
                        $SumberDana['Beasiswa BIDIKMISI']++;
                    }elseif ($status_val === '4') {
                        $SumberDana['Beasiswa PPA']++;
                    }elseif ($status_val === '5') {
                        $SumberDana['Beasiswa AFIRMASI']++;
                    }elseif ($status_val === '6') {
                        $SumberDana['Beasiswa Swasta']++;
                    }elseif ($status_val === '7') {
                        $SumberDana['Lainnya']++;
                    }else {
                        $SumberDana['Lainnya']++;
                    }
                }
            }

            $PosisiJabatan = [];
            foreach ($dataAlumni as $d) {
                if (!empty($d->f5c_posisi_wiraswasta) && $d->f5c_posisi_wiraswasta != '?') {
                    $PosisiJabatan[$d->f5c_posisi_wiraswasta] = ($PosisiJabatan[$d->f5c_posisi_wiraswasta] ?? 0) + 1;
                }
            }

            $PilihTingkat = [];
            foreach ($dataAlumni as $d) {
                if (!empty($d->f5d_tingkat_tempat_kerja) && $d->f5d_tingkat_tempat_kerja != '?') {
                    $PilihTingkat[$d->f5d_tingkat_tempat_kerja] = ($PilihTingkat[$d->f5d_tingkat_tempat_kerja] ?? 0) + 1;
                }
            }

            // C. Lokasi Kerja (f510_provinsi)
            $lokasiKerja = [];
            foreach ($dataAlumni as $d) {
                if (!empty($d->f510_provinsi) && $d->f510_provinsi != '?') {
                    $lokasiKerja[$d->f510_provinsi] = ($lokasiKerja[$d->f510_provinsi] ?? 0) + 1;
                }
            }

            $lokasiKota = [];
            foreach ($dataAlumni as $d) {
                if (!empty($d->f510_kab_kota) && $d->f510_kab_kota != '?') {
                    $lokasiKota[$d->f510_kab_kota] = ($lokasiKota[$d->f510_kab_kota] ?? 0) + 1;
                }
            }
            // D. Tempat Kuliah Lanjut (f18b_perguruan_tinggi_studi)
            $tempatKuliah = [];
            foreach ($dataAlumni as $d) {
                if (!empty($d->f18b_perguruan_tinggi_studi) && $d->f18b_perguruan_tinggi_studi != '?') {
                    $tempatKuliah[$d->f18b_perguruan_tinggi_studi] = ($tempatKuliah[$d->f18b_perguruan_tinggi_studi] ?? 0) + 1;
                }
            }

            // E. Kompetensi Dikuasai vs Diperlukan (f1701_A sampai f1707_B)
            $kompetensiDikuasai = [0, 0, 0, 0, 0, 0, 0]; // f1701_A sd f1707_A
            $kompetensiDiperlukan = [0, 0, 0, 0, 0, 0, 0]; // f1701_B sd f1707_B

            if ($totalAlumni > 0) {
                foreach ($dataAlumni as $d) {
                    for ($i = 1; $i <= 7; $i++) {
                        $colA = "f170{$i}_A"; 
                        $colB = "f170{$i}_B";
                        
                        // Langsung ambil angka dari database, jika kosong beri nilai default 0 atau 3
                        $kompetensiDikuasai[$i-1] += isset($d->$colA) ? (int)$d->$colA : 3;
                        $kompetensiDiperlukan[$i-1] += isset($d->$colB) ? (int)$d->$colB : 3;
                    }
                }
                
                // Jadikan rata-rata
                for ($i = 0; $i < 7; $i++) {
                    $kompetensiDikuasai[$i] = round($kompetensiDikuasai[$i] / $totalAlumni, 2);
                    $kompetensiDiperlukan[$i] = round($kompetensiDiperlukan[$i] / $totalAlumni, 2);
                }
            }

            // F. Penekanan Metode Pembelajaran
            $metodeSangatBesar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeBesar       = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeCukupBesar  = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeKurang      = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            $metodeTidakSama   = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
            foreach ($dataAlumni as $d) {
                if (($d->f21_perkuliahan ?? '') == '1') $metodeSangatBesar['Perkuliahan']++;
                if (($d->f22_demonstrasi ?? '') == '1') $metodeSangatBesar['Demonstrasi']++;
                if (($d->f23_riset ?? '') == '1')       $metodeSangatBesar['Riset']++;
                if (($d->f24_magang ?? '') == '1')      $metodeSangatBesar['Magang']++;
                if (($d->f25_praktikum ?? '') == '1')   $metodeSangatBesar['Praktikum']++;
                if (($d->f26_kerja_lapangan ?? '') == '1') $metodeSangatBesar['Kerja Lapangan']++;
                if (($d->f27_diskusi ?? '') == '1')      $metodeSangatBesar['Diskusi']++;

                if (($d->f21_perkuliahan ?? '') == '2') $metodeBesar['Perkuliahan']++;
                if (($d->f22_demonstrasi ?? '') == '2') $metodeBesar['Demonstrasi']++;
                if (($d->f23_riset ?? '') == '2')       $metodeBesar['Riset']++;
                if (($d->f24_magang ?? '') == '2')      $metodeBesar['Magang']++;
                if (($d->f25_praktikum ?? '') == '2')   $metodeBesar['Praktikum']++;
                if (($d->f26_kerja_lapangan ?? '') == '2') $metodeBesar['Kerja Lapangan']++;
                if (($d->f27_diskusi ?? '') == '2')      $metodeBesar['Diskusi']++;

                if (($d->f21_perkuliahan ?? '') == '3') $metodeCukupBesar['Perkuliahan']++;
                if (($d->f22_demonstrasi ?? '') == '3') $metodeCukupBesar['Demonstrasi']++;
                if (($d->f23_riset ?? '') == '3')       $metodeCukupBesar['Riset']++;
                if (($d->f24_magang ?? '') == '3')      $metodeCukupBesar['Magang']++;
                if (($d->f25_praktikum ?? '') == '3')   $metodeCukupBesar['Praktikum']++;
                if (($d->f26_kerja_lapangan ?? '') == '3') $metodeCukupBesar['Kerja Lapangan']++;
                if (($d->f27_diskusi ?? '') == '3')      $metodeCukupBesar['Diskusi']++;

                if (($d->f21_perkuliahan ?? '') == '4') $metodeKurang['Perkuliahan']++;
                if (($d->f22_demonstrasi ?? '') == '4') $metodeKurang['Demonstrasi']++;
                if (($d->f23_riset ?? '') == '4')       $metodeKurang['Riset']++;
                if (($d->f24_magang ?? '') == '4')      $metodeKurang['Magang']++;
                if (($d->f25_praktikum ?? '') == '4')   $metodeKurang['Praktikum']++;
                if (($d->f26_kerja_lapangan ?? '') == '4') $metodeKurang['Kerja Lapangan']++;
                if (($d->f27_diskusi ?? '') == '4')      $metodeKurang['Diskusi']++;

                if (($d->f21_perkuliahan ?? '') == '5') $metodeTidakSama['Perkuliahan']++;
                if (($d->f22_demonstrasi ?? '') == '5') $metodeTidakSama['Demonstrasi']++;
                if (($d->f23_riset ?? '') == '5')       $metodeTidakSama['Riset']++;
                if (($d->f24_magang ?? '') == '5')      $metodeTidakSama['Magang']++;
                if (($d->f25_praktikum ?? '') == '5')   $metodeTidakSama['Praktikum']++;
                if (($d->f26_kerja_lapangan ?? '') == '5') $metodeTidakSama['Kerja Lapangan']++;
                if (($d->f27_diskusi ?? '') == '5')      $metodeTidakSama['Diskusi']++;
            }

            // G. Waktu Mencari Kerja (f302 / f303 dlm hitungan 1-12 bulan)
            $waktuCariKerja = ['1-3 Bulan' => 0, '4-6 Bulan' => 0, '7-12 Bulan' => 0, '> 12 Bulan' => 0];
            foreach ($dataAlumni as $d) {
                $bulan = (int)($d->f302_bulan_sebelum_lulus ?? $d->f303_bulan_setelah_lulus ?? 0);
                if ($bulan >= 1 && $bulan <= 3) $waktuCariKerja['1-3 Bulan']++;
                elseif ($bulan >= 4 && $bulan <= 6) $waktuCariKerja['4-6 Bulan']++;
                elseif ($bulan >= 7 && $bulan <= 12) $waktuCariKerja['7-12 Bulan']++;
                elseif ($bulan > 12) $waktuCariKerja['> 12 Bulan']++;
            }

            // H. Bagaimana Mencari Pekerjaan (Metode Terbanyak)
            $caraCariKerja = [
                'Iklan Koran' => 0,
                'Melamar Langsung' => 0,
                'Bursa Kerja' => 0,
                'Internet' => 0, 
                'Dihubungi Perusahaan' => 0, 
                'Kemenakertrans' => 0, 
                'Agen' => 0, 
                'CDC Kampus' => 0, 
                'Kantor Kemanusiaan'=> 0,
                'Kuliah'=> 0,
                'Relasi' => 0,
                'Bisnis Sendiri'=> 0,
                'Tempat Magang'=> 0,
                'Kerja Saat Kuliah'=> 0,
                'Lainnya'=> 0
            ];
            foreach ($dataAlumni as $d) {
                if (($d->f401_iklan_koran_brosur ?? '') == '1') $caraCariKerja['Iklan Koran']++;
                if (($d->f402_melamar_tanpa_lowongan ?? '') == '1') $caraCariKerja['Melamar Langsung']++;
                if (($d->f403_bursa_pameran_online ?? '') == '1') $caraCariKerja['Bursa Kerja']++;
                if (($d->f404_internet_iklan_online ?? '') == '1') $caraCariKerja['Internet']++;
                if (($d->f405_dihubungi_perusahaan ?? '') == '1') $caraCariKerja['Dihubungi Perusahaan']++;
                if (($d->f406_menghubungi_kemenakertrans ?? '') == '1') $caraCariKerja['Kemenakertrans']++;
                if (($d->f407_agen_tenaga_kerja ?? '') == '1') $caraCariKerja['Agen']++;
                if (($d->f408_karir_fakultas_universitas ?? '') == '1') $caraCariKerja['CDC Kampus']++;
                if (($d->f409_kantor_kemanusiaan_alumni ?? '') == '1') $caraCariKerja['Kantor Kemanusiaan']++;
                if (($d->f410_membangun_jejaring_kuliah ?? '') == '1') $caraCariKerja['Kuliah']++;
                if (($d->f411_melalui_relasi ?? '') == '1') $caraCariKerja['Relasi']++;
                if (($d->f412_membangun_bisnis_sendiri ?? '') == '1') $caraCariKerja['Bisnis Sendiri']++;
                if (($d->f413_penempatan_kerja_magang ?? '') == '1') $caraCariKerja['Tempat Magang']++;
                if (($d->f414_tempat_kerja_sama_kuliah ?? '') == '1') $caraCariKerja['Kerja Saat Kuliah']++;
                if (($d->f415_lainnya ?? '') == '1') $caraCariKerja['Lainnya']++;
            }

            // I. Aktivitas Lamaran (Dilamar, Merespon, Wawancara)
            $avgLamaran = [
                'Dilamar' => round($dataAlumni->avg('f6_perusahaan_dilamar') ?? 0, 1),
                'Merespon' => round($dataAlumni->avg('f7_perusahaan_merespon') ?? 0, 1),
                'Wawancara' => round($dataAlumni->avg('f7a_mengundang_wawancara') ?? 0, 1)
            ];

            // J. Keaktifan Mencari Kerja (f10_aktif_mencari_kerja)
            $keaktifan = ['Aktif' => 0, 'Tidak Aktif' => 0];
            foreach ($dataAlumni as $d) {
                if (isset($d->f10_aktif_mencari_kerja)) {
                    // PERBAIKAN: Gunakan (string) untuk casting tipe data yang benar di PHP
                    $jawaban_val = (string) trim($d->f10_aktif_mencari_kerja);

                    // Jika nilainya 3 atau 4, masukkan ke kelompok Aktif
                    if ($jawaban_val === '3' || $jawaban_val === '4') { 
                        $keaktifan['Aktif']++;
                    } 
                    // Jika nilainya 1, 2, 5 masuk ke Tidak Aktif
                    else {
                        $keaktifan['Tidak Aktif']++;
                    }
                } else {
                    $keaktifan['Tidak Aktif']++;
                }
            }

            // K. Alasan Pekerjaan Tidak Sesuai (f1601 - f1612)
            $alasanTidakSesuai = [
                'Pekerjaan Sesuai Pendidikan' => 0,
                'Belum Dapat Yang Sesuai' => 0,
                'Prospek Karir' => 0, 
                'Suka Bidang Ini' => 0,
                'Promosi Kurang Tepat' => 0,
                'Gaji Lebih Tinggi' => 0,
                'Pekerjaan Lebih Aman' => 0,
                'Pekerjaan Lebih Menarik'=> 0,
                'Bisa Tambah Kerja'=> 0,
                'Lokasi Dekat' => 0,
                'Menjamin kebutuhan Keluarga' => 0,
                'Awal Karir' => 0,
                'Lainnya'=> 0];
            foreach ($dataAlumni as $d) {
                if (($d->f1601_pertanyaan_tidak_sesuai ?? '') == '1') $alasanTidakSesuai['Pekerjaan Sesuai Pendidikan']++;
                if (($d->f1602_belum_dapat_kerja_sesuai ?? '') == '1') $alasanTidakSesuai['Belum Dapat Yang Sesuai']++;
                if (($d->f1603_prospek_karir_baik ?? '') == '1') $alasanTidakSesuai['Prospek Karir']++;
                if (($d->f1604_suka_area_kerja_tersebut ?? '') == '1') $alasanTidakSesuai['Suka Bidang Ini']++;
                if (($d->f1605_dipromosikan_posisi_lain ?? '') == '1') $alasanTidakSesuai['Promosi Kurang Tepat']++;
                if (($d->f1606_pendapatan_lebih_tinggi ?? '') == '1') $alasanTidakSesuai['Gaji Lebih Tinggi']++;
                if (($d->f1607_pekerjaan_lebih_aman ?? '') == '1') $alasanTidakSesuai['Pekerjaan Lebih Aman']++;
                if (($d->f1608_pekerjaan_lebih_menarik ?? '') == '1') $alasanTidakSesuai['Pekerjaan Lebih Menarik']++;
                if (($d->f1609_mungkinkan_kerja_tambahan ?? '') == '1') $alasanTidakSesuai['Bisa Tambah Kerja']++;
                if (($d->f1610_lokasi_dekat_rumah ?? '') == '1') $alasanTidakSesuai['Lokasi Dekat']++;
                if (($d->f1611_menjamin_kebutuhan_keluarga ?? '') == '1') $alasanTidakSesuai['Menjamin kebutuhan Keluarga']++;
                if (($d->f1612_awal_menitip_karir ?? '') == '1') $alasanTidakSesuai['Awal Karir']++;
                if (($d->f1613_lainnya ?? '') == '1') $alasanTidakSesuai['Lainnya']++;
            }

            return view('dashboard_kurva', compact(
                'listTahun', 'prodiLabels', 'tahunTerpilih', 'prodiTerpilih', 'totalAlumni',
                'statusKerja', 'statusPerusahaanKerja', 'SumberDana', 'pendapatan', 'PilihTingkat', 'PosisiJabatan', 'lokasiKerja', 'lokasiKota', 'tempatKuliah', 'kompetensiDikuasai', 
                'kompetensiDiperlukan', 'waktuCariKerja', 'caraCariKerja', 'avgLamaran', 'keaktifan', 'alasanTidakSesuai',
                'metodeSangatBesar', 'metodeBesar', 'metodeCukupBesar', 'metodeKurang', 'metodeTidakSama'
            ));
    }
    
    public function exportExcel(Request $request) {
        
        $tahunLulus = $request->input('tahun_lulus');
        $kodeProdi  = $request->input('kode_prodi');
        // 1. Ambil data alumni dengan pengurutan otomatis (Tahun -> Prodi -> Nama Abjad A-Z)
        $dataAlumni = DB::table('kuesioner_alumnis') 
        ->when($tahunLulus, function ($query, $tahunLulus) {
            return $query->where('tahun_lulus', $tahunLulus);
        })
        ->when($kodeProdi, function ($query, $kodeProdi) {
            return $query->where('kode_prodi', $kodeProdi);
        })
            ->orderBy('tahun_lulus', 'asc')    // Sesuai kolom tahun kelulusan
            ->orderBy('kode_prodi', 'asc')     // Sesuai kolom kode prodi
            ->orderBy('Nama', 'asc')           // <-- DIUBAH KE 'Nama' (Sesuai Gambar Tabel Anda)
            ->get();

        // 2. Logika Hitung Keaktifan Mencari Kerja
        $keaktifan = ['Aktif' => 0, 'Tidak Aktif' => 0];
        foreach ($dataAlumni as $d) {
            $jawaban = trim(strtolower($d->f10_aktif_mencari_kerja ?? ''));
            if ($jawaban !== '' && str_contains($jawaban, 'ya')) { 
                $keaktifan['Aktif']++;
            } else {
                $keaktifan['Tidak Aktif']++;
            }
        }

        // 3. Logika Hitung Saluran / Cara Cari Kerja (Sesuai grafik donut di gambar 1)
        $caraCariKerja = [
            'Iklan Koran' => 0, 'Melamar Langsung' => 0, 'Bursa Kerja' => 0, 'Internet' => 0, 
            'Dihubungi Perusahaan' => 0, 'Kemenakertrans' => 0, 'Agen' => 0, 'CDC Kampus' => 0, 
            'Kantor Kemanusiaan' => 0, 'Kuliah' => 0, 'Relasi' => 0, 'Bisnis Sendiri' => 0, 
            'Tempat Magang' => 0, 'Kerja Saat Kuliah' => 0, 'Lainnya' => 0
        ];
        foreach ($dataAlumni as $d) {
            if (($d->f401_iklan_koran_brosur ?? '') == 'Ya' || ($d->f401 ?? '') == 1) $caraCariKerja['Iklan Koran']++;
            if (($d->f402_melamar_tanpa_lowongan ?? '') == 'Ya' || ($d->f402 ?? '') == 1) $caraCariKerja['Melamar Langsung']++;
            if (($d->f403_bursa_pameran_online ?? '') == 'Ya' || ($d->f403 ?? '') == 1) $caraCariKerja['Bursa Kerja']++;
            if (($d->f404_internet_iklan_online ?? '') == 'Ya' || ($d->f404 ?? '') == 1) $caraCariKerja['Internet']++;
            if (($d->f405_dihubungi_perusahaan ?? '') == 'Ya' || ($d->f405 ?? '') == 1) $caraCariKerja['Dihubungi Perusahaan']++;
            if (($d->f406_menghubungi_kemenakertrans ?? '') == 'Ya' || ($d->f406 ?? '') == 1) $caraCariKerja['Kemenakertrans']++;
            if (($d->f407_agen_tenaga_kerja ?? '') == 'Ya' || ($d->f407 ?? '') == 1) $caraCariKerja['Agen']++;
            if (($d->f408_karir_fakultas_universitas ?? '') == 'Ya' || ($d->f408 ?? '') == 1) $caraCariKerja['CDC Kampus']++;
            if (($d->f409_kantor_kemanusiaan_alumni ?? '') == 'Ya' || ($d->f409 ?? '') == 1) $caraCariKerja['Kantor Kemanusiaan']++;
            if (($d->f410_membangun_jejaring_kuliah ?? '') == 'Ya' || ($d->f410 ?? '') == 1) $caraCariKerja['Kuliah']++;
            if (($d->f411_melalui_relasi ?? '') == 'Ya' || ($d->f411 ?? '') == 1) $caraCariKerja['Relasi']++;
            if (($d->f412_membangun_bisnis_sendiri ?? '') == 'Ya' || ($d->f412 ?? '') == 1) $caraCariKerja['Bisnis Sendiri']++;
            if (($d->f413_penempatan_kerja_magang ?? '') == 'Ya' || ($d->f413 ?? '') == 1) $caraCariKerja['Tempat Magang']++;
            if (($d->f414_tempat_kerja_sama_kuliah ?? '') == 'Ya' || ($d->f414 ?? '') == 1) $caraCariKerja['Kerja Saat Kuliah']++;
            if (($d->f415_lainnya ?? '') == 'Ya' || ($d->f415 ?? '') == 1) $caraCariKerja['Lainnya']++;
        }

        // 4. Logika Hitung Penekanan Metode Belajar
        $metodeBelajar = ['Perkuliahan' => 0, 'Demonstrasi' => 0, 'Riset' => 0, 'Magang' => 0, 'Praktikum' => 0, 'Kerja Lapangan' => 0, 'Diskusi' => 0];
        foreach ($dataAlumni as $d) {
            if (isset($d->f21) && str_contains(strtolower($d->f21), 'besar')) $metodeBelajar['Perkuliahan']++;
            if (isset($d->f22) && str_contains(strtolower($d->f22), 'besar')) $metodeBelajar['Demonstrasi']++;
            if (isset($d->f23) && str_contains(strtolower($d->f23), 'besar')) $metodeBelajar['Riset']++;
            if (isset($d->f24) && str_contains(strtolower($d->f24), 'besar')) $metodeBelajar['Magang']++;
            if (isset($d->f25) && str_contains(strtolower($d->f25), 'besar')) $metodeBelajar['Praktikum']++;
            if (isset($d->f26) && str_contains(strtolower($d->f26), 'besar')) $metodeBelajar['Kerja Lapangan']++;
            if (isset($d->f27) && str_contains(strtolower($d->f27), 'besar')) $metodeBelajar['Diskusi']++;
        }

        // 5. Satukan data rekap + data mentah berurutan
        $dataDashboard = [
            'keaktifan'     => $keaktifan,
            'caraCariKerja' => $caraCariKerja,
            'metodeBelajar' => $metodeBelajar,
            'alumniRaw'     => $dataAlumni 
        ];

        $namaFile = 'laporan_tracer';
        if($tahunLulus) $namaFile .= '_tahun_' . $tahunLulus;
        if($kodeProdi)  $namaFile .= '_prodi_' . $kodeProdi;
        $namaFile .= '.xlsx';

        return Excel::download(new KuesionerAlumniExport($dataDashboard), $namaFile);
    }

    // 2. Fungsi untuk memvalidasi input NIM, NIK, dan Nama alumni
    public function validasiAlumni(Request $request)
    {
        // Validasi inputan form dari alumni wajib diisi
        $request->validate([
            'no_mahasiswa' => 'required',
            'nik'          => 'required',
            'nama'         => 'required',
            'kode_prodi'   => 'required',
        ]);

        // Proses Pencocokan: Mencari di database yang NIM dan NIK-nya COCOK SAMA
        $alumni = MasterAlumni::where('no_mahasiswa', $request->no_mahasiswa)
                              ->where('nik', $request->nik)
                              ->first();

        // Jika data TIDAK ditemukan atau nama tidak mirip
        if (!$alumni || strtolower($alumni->nama) != strtolower($request->nama)) {
            return redirect()->back()->withErrors([
                'pesan_error' => 'Maaf, data identitas (NIM/NIK/Nama/Kode Prodi) Anda tidak cocok dengan data kelulusan LPKM.'
            ])->withInput();
        }

        // Jika data COCOK, simpan data alumni ke session dan loloskan ke halaman pertanyaan kuesioner
        session(['alumni_sah' => $alumni]);
        return redirect()->route('kuesioner.pertanyaan');
    }
}