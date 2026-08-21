<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class KuesionerAlumniExport extends DefaultValueBinder implements FromCollection, ShouldAutoSize, WithCustomValueBinder, WithHeadings, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $rows = [];

        // DATA UTAMA RESPONDEN (Dibuat Berurutan Sesuai Format Gambar 2)
        if (isset($this->data['alumniRaw'])) {
            foreach ($this->data['alumniRaw'] as $index => $alumni) {
                $a = (array) $alumni;
                $rows[] = [
                    $index + 1,
                    $this->n($a['kode_PT'] ?? null, $a['Kode Pt'] ?? null),
                    $this->n($a['kode_prodi'] ?? null, $a['Kode Prodi'] ?? null),
                    $this->n($a['no_mahasiswa'] ?? null, $a['Nomor Mhs'] ?? null), // Kolom D
                    $this->n($a['nama'] ?? null),
                    $this->n($a['no_hp'] ?? null, $a['Hp'] ?? null),
                    $this->n($a['email'] ?? null, $a['Email'] ?? null),
                    $this->n($a['tahun_lulus'] ?? null, $a['Tahun Lulus'] ?? null),
                    $this->n($a['nik'] ?? null, $a['NIK'] ?? null),          // Kolom I
                    $this->n($a['npwp'] ?? null, $a['NPWP'] ?? null),
                    $this->n($a['f8_status_saat_ini'] ?? null, $a['f8'] ?? null),
                    $this->n($a['f502_bulan_dapat_kerja'] ?? null, $a['f506_bulan_dapat_kerja_setelahnya'] ?? null, $a['f502'] ?? null),
                    $this->n($a['f505_pendapatan_per_bulan'] ?? null, $a['f505'] ?? null),
                    $this->n($a['f510_provinsi'] ?? null, $a['f5a1'] ?? null),
                    $this->n($a['f510_kab_kota'] ?? null, $a['f5a2'] ?? null),
                    $this->n($a['f11_jenis_instansi'] ?? null, $a['f1101'] ?? null),
                    $this->n($a['f11_jenis_instansi_lainnya'] ?? null, $a['f1102'] ?? null),
                    $this->n($a['f5b_nama_perusahaan'] ?? null, $a['f5b'] ?? null),
                    $this->f5cKode($this->n($a['f5c_posisi_wiraswasta'] ?? null, $a['f5c'] ?? null)),
                    $this->f5dKode($this->n($a['f5d_tingkat_tempat_kerja'] ?? null, $a['f5d'] ?? null)),
                    $this->n($a['f18a_sumber_biaya_studi'] ?? null, $a['f18a'] ?? null),
                    $this->n($a['f18b_perguruan_tinggi_studi'] ?? null, $a['f18b'] ?? null),
                    $this->n($a['f18c_program_studi'] ?? null, $a['f18c'] ?? null),
                    $this->n($a['f18d_tanggal_masuk'] ?? null, $a['f18d'] ?? null),
                    $this->n($a['f12_sumber_biaya_kuliah'] ?? null, $a['f1201'] ?? null),
                    $this->n($a['f12_sumber_biaya_kuliah_lainnya'] ?? null, $a['f1202'] ?? null),
                    $this->n($a['f14_erat_hubungan_studi'] ?? null, $a['f14'] ?? null),
                    $this->n($a['f15_tingkat_paling_tepat'] ?? null, $a['f15'] ?? null),
                    $this->n($a['f1701_A'] ?? null, $a['f1761'] ?? null),
                    $this->n($a['f1701_B'] ?? null, $a['f1762'] ?? null),
                    $this->n($a['f1702_A'] ?? null, $a['f1763'] ?? null),
                    $this->n($a['f1702_B'] ?? null, $a['f1764'] ?? null),
                    $this->n($a['f1703_A'] ?? null, $a['f1765'] ?? null),
                    $this->n($a['f1703_B'] ?? null, $a['f1766'] ?? null),
                    $this->n($a['f1704_A'] ?? null, $a['f1767'] ?? null),
                    $this->n($a['f1704_B'] ?? null, $a['f1768'] ?? null),
                    $this->n($a['f1705_A'] ?? null, $a['f1769'] ?? null),
                    $this->n($a['f1705_B'] ?? null, $a['f1770'] ?? null),
                    $this->n($a['f1706_A'] ?? null, $a['f1771'] ?? null),
                    $this->n($a['f1706_B'] ?? null, $a['f1772'] ?? null),
                    $this->n($a['f1707_A'] ?? null, $a['f1773'] ?? null),
                    $this->n($a['f1707_B'] ?? null, $a['f1774'] ?? null),
                    $this->n($a['f21_perkuliahan'] ?? null, $a['f21'] ?? null),
                    $this->n($a['f22_demonstrasi'] ?? null, $a['f22'] ?? null),
                    $this->n($a['f23_riset'] ?? null, $a['f23'] ?? null),
                    $this->n($a['f24_magang'] ?? null, $a['f24'] ?? null),
                    $this->n($a['f25_praktikum'] ?? null, $a['f25'] ?? null),
                    $this->n($a['f26_kerja_lapangan'] ?? null, $a['f26'] ?? null),
                    $this->n($a['f27_diskusi'] ?? null, $a['f27'] ?? null),
                    $this->n($a['f301_kapan_mencari_pekerjaan'] ?? null, $a['f301'] ?? null),
                    $this->n($a['f302_bulan_sebelum_lulus'] ?? null, $a['f302'] ?? null),
                    $this->n($a['f303_bulan_setelah_lulus'] ?? null, $a['f303'] ?? null),
                    $this->n($a['f401_iklan_koran_brosur'] ?? null, $a['f401'] ?? null),
                    $this->n($a['f402_melamar_tanpa_lowongan'] ?? null, $a['f402'] ?? null),
                    $this->n($a['f403_bursa_pameran_online'] ?? null, $a['f403'] ?? null),
                    $this->n($a['f404_internet_iklan_online'] ?? null, $a['f404'] ?? null),
                    $this->n($a['f405_dihubungi_perusahaan'] ?? null, $a['f405'] ?? null),
                    $this->n($a['f406_menghubungi_kemenakertrans'] ?? null, $a['f406'] ?? null),
                    $this->n($a['f407_agen_tenaga_kerja'] ?? null, $a['f407'] ?? null),
                    $this->n($a['f408_karir_fakultas_universitas'] ?? null, $a['f408'] ?? null),
                    $this->n($a['f409_kantor_kemanusiaan_alumni'] ?? null, $a['f409'] ?? null),
                    $this->n($a['f410_membangun_jejaring_kuliah'] ?? null, $a['f410'] ?? null),
                    $this->n($a['f411_melalui_relasi'] ?? null, $a['f411'] ?? null),
                    $this->n($a['f412_membangun_bisnis_sendiri'] ?? null, $a['f412'] ?? null),
                    $this->n($a['f413_penempatan_kerja_magang'] ?? null, $a['f413'] ?? null),
                    $this->n($a['f414_tempat_kerja_sama_kuliah'] ?? null, $a['f414'] ?? null),
                    $this->n($a['f415_lainnya'] ?? null, $a['f415'] ?? null),
                    $this->n($a['f416_tuliskan'] ?? null, $a['f416'] ?? null),
                    $this->n($a['f6_perusahaan_dilamar'] ?? null),
                    $this->n($a['f7_perusahaan_merespon'] ?? null),
                    $this->n($a['f7a_mengundang_wawancara'] ?? null),
                    $this->n($a['f10_aktif_mencari_kerja'] ?? null, $a['f1001'] ?? null),
                    $this->n($a['f10_lainnya'] ?? null, $a['f1002'] ?? null),
                    $this->n($a['f1601_pertanyaan_tidak_sesuai'] ?? null, $a['f1601'] ?? null),
                    $this->n($a['f1602_belum_dapat_kerja_sesuai'] ?? null, $a['f1602'] ?? null),
                    $this->n($a['f1603_prospek_karir_baik'] ?? null, $a['f1603'] ?? null),
                    $this->n($a['f1604_suka_area_kerja_tersebut'] ?? null, $a['f1604'] ?? null),
                    $this->n($a['f1605_dipromosikan_posisi_lain'] ?? null, $a['f1605'] ?? null),
                    $this->n($a['f1606_pendapatan_lebih_tinggi'] ?? null, $a['f1606'] ?? null),
                    $this->n($a['f1607_pekerjaan_lebih_aman'] ?? null, $a['f1607'] ?? null),
                    $this->n($a['f1608_pekerjaan_lebih_menarik'] ?? null, $a['f1608'] ?? null),
                    $this->n($a['f1609_mungkinkan_kerja_tambahan'] ?? null, $a['f1609'] ?? null),
                    $this->n($a['f1610_lokasi_dekat_rumah'] ?? null, $a['f1610'] ?? null),
                    $this->n($a['f1611_menjamin_kebutuhan_keluarga'] ?? null, $a['f1611'] ?? null),
                    $this->n($a['f1612_awal_menitip_karir'] ?? null, $a['f1612'] ?? null),
                    $this->n($a['f1613_lainnya'] ?? null, $a['f1613'] ?? null),
                    $this->n($a['f1614_tuliskan'] ?? null, $a['f1614'] ?? null),
                ];
            }
        }

        return collect($rows);
    }

    // Ambil nilai jawaban pertama yang benar-benar terisi.
    // NULL, string kosong, atau properti yang tidak ada otomatis diganti '0'
    private function n(...$kandidat): string
    {
        foreach ($kandidat as $nilai) {
            if ($nilai !== null && trim((string) $nilai) !== '') {
                return (string) $nilai;
            }
        }

        return '0';
    }

    // Mengunci cell yang berpotensi kehilangan angka 0 di depan / jadi notasi ilmiah
    public function bindValue(Cell $cell, $value)
    {
        // D = Nomor Mhs (NIM), F = Hp (08xxx), I = NIK, J = NPWP (15 digit)
        if (in_array($cell->getColumn(), ['D', 'F', 'I', 'J'], true)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // Cegah formula injection: nilai teks berawalan =, +, -, @ bisa dianggap rumus
        // oleh Excel (mis. =HYPERLINK(...) atau DDE). Paksa ditulis sebagai teks biasa.
        if (is_string($value) && $value !== '' && in_array($value[0], ['=', '+', '-', '@'], true)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // Kembali ke format default untuk kolom lainnya
        return parent::bindValue($cell, $value);
    }

    // Posisi/jabatan wiraswasta (F5c): 1=Founder, 2=Co-Founder, 3=Staff, 4=Freelance
    private function f5cKode($nilai): string
    {
        $teks = strtolower(trim((string) $nilai));

        if (str_contains($teks, 'co-founder') || str_contains($teks, 'cofounder')) {
            return '2';
        }
        if (str_contains($teks, 'founder') || str_contains($teks, 'owner') || str_contains($teks, 'pemilik')) {
            return '1';
        }
        if (str_contains($teks, 'staff') || str_contains($teks, 'staf')) {
            return '3';
        }
        if (str_contains($teks, 'freelance') || str_contains($teks, 'freelace') || str_contains($teks, 'lepas')) {
            return '4';
        }

        return (string) $nilai;
    }

    // Tingkat tempat kerja (F5d): 1=Lokal, 2=Nasional, 3=Internasional
    private function f5dKode($nilai): string
    {
        $teks = strtolower(trim((string) $nilai));

        if (str_contains($teks, 'internasional') || str_contains($teks, 'internasional/multinasional') || str_contains($teks, 'multinasional')) {
            return '3';
        }
        if (str_contains($teks, 'nasional')) {
            return '2';
        }
        if (str_contains($teks, 'lokal') || str_contains($teks, 'local') || str_contains($teks, 'wilayah')) {
            return '1';
        }

        return (string) $nilai;
    }

    public function headings(): array
    {
        return [
            'NO', 'Kode Pt', 'Kode Prodi', 'Nomor Mhs', 'Nama', 'Hp', 'Email', 'Tahun Lulus', 'NIK', 'NPWP',
            'f8', 'f502', 'f505', 'f5a1', 'f5a2', 'f1101', 'f1102', 'f5b', 'f5c', 'f5d',
            'f18a', 'f18b', 'f18c', 'f18d', 'f1201', 'f1202', 'f14', 'f15', 'f1761', 'f1762', 'f1763',
            'f1764', 'f1765', 'f1766', 'f1767', 'f1768', 'f1769', 'f1770', 'f1771', 'f1772', 'f1773',
            'f1774', 'f21', 'f22', 'f23', 'f24', 'f25', 'f26', 'f27', 'f301', 'f302', 'f303', 'f401',
            'f402', 'f403', 'f404', 'f405', 'f406', 'f407', 'f408', 'f409', 'f410', 'f411', 'f412',
            'f413', 'f414', 'f415', 'f416', 'f6', 'f7', 'f7a', 'f1001', 'f1002', 'f1601', 'f1602',
            'f1603', 'f1604', 'f1605', 'f1606', 'f1607', 'f1608', 'f1609', 'f1610', 'f1611', 'f1612',
            'f1613', 'f1614',
        ];
    }

    public function title(): string
    {
        return 'Data Terurut Alumni';
    }
}
