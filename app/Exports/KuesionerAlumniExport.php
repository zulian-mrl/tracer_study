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
                $rows[] = [
                    $index + 1,
                    $alumni->kode_PT ?? $alumni->{'Kode Pt'} ?? '0',
                    $alumni->kode_prodi ?? $alumni->{'Kode Prodi'} ?? '0',
                    $alumni->no_mahasiswa ?? $alumni->{'Nomor Mhs'} ?? '0', // Kolom D
                    $alumni->nama ?? '0',
                    $alumni->no_hp ?? $alumni->Hp ?? '0',
                    $alumni->email ?? $alumni->Email ?? '0',
                    $alumni->tahun_lulus ?? $alumni->{'Tahun Lulus'} ?? '0',
                    $alumni->nik ?? $alumni->NIK ?? '0',                    // Kolom I
                    $alumni->npwp ?? $alumni->NPWP ?? '0',
                    $alumni->f8_status_saat_ini ?? $alumni->f8 ?? '0',
                    $alumni->f502_bulan_dapat_kerja ?? $alumni->f506_bulan_dapat_kerja_setelahnya ?? $alumni->f502 ?? '0',
                    $alumni->f505_pendapatan_per_bulan ?? $alumni->f505 ?? '0',
                    $alumni->f510_provinsi ?? $alumni->f5a1 ?? '0',
                    $alumni->f510_kab_kota ?? $alumni->f5a2 ?? '0',
                    $alumni->f11_jenis_instansi ?? $alumni->f1101 ?? '0',
                    $alumni->f11_jenis_instansi_lainnya ?? $alumni->f1102 ?? '0',
                    $alumni->f5b_nama_perusahaan ?? $alumni->f5b ?? '0',
                    $this->f5cKode($alumni->f5c_posisi_wiraswasta ?? $alumni->f5c ?? '0'),
                    $this->f5dKode($alumni->f5d_tingkat_tempat_kerja ?? $alumni->f5d ?? '0'),
                    $alumni->f18a_sumber_biaya_studi ?? $alumni->f18a ?? '0',
                    $alumni->f18b_perguruan_tinggi_studi ?? $alumni->f18b ?? '0',
                    $alumni->f18c_program_studi ?? $alumni->f18c ?? '0',
                    $alumni->f18d_tanggal_masuk ?? $alumni->f18d ?? '0',
                    $alumni->f12_sumber_biaya_kuliah ?? $alumni->f1201 ?? '0',
                    $alumni->f12_sumber_biaya_kuliah_lainnya ?? $alumni->f1202 ?? '0',
                    $alumni->f14_erat_hubungan_studi ?? $alumni->f14 ?? '0',
                    $alumni->f15_tingkat_paling_tepat ?? $alumni->f15 ?? '0',
                    $alumni->f1701_A ?? $alumni->f1761 ?? '0',
                    $alumni->f1701_B ?? $alumni->f1762 ?? '0',
                    $alumni->f1702_A ?? $alumni->f1763 ?? '0',
                    $alumni->f1702_B ?? $alumni->f1764 ?? '0',
                    $alumni->f1703_A ?? $alumni->f1765 ?? '0',
                    $alumni->f1703_B ?? $alumni->f1766 ?? '0',
                    $alumni->f1704_A ?? $alumni->f1767 ?? '0',
                    $alumni->f1704_B ?? $alumni->f1768 ?? '0',
                    $alumni->f1705_A ?? $alumni->f1769 ?? '0',
                    $alumni->f1705_B ?? $alumni->f1770 ?? '0',
                    $alumni->f1706_A ?? $alumni->f1771 ?? '0',
                    $alumni->f1706_B ?? $alumni->f1772 ?? '0',
                    $alumni->f1707_A ?? $alumni->f1773 ?? '0',
                    $alumni->f1707_B ?? $alumni->f1774 ?? '0',
                    $alumni->f21_perkuliahan ?? $alumni->f21 ?? '0',
                    $alumni->f22_demonstrasi ?? $alumni->f22 ?? '0',
                    $alumni->f23_riset ?? $alumni->f23 ?? '0',
                    $alumni->f24_magang ?? $alumni->f24 ?? '0',
                    $alumni->f25_praktikum ?? $alumni->f25 ?? '0',
                    $alumni->f26_kerja_lapangan ?? $alumni->f26 ?? '0',
                    $alumni->f27_diskusi ?? $alumni->f27 ?? '0',
                    $alumni->f301_kapan_mencari_pekerjaan ?? $alumni->f301 ?? '0',
                    $alumni->f302_bulan_sebelum_lulus ?? $alumni->f302 ?? '0',
                    $alumni->f303_bulan_setelah_lulus ?? $alumni->f303 ?? '0',
                    $alumni->f401_iklan_koran_brosur ?? $alumni->f401 ?? '0',
                    $alumni->f402_melamar_tanpa_lowongan ?? $alumni->f402 ?? '0',
                    $alumni->f403_bursa_pameran_online ?? $alumni->f403 ?? '0',
                    $alumni->f404_internet_iklan_online ?? $alumni->f404 ?? '0',
                    $alumni->f405_dihubungi_perusahaan ?? $alumni->f405 ?? '0',
                    $alumni->f406_menghubungi_kemenakertrans ?? $alumni->f406 ?? '0',
                    $alumni->f407_agen_tenaga_kerja ?? $alumni->f407 ?? '0',
                    $alumni->f408_karir_fakultas_universitas ?? $alumni->f408 ?? '0',
                    $alumni->f409_kantor_kemanusiaan_alumni ?? $alumni->f409 ?? '0',
                    $alumni->f410_membangun_jejaring_kuliah ?? $alumni->f410 ?? '0',
                    $alumni->f411_melalui_relasi ?? $alumni->f411 ?? '0',
                    $alumni->f412_membangun_bisnis_sendiri ?? $alumni->f412 ?? '0',
                    $alumni->f413_penempatan_kerja_magang ?? $alumni->f413 ?? '0',
                    $alumni->f414_tempat_kerja_sama_kuliah ?? $alumni->f414 ?? '0',
                    $alumni->f415_lainnya ?? $alumni->f415 ?? '0',
                    $alumni->f416_tuliskan ?? $alumni->f416 ?? '0',
                    strval($alumni->f6_perusahaan_dilamar ?? 0),
                    strval($alumni->f7_perusahaan_merespon ?? 0),
                    strval($alumni->f7a_mengundang_wawancara ?? 0),
                    $alumni->f10_aktif_mencari_kerja ?? $alumni->f1001 ?? '0',
                    $alumni->f10_lainnya ?? $alumni->f1002 ?? '0',
                    $alumni->f1601_pertanyaan_tidak_sesuai ?? $alumni->f1601 ?? '0',
                    $alumni->f1602_belum_dapat_kerja_sesuai ?? $alumni->f1602 ?? '0',
                    $alumni->f1603_prospek_karir_baik ?? $alumni->f1603 ?? '0',
                    $alumni->f1604_suka_area_kerja_tersebut ?? $alumni->f1604 ?? '0',
                    $alumni->f1605_dipromosikan_posisi_lain ?? $alumni->f1605 ?? '0',
                    $alumni->f1606_pendapatan_lebih_tinggi ?? $alumni->f1606 ?? '0',
                    $alumni->f1607_pekerjaan_lebih_aman ?? $alumni->f1607 ?? '0',
                    $alumni->f1608_pekerjaan_lebih_menarik ?? $alumni->f1608 ?? '0',
                    $alumni->f1609_mungkinkan_kerja_tambahan ?? $alumni->f1609 ?? '0',
                    $alumni->f1610_lokasi_dekat_rumah ?? $alumni->f1610 ?? '0',
                    $alumni->f1611_menjamin_kebutuhan_keluarga ?? $alumni->f1611 ?? '0',
                    $alumni->f1612_awal_menitip_karir ?? $alumni->f1612 ?? '0',
                    $alumni->f1613_lainnya ?? $alumni->f1613 ?? '0',
                    $alumni->f1614_tuliskan ?? $alumni->f1614 ?? '0',
                ];
            }
        }

        return collect($rows);
    }

    // Mengunci cell yang berpotensi kehilangan angka 0 di depan / jadi notasi ilmiah
    public function bindValue(Cell $cell, $value)
    {
        // D = Nomor Mhs (NIM), F = Hp (08xxx), I = NIK, J = NPWP (15 digit)
        if (in_array($cell->getColumn(), ['D', 'F', 'I', 'J'], true)) {
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
