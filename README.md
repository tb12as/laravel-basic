# Demo Laravel: CRUD Produk

Proyek ini adalah aplikasi Laravel sederhana untuk mengelola data produk (nama, harga, dan stok). Proyek ini ditujukan
bagi mahasiswa yang sedang belajar Laravel, sehingga setiap bagian sengaja dibuat sekecil mungkin agar mudah ditelusuri.

Aplikasi ini memiliki dua versi antarmuka:

1. Versi web biasa: halaman dirender di server menggunakan Blade, dan setiap aksi mengirim form lalu memuat ulang
   halaman.
2. Versi Fetch API: satu halaman yang mengambil dan mengirim data ke endpoint JSON menggunakan JavaScript `fetch()`.

Kedua versi memakai model dan tabel database yang sama, sehingga Anda dapat membandingkan cara kerjanya secara langsung.

## Prasyarat

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm (dipakai oleh skrip `setup` untuk membangun aset frontend)

Database yang dipakai adalah SQLite (`DB_CONNECTION=sqlite` di `.env.example`), sehingga Anda tidak perlu memasang
MySQL atau PostgreSQL.

## Menjalankan Proyek

```bash
# instal dependensi, salin .env, buat app key, jalankan migrasi, dan build aset
composer run setup

# isi tabel products dengan 10 data contoh
php artisan db:seed

# jalankan server pengembangan
composer run dev
```

Setelah server berjalan, buka alamat yang tampil di terminal. Halaman `/` akan langsung mengarahkan Anda ke `/products`.

Jika ingin mengosongkan database dan mengisinya ulang dari awal, jalankan:

```bash
php artisan migrate:fresh --seed
```

## Alur Sebuah Request

Hampir semua fitur di Laravel mengikuti alur yang sama. Pahami alur ini terlebih dahulu sebelum membaca kode:

```text
Browser
  -> Route        (routes/web.php)       menentukan URL mana ditangani oleh method mana
  -> Controller   (ProductController)    memvalidasi input dan menjalankan logika
  -> Model        (Product)              membaca atau menulis ke tabel products
  -> View         (resources/views)      menghasilkan HTML yang dikirim kembali ke browser
```

Untuk versi API, langkah terakhir bukan View, melainkan respons JSON.

## File yang Perlu Dibaca

| File | Isi |
| --- | --- |
| `routes/web.php` | Definisi route untuk halaman web |
| `routes/api.php` | Definisi route untuk endpoint JSON (otomatis diawali `/api`) |
| `app/Http/Controllers/ProductController.php` | Controller untuk versi web (CRUD lengkap) |
| `app/Http/Controllers/Api/ProductController.php` | Controller untuk versi API (daftar, detail, tambah) |
| `app/Models/Product.php` | Model Eloquent untuk tabel `products` |
| `database/migrations/2026_11_17_033157_create_products_table.php` | Struktur tabel `products` |
| `database/factories/ProductFactory.php` | Aturan pembuatan data palsu untuk produk |
| `database/seeders/ProductSeeder.php` | Membuat 10 produk menggunakan factory |
| `resources/views/layouts/app.blade.php` | Layout utama yang dipakai semua halaman |
| `resources/views/products/` | Halaman daftar, detail, tambah, edit, dan versi fetch |

Sebaiknya baca file-file tersebut sesuai urutan bagian di bawah ini.

## Konsep Dasar

### 1. Migration: Membuat Struktur Tabel

Migration adalah file PHP yang mendeskripsikan struktur tabel. Dengan migration, struktur database tersimpan di Git
dan dapat dibuat ulang oleh siapa pun dengan satu perintah.

Migration produk membuat tabel `products` dengan kolom berikut:

```php
$table->id();                       // primary key, auto increment
$table->string('name');
$table->unsignedInteger('price');   // bilangan bulat, tidak boleh negatif
$table->unsignedInteger('stock');
$table->timestamps();               // kolom created_at dan updated_at
```

Method `up()` dijalankan oleh `php artisan migrate`, sedangkan `down()` dijalankan saat migrasi dibatalkan
(`php artisan migrate:rollback`).

### 2. Model: Mewakili Tabel di Kode PHP

`app/Models/Product.php` adalah model Eloquent. Laravel menghubungkan model `Product` dengan tabel `products` secara
otomatis berdasarkan nama (bentuk jamak dari nama model).

```php
#[UseFactory(ProductFactory::class)]
#[Fillable('name', 'price', 'stock')]
class Product extends Model
```

Atribut `#[Fillable]` menentukan kolom mana yang boleh diisi secara massal, misalnya melalui `Product::create([...])`
atau `$product->update([...])`. Kolom di luar daftar ini akan diabaikan. Fungsinya sama dengan properti
`protected $fillable` yang masih ditulis sebagai komentar di file tersebut, hanya saja ditulis dalam bentuk atribut PHP.

Contoh pemakaian model di proyek ini:

```php
Product::orderByDesc('id')->get();   // ambil semua produk, terbaru di atas
Product::create($data);              // simpan produk baru
$product->update($fields);           // ubah produk
$product->delete();                  // hapus produk
```

### 3. Factory dan Seeder: Mengisi Data Contoh

Factory mendefinisikan cara membuat satu data palsu. `ProductFactory` menghasilkan nama acak, stok antara 100 sampai
2000, dan harga kelipatan 100.000.

Seeder memakai factory tersebut untuk mengisi tabel:

```php
Product::factory()->count(10)->create();
```

`DatabaseSeeder` memanggil `ProductSeeder`, sehingga perintah `php artisan db:seed` akan membuat 10 produk.

### 4. Routing

Route menghubungkan URL dengan method di controller. Di `routes/web.php`, satu baris berikut membuat tujuh route
sekaligus:

```php
Route::resource('products', ProductController::class);
```

Hasilnya (dapat Anda lihat sendiri dengan `php artisan route:list`):

| Method HTTP | URL | Nama Route | Method Controller |
| --- | --- | --- | --- |
| GET | `/products` | `products.index` | `index` |
| GET | `/products/create` | `products.create` | `create` |
| POST | `/products` | `products.store` | `store` |
| GET | `/products/{product}` | `products.show` | `show` |
| GET | `/products/{product}/edit` | `products.edit` | `edit` |
| PUT/PATCH | `/products/{product}` | `products.update` | `update` |
| DELETE | `/products/{product}` | `products.destroy` | `destroy` |

Nama route dipakai di view dan controller melalui helper `route()`, misalnya `route('products.edit', $product)`.
Dengan cara ini, URL dapat diubah di satu tempat tanpa harus mengubah semua link.

Catatan: komentar di `routes/web.php` yang menjabarkan route di atas memiliki beberapa kesalahan kecil (lihat bagian
Latihan). Gunakan tabel ini atau output `php artisan route:list` sebagai acuan.

Ada juga route yang langsung menampilkan view tanpa controller:

```php
Route::view('/products-fetch', 'products.fetch')->name('products.fetch');
```

### 5. Route Model Binding

Perhatikan parameter method berikut:

```php
public function show(Product $product)
```

Karena URL-nya `/products/{product}` dan parameternya bertipe `Product`, Laravel otomatis mencari produk berdasarkan
`id` di URL. Jika produk tidak ditemukan, Laravel mengembalikan halaman 404. Anda tidak perlu menulis
`Product::findOrFail($id)` secara manual.

### 6. Controller dan Validasi

`ProductController` adalah resource controller: setiap method sesuai dengan satu route di tabel di atas.

Sebelum data disimpan, input divalidasi:

```php
$request->validate([
    'name' => 'required|max:255',
    'price' => 'required|numeric|min:1',
    'stock' => 'required|numeric|min:1',
]);
```

Jika validasi gagal, Laravel otomatis mengembalikan pengguna ke halaman form beserta pesan error dan input sebelumnya.
Jika berhasil, `validate()` mengembalikan array berisi field yang lolos validasi, seperti yang dipakai di method
`update()`.

Setelah data disimpan, controller mengarahkan pengguna kembali ke daftar produk dengan pesan sukses:

```php
return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
```

Pesan dari `with()` disebut flash message: pesan ini hanya tersedia pada request berikutnya, lalu hilang.

Method `store()` juga menambahkan PPN 11% ke harga sebelum disimpan. Ini adalah contoh logika bisnis yang ditaruh di
controller.

### 7. Blade: Template View

Semua halaman produk memakai layout yang sama melalui `@extends`:

```blade
@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
    ...
@endsection
```

Di `layouts/app.blade.php`, bagian tersebut ditampilkan dengan `@yield('title')` dan `@yield('content')`. Layout ini
juga menampilkan flash message `session('success')` dan memuat CSS Bootstrap dari CDN.

Beberapa fitur Blade lain yang dipakai di proyek ini:

| Sintaks | Kegunaan | Contoh di |
| --- | --- | --- |
| `{{ $var }}` | Menampilkan data dengan escape HTML otomatis | Semua view |
| `@forelse ... @empty` | Perulangan dengan tampilan khusus jika data kosong | `index.blade.php` |
| `$loop->iteration` | Nomor urut perulangan, dimulai dari 1 | `index.blade.php` |
| `@csrf` | Token keamanan yang wajib ada di setiap form POST | `create`, `edit`, `index` |
| `@method('PUT')` | Form HTML hanya mendukung GET dan POST, jadi PUT/DELETE disimulasikan | `edit`, `index` |
| `old('name')` | Mengisi ulang input setelah validasi gagal | `create`, `edit` |
| `$errors` | Daftar pesan error validasi | `create`, `edit` |

### 8. API dan Fetch

Route di `routes/api.php` otomatis diawali `/api`:

```php
Route::apiResource('products', ProductController::class)
    ->only(['index', 'show', 'store'])->names('api.products');
```

| Method HTTP | URL | Kegunaan |
| --- | --- | --- |
| GET | `/api/products` | Daftar semua produk |
| GET | `/api/products/{product}` | Detail satu produk |
| POST | `/api/products` | Tambah produk baru |

Controller API (`app/Http/Controllers/Api/ProductController.php`) mengembalikan JSON, bukan view:

```php
return response()->json(Product::create($fields), 201);
```

Kode status `201` berarti data berhasil dibuat. Method `show()` cukup mengembalikan `$product`, karena Laravel otomatis
mengubah model menjadi JSON.

Jika validasi gagal pada request API, Laravel tidak melakukan redirect, melainkan mengembalikan status `422` beserta
daftar error dalam format JSON. Pengaturan ini ada di `bootstrap/app.php`.

Halaman `/products-fetch` (`resources/views/products/fetch.blade.php`) memakai endpoint tersebut dengan JavaScript:

- `load()` memanggil `GET /api/products` lalu mengisi tabel tanpa memuat ulang halaman.
- Saat form dikirim, data dikirim ke `POST /api/products` dalam format JSON.
- Jika respons berstatus `422`, pesan error ditampilkan di atas form.

Coba buka tab Network di DevTools browser saat memakai halaman ini untuk melihat request dan respons JSON-nya.

## Perintah Artisan yang Sering Dipakai

```bash
php artisan route:list                       # lihat semua route
php artisan migrate                          # jalankan migrasi yang belum dijalankan
php artisan migrate:fresh --seed             # hapus semua tabel, migrasi ulang, lalu isi data contoh
php artisan db:seed                          # jalankan seeder
php artisan tinker                           # konsol interaktif, misalnya: Product::count()
php artisan make:model Category -mfs         # buat model beserta migration, factory, dan seeder
php artisan make:controller CategoryController --resource
```

## Latihan

Latihan berikut berangkat dari kondisi kode saat ini:

1. PPN 11% hanya ditambahkan di `store()` versi web. Method `update()` dan `store()` versi API tidak menambahkannya,
   sehingga harga produk bisa berbeda tergantung cara data disimpan. Pindahkan perhitungan PPN ke satu tempat agar
   konsisten.
2. Tambahkan method `update` dan `destroy` ke controller API, lalu tambahkan tombol edit dan hapus di halaman fetch.
3. Tambahkan kolom pencarian berdasarkan nama di halaman daftar produk.
4. Buat fitur kategori produk, lalu hubungkan dengan produk menggunakan relasi `belongsTo` dan `hasMany`.

## Referensi

- [Dokumentasi Laravel](https://laravel.com/docs)
- [Routing](https://laravel.com/docs/routing)
- [Controller](https://laravel.com/docs/controllers)
- [Blade](https://laravel.com/docs/blade)
- [Validasi](https://laravel.com/docs/validation)
- [Eloquent](https://laravel.com/docs/eloquent)
- [Migration](https://laravel.com/docs/migrations)
- [Laravel Learn](https://laravel.com/learn)
