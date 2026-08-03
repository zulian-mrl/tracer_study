# Bab 3 — Pendefinisian Imports (app/Imports)

## 3.x.x Import `AlumniImport`

`AlumniImport` merupakan kelas yang berperan sebagai pembaca berkas Excel pada proses impor data master alumni, yang didefinisikan pada berkas `app/Imports/AlumniImport.php` di dalam *namespace* `App\Imports`. Kelas ini dibangun dengan memanfaatkan pustaka *Maatwebsite Excel* dan menerapkan *interface* `ToModel`, sehingga setiap baris data yang dibaca dari berkas Excel dapat langsung dikonversi menjadi baris data pada model `MasterAlumni`.

Kelas `AlumniImport` memiliki satu metode utama, yaitu metode `model(array $row)` yang dipanggil secara otomatis oleh pustaka *Maatwebsite Excel* untuk setiap baris data yang terdapat pada berkas. Metode ini menerima parameter berupa array `row` yang berisi nilai setiap kolom pada satu baris berkas Excel. Sebelum data disimpan, metode ini melakukan pemeriksaan terhadap baris yang diterima, yaitu apabila kolom pertama pada baris tersebut kosong atau berisi teks yang menunjukkan baris tersebut merupakan baris judul, seperti `no_mahasiswa`, `nim`, `no`, `no mahasiswa`, atau `tahun_lulus`, maka baris tersebut akan dilewati dengan mengembalikan nilai `null`. Pemeriksaan ini dimaksudkan untuk mencegah baris judul atau baris kosong ikut tersimpan ke dalam tabel *database*.

Selanjutnya, setiap nilai pada baris data dipetakan ke dalam kolom yang sesuai pada model `MasterAlumni`, yaitu kolom `no_mahasiswa` diambil dari kolom pertama berkas Excel, kolom `kode_prodi` dari kolom kedua, kolom `nama` dari kolom ketiga, kolom `nik` dari kolom keempat, dan kolom `tahun_lulus` dari kolom kelima. Pemetaan ini dilakukan berdasarkan urutan kolom pada berkas Excel, sehingga format berkas yang diimpor harus mengikuti urutan kolom tersebut agar data yang tersimpan sesuai.

Dengan perancangan kelas tersebut, proses impor data alumni yang berasal dari berkas Excel dapat dilakukan secara otomatis dan terstruktur, di mana setiap baris data langsung dibentuk menjadi objek model `MasterAlumni` dan disimpan ke dalam tabel `master_alumnis` untuk dijadikan data acuan pada proses verifikasi pengisian kuesioner oleh alumni.
