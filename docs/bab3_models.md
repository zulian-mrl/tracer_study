# Bab 3 — Pendefinisian Model (app/Models)

## 3.x.x Model `User`

Model `User` merupakan representasi dari data pengguna sistem yang didefinisikan pada berkas `app/Models/User.php`. Berbeda dengan model lainnya, model `User` tidak diturunkan langsung dari `Eloquent Model`, melainkan dari kelas `Illuminate\Foundation\Auth\User` (*Authenticatable*), sehingga model ini mewarisi seluruh fungsionalitas otentikasi bawaan Laravel, seperti penyimpanan *password* terenkripsi dan penggunaan *token* untuk mengingat sesi login.

Model `User` berasosiasi dengan tabel `users` pada *database*. Atribut yang diperbolehkan untuk diisi secara massal terdiri atas kolom `name` (nama pengguna), `email` (alamat surel), `password` (kata sandi), `is_super` (penanda status sebagai *super admin*), dan `foto` (berkas foto pengguna). Sementara itu, atribut `password` dan `remember_token` dikecualikan (*hidden*) dari hasil serialisasi agar tidak terbaca oleh proses yang membutuhkan konversi ke JSON, misalnya pada halaman *frontend*.

Pada bagian *casting*, atribut `password` dikonversi secara otomatis menjadi nilai terenkripsi (*hashed*) setiap kali dilakukan penyimpanan, dan atribut `email_verified_at` diperlakukan sebagai tipe `datetime`. Selain itu, model `User` menggunakan *trait* `HasFactory` untuk mendukung pembuatan data uji serta `Notifiable` untuk mendukung pengiriman notifikasi, misalnya saat proses pemulihan kata sandi.

## 3.x.x Model `Response`

Model `Response` merupakan representasi dari data jawaban pengguna terhadap setiap pertanyaan kuesioner yang didefinisikan pada berkas `app/Models/Response.php`. Model ini diturunkan dari kelas `Illuminate\Database\Eloquent\Model` dan berasosiasi dengan tabel `responses` pada *database*.

Atribut yang diperbolehkan untuk diisi secara massal terdiri atas kolom `user_id` sebagai kunci asing yang menunjuk pengguna (alumni) yang mengisi jawaban, `question_id` sebagai kunci asing yang menunjuk pertanyaan yang dijawab, serta `answer` sebagai isi jawaban yang diberikan. Dengan perancangan ini, setiap jawaban yang dikirimkan oleh pengguna tersimpan sebagai satu baris data pada tabel `responses`, sehingga sistem dapat mengelompokkan jawaban berdasarkan pengguna maupun berdasarkan pertanyaan untuk keperluan analisis hasil tracer study.

## 3.x.x Model `Question`

Model `Question` merupakan representasi dari data pertanyaan kuesioner yang didefinisikan pada berkas `app/Models/Question.php`. Model ini diturunkan dari kelas `Illuminate\Database\Eloquent\Model` dan berasosiasi dengan tabel `questions`.

Melalui struktur tabelnya, setiap pertanyaan memiliki atribut `question_text` sebagai teks pertanyaan, `type` sebagai jenis pertanyaan yang dapat berupa `text`, `radio`, atau `select`, serta `is_required` sebagai penanda apakah pertanyaan wajib diisi atau tidak. Model ini menjadi dasar bagi sistem dalam membangun halaman kuesioner secara dinamis, di mana daftar pertanyaan ditampilkan sesuai dengan data yang tersimpan pada tabel `questions`. Adapun penambahan kolom *fillable* tidak dibutuhkan karena data pertanyaan dikelola langsung oleh sistem melalui proses migrasi *database*, bukan melalui proses pengisian massal dari pengguna.

## 3.x.x Model `QuestionOption`

Model `QuestionOption` merupakan representasi dari data pilihan jawaban pada setiap pertanyaan yang didefinisikan pada berkas `app/Models/QuestionOption.php`. Model ini diturunkan dari kelas `Illuminate\Database\Eloquent\Model` dan berasosiasi dengan tabel `question_options`.

Struktur tabelnya terdiri atas kolom `question_id` sebagai kunci asing yang menghubungkan pilihan jawaban dengan pertanyaan terkait, serta `option_text` sebagai teks dari pilihan jawaban. Hubungan antara tabel `question_options` dengan tabel `questions` dirancang dengan ketentuan *cascade delete*, sehingga apabila suatu pertanyaan dihapus maka seluruh pilihan jawaban yang terkait akan ikut terhapus secara otomatis. Model ini memungkinkan sistem menampilkan pilihan jawaban secara dinamis untuk pertanyaan berjenis `radio` atau `select`.

## 3.x.x Model `MasterAlumni`

Model `MasterAlumni` merupakan representasi dari data master alumni yang didefinisikan pada berkas `app/Models/MasterAlumni.php`. Model ini diturunkan dari kelas `Illuminate\Database\Eloquent\Model`, menggunakan *trait* `HasFactory`, serta secara eksplisit menetapkan nama tabel `master_alumnis` pada *database*.

Atribut yang diperbolehkan untuk diisi secara massal terdiri atas kolom `no_mahasiswa` sebagai nomor induk mahasiswa yang bersifat unik dan menjadi kunci utama pencarian, `kode_prodi` sebagai kode program studi, `nama` sebagai nama lengkap alumni, `nik` sebagai nomor induk kependudukan yang bersifat unik, serta `tahun_lulus` sebagai tahun kelulusan alumni. Pendefinisian kolom *fillable* pada model ini berfungsi untuk mendukung proses impor data alumni secara massal melalui berkas *Excel*, sehingga data yang dibaca dari berkas tersebut dapat langsung disimpan ke dalam tabel `master_alumnis`.

## 3.x.x Model `Setting`

Model `Setting` merupakan salah satu model pada sistem yang berperan sebagai pengelola data pengaturan (*configuration*) aplikasi secara terpusat. Model ini didefinisikan pada berkas `app/Models/Setting.php` dengan menggunakan *framework* Laravel, yaitu kelas yang diturunkan dari kelas `Illuminate\Database\Eloquent\Model`.

Model `Setting` berasosiasi dengan tabel `settings` pada *database*, sehingga seluruh data pengaturan sistem tersimpan dalam bentuk baris pada tabel tersebut. Adapun atribut yang diperbolehkan untuk diisi (kolom *fillable*) hanya terdiri dari dua kolom, yaitu `key` sebagai kunci pengaturan dan `value` sebagai nilai dari pengaturan tersebut. Pembatasan ini bertujuan untuk mencegah pengisian data secara massal pada kolom lain yang tidak seharusnya diubah.

Dalam implementasinya, model `Setting` dilengkapi dengan beberapa metode statis yang mendukung fungsionalitas pengelolaan pengaturan, yaitu:

a) Metode `defaults()`, yaitu metode yang mengembalikan kumpulan nilai *default* dari seluruh pengaturan sistem dalam bentuk array. Nilai *default* tersebut mencakup pengaturan kuesioner (seperti judul kuesioner, nama universitas, dan instruksi pengisian), pengaturan *dashboard* (judul halaman dan warna aksen), serta pengaturan bentuk dan warna grafik yang digunakan pada halaman analitik.

b) Metode `itemDefaults()`, yaitu metode yang mengembalikan nilai *default* berupa nama dan warna dari setiap irisan atau seri pada grafik, misalnya kategori status alumni, rentang pendapatan, jenis perusahaan, sumber dana kuliah, dan lain sebagainya.

c) Metode `items($slug)`, yaitu metode yang digunakan untuk mengambil kumpulan item (nama dan warna) dari suatu grafik berdasarkan kunci atau *slug* tertentu yang dikirim sebagai parameter.

d) Metode `allCached()`, yaitu metode yang mengambil seluruh data pengaturan dari tabel `settings` dalam bentuk array yang dipetakan berdasarkan kolom `key` sebagai kunci dan `value` sebagai nilai. Data hasil pemanggilan ini disimpan pada variabel statis sebagai *cache* sehingga tidak perlu dilakukan kueri ulang ke *database* selama proses berjalan.

e) Metode `get($key, $default)`, yaitu metode yang digunakan untuk membaca nilai suatu pengaturan berdasarkan kunci. Apabila nilai tidak ditemukan pada *database*, maka sistem akan mengambil nilai *default* yang telah didefinisikan, dan apabila nilai *default* tidak tersedia maka akan dikembalikan nilai pengganti yang dikirim sebagai parameter.

f) Metode `set($key, $value)`, yaitu metode yang digunakan untuk menyimpan atau memperbarui nilai suatu pengaturan dengan mekanisme *updateOrCreate*, sehingga data lama akan diperbarui apabila kunci sudah ada dan akan membuat baris baru apabila kunci belum tersedia. Setelah penyimpanan, *cache* akan dikosongkan agar data terbaru dapat diambil pada pemanggilan berikutnya.

g) Metode `forget($key)`, yaitu metode yang digunakan untuk menghapus suatu pengaturan berdasarkan kunci yang diberikan.

h) Metode `syncDefaults()`, yaitu metode yang digunakan untuk menyinkronkan seluruh nilai *default* ke dalam tabel `settings`. Metode ini akan membuat baris baru apabila kunci belum ada di dalam tabel, sehingga seluruh pengaturan *default* dijamin tersedia di dalam *database*.

Dengan perancangan model tersebut, seluruh pengaturan sistem dapat diakses dan dikelola secara terpusat melalui satu model, sehingga memudahkan proses pemeliharaan sistem tanpa harus mengubah kode program secara langsung untuk setiap perubahan pengaturan.
