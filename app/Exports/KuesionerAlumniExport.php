<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KuesionerAlumniExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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
                    $alumni->kode_PT ?? $alumni->{'Kode Pt'} ?? '-',
                    $alumni->kode_prodi ?? $alumni->{'Kode Prodi'} ?? '-',
                    $alumni->no_mahasiswa ?? $alumni->{'Nomor Mhs'} ?? '-',
                    $alumni->nama ?? '-', // Menggunakan 'Nama' sesuai database Anda
                    $alumni->no_hp ?? $alumni->Hp ?? '-',
                    $alumni->email ?? $alumni->Email ?? '-',
                    $alumni->tahun_lulus ?? $alumni->{'Tahun Lulus'} ?? '-',
                    $alumni->nik ?? $alumni->NIK ?? '-',
                    $alumni->npwp ?? $alumni->NPWP ?? '-',
                    $alumni->f8_status_saat_ini ?? $alumni->f8 ?? '-',
                    $alumni->f504_mendapat_pekerjaan_6_bulan ?? $alumni-> f504 ?? '-',
                    $alumni->f502_bulan_dapat_kerja ?? $alumni->f502 ?? '-',
                    $alumni->f505_pendapatan_per_bulan ?? $alumni->f505 ?? '-',
                    $alumni->f506_bulan_dapat_kerja_setelahnya ?? $alumni->f506 ?? '-',
                    $alumni->f510_provinsi ?? $alumni->f5a1 ?? '-',
                    $alumni->f510_kab_kota ?? $alumni->f5a2 ?? '-',
                    $alumni->f11_jenis_instansi ?? $alumni->f1101 ?? '-',
                    $alumni->f11_jenis_instansi_lainnya ?? $alumni->f1102 ?? '-',
                    $alumni->f5b_nama_perusahaan ?? $alumni->f5b ?? '-',
                    $alumni->f5c_posisi_wiraswasta ?? $alumni->f5c ?? '-',
                    $alumni->f5d_tingkat_tempat_kerja ?? $alumni->f5d ?? '-',
                    $alumni->f18a_sumber_biaya_studi ?? $alumni->f18a ?? '-',
                    $alumni->f18b_perguruan_tinggi_studi ?? $alumni->f18b ?? '-',
                    $alumni->f18c_program_studi ?? $alumni->f18c ?? '-',
                    $alumni->f18d_tanggal_masuk ?? $alumni->f18d ?? '-',
                    $alumni->f12_sumber_biaya_kuliah ?? $alumni->f1201 ?? '-',
                    $alumni->f12_sumber_biaya_kuliah_lainnya ?? $alumni->f1202 ?? '-',
                    $alumni->f14_erat_hubungan_studi ?? $alumni->f14 ?? '-',
                    $alumni->f15_tingkat_paling_tepat ?? $alumni->f15 ?? '-',
                    $alumni->f1701_A ?? $alumni->f1761 ?? '-',
                    $alumni->f1702_A ?? $alumni->f1762 ?? '-',
                    $alumni->f1703_A ?? $alumni->f1763 ?? '-',
                    $alumni->f1704_A ?? $alumni->f1764 ?? '-',
                    $alumni->f1705_A ?? $alumni->f1765 ?? '-',
                    $alumni->f1706_A ?? $alumni->f1766 ?? '-',
                    $alumni->f1707_A ?? $alumni->f1767 ?? '-',
                    $alumni->f1701_B ?? $alumni->f1768 ?? '-',
                    $alumni->f1702_B ?? $alumni->f1769 ?? '-',
                    $alumni->f1703_B ?? $alumni->f1770 ?? '-',
                    $alumni->f1704_B ?? $alumni->f1771 ?? '-',
                    $alumni->f1705_B ?? $alumni->f1772 ?? '-',
                    $alumni->f1706_B ?? $alumni->f1773 ?? '-',
                    $alumni->f1707_B ?? $alumni->f1774 ?? '-',
                    $alumni->f21_perkuliahan ?? $alumni->f21 ?? '-',
                    $alumni->f22_demonstrasi ?? $alumni->f22 ?? '-',
                    $alumni->f23_riset ?? $alumni->f23 ?? '-',
                    $alumni->f24_magang ?? $alumni->f24 ?? '-',
                    $alumni->f25_praktikum ?? $alumni->f25 ?? '-',
                    $alumni->f26_kerja_lapangan ?? $alumni->f26 ?? '-',
                    $alumni->f27_diskusi ?? $alumni->f27 ?? '-',
                    $alumni->f301_kapan_mencari_pekerjaan ?? $alumni->f301 ?? '-',
                    $alumni->f302_bulan_sebelum_lulus ?? $alumni->f302 ?? '-',
                    $alumni->f303_bulan_setelah_lulus ?? $alumni->f303 ?? '-',
                    $alumni->f401_iklan_koran_brosur ?? $alumni->f401 ?? '-',
                    $alumni->f402_melamar_tanpa_lowongan ?? $alumni->f402 ?? '-',
                    $alumni->f403_bursa_pameran_online ?? $alumni->f403 ?? '-',
                    $alumni->f404_internet_iklan_online ?? $alumni->f404 ?? '-',
                    $alumni->f405_dihubungi_perusahaan ?? $alumni->f405 ?? '-',
                    $alumni->f406_menghubungi_kemenakertrans ?? $alumni->f406 ?? '-',
                    $alumni->f407_agen_tenaga_kerja ?? $alumni->f407 ?? '-',
                    $alumni->f408_karir_fakultas_universitas ?? $alumni->f408 ?? '-',
                    $alumni->f409_kantor_kemanusiaan_alumni ?? $alumni->f409 ?? '-',
                    $alumni->f410_membangun_jejaring_kuliah ?? $alumni->f410 ?? '-',
                    $alumni->f411_melalui_relasi ?? $alumni->f411 ?? '-',
                    $alumni->f412_membangun_bisnis_sendiri ?? $alumni->f412 ?? '-',
                    $alumni->f413_penempatan_kerja_magang ?? $alumni->f413 ?? '-',
                    $alumni->f414_tempat_kerja_sama_kuliah ?? $alumni->f414 ?? '-',
                    $alumni->f415_lainnya ?? $alumni->f415 ?? '-',
                    $alumni->f6_perusahaan_dilamar ?? $alumni->f6 ?? '-',
                    $alumni->f7_perusahaan_merespon ?? $alumni->f7 ?? '-',
                    $alumni->f7a_mengundang_wawancara ?? $alumni->f7a ?? '-',
                    $alumni->f10_aktif_mencari_kerja ?? $alumni->f1001 ?? '-',
                    $alumni->f10_lainnya ?? $alumni->f1002 ?? '-',
                    $alumni->f1601_pertanyaan_tidak_sesuai ?? $alumni->f1601 ?? '-',
                    $alumni->f1602_belum_dapat_kerja_sesuai ?? $alumni->f1602 ?? '-',
                    $alumni->f1603_prospek_karir_baik ?? $alumni->f1603 ?? '-',
                    $alumni->f1604_suka_area_kerja_tersebut ?? $alumni->f1604 ?? '-',
                    $alumni->f1605_dipromosikan_posisi_lain ?? $alumni->f1605 ?? '-',
                    $alumni->f1606_pendapatan_lebih_tinggi ?? $alumni->f1606 ?? '-',
                    $alumni->f1607_pekerjaan_lebih_aman ?? $alumni->f1607 ?? '-',
                    $alumni->f1608_pekerjaan_lebih_menarik ?? $alumni->f1608 ?? '-',
                    $alumni->f1609_mungkinkan_kerja_tambahan ?? $alumni->f1609 ?? '-',
                    $alumni->f1610_lokasi_dekat_rumah ?? $alumni->f1610 ?? '-',
                    $alumni->f1611_menjamin_kebutuhan_keluarga ?? $alumni->f1611 ?? '-',
                    $alumni->f1612_awal_menitip_karir ?? $alumni->f1612 ?? '-',
                    $alumni->f1613_lainnya ?? $alumni->f1613 ?? '-',

                ];
            }
        }

        return collect($rows);
    }

    // Judul Header kolom atas dibuat persis seperti gambar contoh Anda
    public function headings(): array
    {
        return [
            'NO',
            'Kode Pt',
            'Kode Prodi',
            'Nomor Mhs',
            'Nama',
            'Hp',
            'Email',
            'Tahun Lulus',
            'NIK',
            'NPWP',
            'f8',
            'f504',
            'f502',
            'f505',
            'f506',
            'f5a1',
            'f5a2',
            'f1101',
            'f1102',
            'f5b',
            'f5c',
            'f5d',
            'f18a',
            'f18b',
            'f18c',
            'f18d',
            'f1201',
            'f1202',
            'f14',
            'f15',
            'f1761',
            'f1762',
            'f1763',
            'f1764',
            'f1765',
            'f1766',
            'f1767',
            'f1768',
            'f1769',
            'f1770',
            'f1771',
            'f1772',
            'f1773',
            'f1774',
            'f21',
            'f22',
            'f23',
            'f24',
            'f25',
            'f26',
            'f27',
            'f301',
            'f302',
            'f303',
            'f401',
            'f402',
            'f403',
            'f404',
            'f405',
            'f406',
            'f407',
            'f408',
            'f409',
            'f410',
            'f411',
            'f412',
            'f413',
            'f414',
            'f415',
            'f6',
            'f7',
            'f7a',
            'f1001',
            'f1002',
            'f1601',
            'f1602',
            'f1603',
            'f1604',
            'f1605',
            'f1606',
            'f1607',
            'f1608',
            'f1609',
            'f1610',
            'f1611',
            'f1612',
            'f1613',

        ];
    }

    public function title(): string
    {
        return 'Data Terurut Alumni';
    }
}