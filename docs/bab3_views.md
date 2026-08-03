# Bab 3 — Pendefinisian View (resources/views)

## 3.x.x View `welcome.blade.php`

View `welcome.blade.php` merupakan halaman tampilan awal bawaan *framework* Laravel yang didefinisikan pada berkas `resources/views/welcome.blade.php`. Halaman ini menggunakan bahasa templating *Blade* serta kerangka gaya *Tailwind CSS* yang dimuat melalui *Vite*. Halaman ini berisi struktur dokumen HTML lengkap dengan elemen `head` yang memuat judul aplikasi, tautan *font*, serta berkas gaya dan skrip. Di bagian badan halaman, terdapat navigasi yang menampilkan tautan menuju halaman *dashboard* apabila pengguna telah masuk ke dalam sistem, atau tautan menuju halaman login apabila pengguna belum masuk. Halaman ini bersifat statis dan hanya berfungsi sebagai halaman pembuka aplikasi.

## 3.x.x View `login.blade.php`

View `login.blade.php` merupakan halaman formulir masuk (*login*) untuk pengguna admin yang didefinisikan pada berkas `resources/views/login.blade.php`. Halaman ini dirancang dengan *framework* *Tailwind CSS* yang dimuat melalui CDN serta gaya kustom tambahan berupa gradien latar gelap, efek kaca (*glass*), dan animasi melayang pada kartu login.

Halaman ini menampilkan formulir yang berisi kolom `email` dan `password` beserta opsi untuk mengingat sesi login, yang dikirimkan melalui metode `POST` menuju *route* yang ditangani oleh `AuthController`. Apabila terdapat kesalahan pada proses autentikasi, halaman akan menampilkan pesan kesalahan di bawah kolom email bahwa email atau kata sandi yang dimasukkan salah. Selain itu, halaman ini dilengkapi tautan menuju halaman pemulihan kata sandi bagi *super admin* yang lupa kata sandi. Seluruh elemen masukan menggunakan gaya yang seragam dengan efek fokus menyala, sehingga tampilan halaman tetap konsisten dengan halaman lainnya.

## 3.x.x View `pemulihan.blade.php`

View `pemulihan.blade.php` merupakan halaman pemulihan kata sandi bagi akun *super admin* yang didefinisikan pada berkas `resources/views/pemulihan.blade.php`. Halaman ini menampilkan kartu formulir yang berisi kolom `email`, `kode` pemulihan, dan `password` beserta kolom konfirmasi kata sandi. Data yang diisi dikirimkan melalui metode `POST` dan ditangani oleh `RecoveryController`.

Halaman ini menggunakan desain yang serupa dengan halaman login, yaitu latar gradien gelap dengan efek kaca dan animasi kemunculan, sehingga tampilan antar halaman terlihat konsisten. Apabila terdapat kesalahan, misalnya kode pemulihan salah atau akun bukan *super admin*, pesan kesalahan akan ditampilkan pada halaman ini. Apabila proses berhasil, pengguna diarahkan kembali ke halaman login.

## 3.x.x View `accounts.blade.php`

View `accounts.blade.php` merupakan halaman pengelolaan akun admin yang hanya dapat diakses oleh pengguna berstatus *super admin*, yang didefinisikan pada berkas `resources/views/accounts.blade.php`. Halaman ini diteruskan oleh `AccountController` bersama dengan data daftar seluruh akun.

Halaman ini terdiri atas beberapa bagian utama, yaitu:

a) Bagian navigasi atas yang berisi judul halaman, tautan menuju *dashboard*, dan tombol keluar dari sistem.

b) Bagian formulir tambah akun yang berisi kolom `name`, `email`, `password`, `password_confirmation`, serta opsi untuk menjadikan akun sebagai *super admin*. Formulir ini dikirimkan melalui metode `POST` dan ditangani oleh metode `store` pada `AccountController`.

c) Bagian daftar akun yang menampilkan tabel berisi nama, email, tipe akun, dan aksi. Pada bagian ini, akun *super admin* ditandai dengan label khusus, sedangkan akun admin biasa dilengkapi tombol untuk menjadikan *super admin*, mereset kata sandi, dan menghapus akun. Untuk akun *super admin* lainnya, tersedia aksi untuk menurunkan status, mereset kata sandi, dan menghapus sesuai dengan ketentuan pengamanan yang berlaku pada *controller*.

d) Bagian pengaturan profil dan kata sandi, yang menampilkan formulir untuk mengubah nama akun, mengunggah foto profil, mengganti kata sandi, serta mengelola kode pemulihan.

Halaman ini juga menampilkan pesan keberhasilan dan daftar pesan kesalahan apabila proses validasi gagal, sehingga pengguna dapat mengetahui status dari setiap aksi yang dilakukan.

## 3.x.x View `settings.blade.php`

View `settings.blade.php` merupakan halaman pengaturan aplikasi yang didefinisikan pada berkas `resources/views/settings.blade.php`. Halaman ini diteruskan oleh `SettingsController` bersama dengan seluruh data pengaturan yang tersimpan pada tabel `settings` beserta nilai *default*-nya.

Halaman ini menampilkan formulir pengaturan dalam bentuk beberapa kelompok yang disesuaikan dengan jenis pengaturan, antara lain pengaturan kuesioner berupa judul kuesioner, nama universitas, subjudul, instruksi, dan pesan keberhasilan; pengaturan *dashboard* berupa judul halaman dan warna aksen; pengaturan bentuk serta warna setiap grafik; judul setiap grafik; serta nama dan warna setiap irisan atau seri grafik. Seluruh nilai pada formulir diisi menggunakan data yang diambil dari model `Setting`, sehingga administrator dapat mengubah tampilan kuesioner dan *dashboard* secara langsung melalui halaman ini tanpa mengubah kode program.

Halaman ini juga dilengkapi navigasi menuju *dashboard*, tombol ganti kata sandi, dan tombol keluar, serta menampilkan pesan keberhasilan apabila pengaturan berhasil disimpan.

## 3.x.x View `kuesioner.blade.php`

View `kuesioner.blade.php` merupakan halaman utama pengisian kuesioner tracer study oleh alumni yang didefinisikan pada berkas `resources/views/kuesioner.blade.php`. Halaman ini diteruskan oleh `KuesionerController` dan merupakan halaman dengan struktur terpanjang pada sistem karena memuat seluruh pertanyaan kuesioner.

Halaman ini diawali dengan bagian kepala yang menampilkan judul kuesioner, nama universitas, dan subjudul lembaga yang nilainya diambil secara dinamis dari model `Setting`, sehingga dapat diubah oleh administrator. Seluruh formulir kuesioner dikelompokkan menjadi beberapa bagian, antara lain identitas alumni, status saat ini, kapan mulai mencari pekerjaan, detail tempat bekerja, riwayat studi lanjut dan pembiayaan kuliah, keselarasan bidang studi dengan pekerjaan, kompetensi yang dikuasai dan diperlukan, penekanan metode pembelajaran, cara mencari pekerjaan, proses lamaran kerja, keaktifan mencari kerja, serta alasan mengambil pekerjaan yang tidak sesuai bidang pendidikan. Judul setiap bagian juga diambil secara dinamis dari model `Setting`.

Setiap pertanyaan dilengkapi dengan penanda wajib isi untuk pertanyaan yang bersifat *required*, serta variasi elemen masukan seperti kolom teks, pilihan tunggal (*radio*), dan daftar pilihan (*select*) sesuai dengan jenis pertanyaannya. Apabila terdapat kesalahan validasi, misalnya data alumni tidak terdaftar pada tabel master, pesan kesalahan akan ditampilkan pada bagian atas halaman beserta pesan khusus untuk setiap kolom yang tidak valid. Halaman ini juga memuat skrip *JavaScript* untuk mengatur logika tampilan pertanyaan secara dinamis, misalnya menampilkan atau menyembunyikan bagian tertentu berdasarkan jawaban yang dipilih alumni.

## 3.x.x View `dashboard_kurva.blade.php`

View `dashboard_kurva.blade.php` merupakan halaman *dashboard* analitik hasil tracer study yang didefinisikan pada berkas `resources/views/dashboard_kurva.blade.php`. Halaman ini diteruskan oleh `KuesionerController` bersama dengan seluruh hasil pengolahan data agregasi alumni.

Halaman ini menampilkan berbagai grafik menggunakan pustaka *Chart.js* yang masing-masing dibuat berdasarkan data yang diterima dari *controller*. Grafik tersebut antara lain status kesibukan alumni, distribusi pendapatan per bulan, jenis perusahaan tempat bekerja, sumber dana kuliah, jenis jabatan dan tingkat tempat kerja, sebaran provinsi serta kabupaten/kota wilayah kerja, destinasi kampus studi lanjut, perbandingan kompetensi yang dikuasai dengan kompetensi yang diperlukan, penekanan metode pembelajaran, masa tunggu mendapatkan pekerjaan, saluran mencari pekerjaan, rata-rata rasio proses lamaran kerja, keaktifan mencari kerja, serta alasan mengambil pekerjaan yang tidak sesuai bidang pendidikan.

Bentuk dan warna setiap grafik, judul setiap grafik, serta nama dan warna setiap irisan diambil secara dinamis dari model `Setting`, sehingga administrator dapat mengubahnya melalui halaman pengaturan. Halaman ini juga menyediakan filter berdasarkan tahun lulus dan kode program studi, kartu ringkasan total alumni beserta rincian nama alumni yang dapat dilihat saat kartu diklik, serta daftar nama alumni yang terkait pada setiap irisan grafik ketika irisan tersebut diklik. Selain itu, halaman ini menyediakan tombol untuk mengekspor data hasil tracer study ke dalam berkas Excel.
