# Bab 3 — Pendefinisian Exports (app/Exports)

## 3.x.x Export `KuesionerAlumniExport`

`KuesionerAlumniExport` merupakan kelas yang berperan sebagai pembentuk berkas laporan Excel pada sistem, yang didefinisikan pada berkas `app/Exports/KuesionerAlumniExport.php` di dalam *namespace* `App\Exports`. Kelas ini dibangun dengan memanfaatkan pustaka *Maatwebsite Excel* yang merupakan pembungkus dari *PhpSpreadsheet*, sehingga mampu menghasilkan berkas berformat `.xlsx` sesuai dengan struktur data hasil tracer study.

Secara struktur pewarisan, kelas `KuesionerAlumniExport` diturunkan dari kelas `DefaultValueBinder` milik *PhpSpreadsheet* yang berfungsi sebagai pengatur nilai *default* pada sel Excel. Selain itu, kelas ini menerapkan beberapa *interface* dari *Maatwebsite Excel*, yaitu `FromCollection` untuk mengambil kumpulan data yang akan ditulis ke lembar kerja, `WithHeadings` untuk menulis baris judul kolom, `WithTitle` untuk menetapkan nama lembar kerja, `ShouldAutoSize` untuk menyesuaikan lebar kolom secara otomatis, serta `WithCustomValueBinder` untuk menentukan aturan penulisan nilai tertentu ke dalam sel.

Kelas ini memiliki atribut `data` yang menampung seluruh data yang akan diekspor, yang diterima melalui konstruktor dalam bentuk array. Data tersebut terdiri atas hasil rekap *dashboard* serta data mentah alumni yang telah diambil dari tabel `kuesioner_alumnis`.

Adapun metode-metode yang dimiliki oleh kelas `KuesionerAlumniExport` antara lain:

a) Metode `collection()`, yaitu metode yang membangun kumpulan baris data responden untuk ditulis ke dalam lembar kerja Excel. Metode ini mengolah setiap data alumni dari bagian `alumniRaw` dan menyusunnya menjadi baris-baris berurutan yang terdiri atas nomor urut, kode perguruan tinggi, kode program studi, nomor mahasiswa, nama, nomor handphone, alamat surel, tahun lulus, nomor induk kependudukan, nomor pokok wajib pajak, serta seluruh jawaban kuesioner mulai dari kolom `f8` hingga `f1614`. Setiap nilai yang tidak tersedia pada data akan digantikan dengan nilai cadangan berupa `'0'`, sehingga berkas laporan tetap memiliki struktur kolom yang lengkap dan konsisten.

b) Metode `bindValue(Cell $cell, $value)`, yaitu metode yang menentukan cara nilai dituliskan ke dalam setiap sel pada Excel. Metode ini memberikan perlakuan khusus terhadap kolom `D` yang berisi nomor mahasiswa (NIM) dan kolom `I` yang berisi nomor induk kependudukan (NIK), yaitu kedua kolom tersebut dipaksa ditulis sebagai teks murni dengan tipe data string. Perlakuan ini dimaksudkan untuk mencegah nilai NIM dan NIK berubah bentuk menjadi format angka yang tidak sesuai, misalnya menghilangkan angka nol di awal. Sementara itu, untuk kolom lainnya, penulisan nilai dikembalikan kepada aturan *default* bawaan kelas induk.

c) Metode `headings()`, yaitu metode yang digunakan untuk menetapkan baris judul kolom pada lembar kerja, mulai dari judul `NO`, `Kode Pt`, `Kode Prodi`, `Nomor Mhs`, `Nama`, hingga seluruh kode kolom jawaban kuesioner yang berakhir pada `f1614`.

d) Metode `title()`, yaitu metode yang digunakan untuk menetapkan nama lembar kerja pada berkas Excel, yaitu `Data Terurut Alumni`.

Dengan perancangan kelas tersebut, seluruh data hasil tracer study dapat diunduh menjadi berkas Excel yang terstruktur sesuai dengan format pelaporan yang dibutuhkan, dengan penanganan khusus pada kolom identitas agar keakuratan data tetap terjaga.
