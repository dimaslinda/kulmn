# Kulmn Barbershop Website

Ini adalah situs web resmi untuk Kulmn Barbershop, dibangun dengan Laravel.

## Fitur Utama

-   **Desain Modern**: Antarmuka pengguna yang bersih dan responsif.
-   **SEO Optimized**: Mengimplementasikan tag meta Schema.org dan Open Graph untuk visibilitas mesin pencari yang lebih baik dan berbagi di media sosial.
-   **Pencari Lokasi**: Membantu pengguna menemukan lokasi Kulmn Barbershop terdekat.
-   **Profil Pemilik**: Informasi tentang pemilik dan keahlian mereka.
-   **Program Kemitraan**: Detail tentang cara bergabung sebagai mitra Kulmn.
-   **Pelatihan Tukang Cukur Profesional**: Informasi tentang menjadi tukang cukur profesional dengan Kulmn.

## Teknologi yang Digunakan

-   **Backend**: Laravel 10.x
-   **Frontend**: Blade Templates, Tailwind CSS
-   **Database**: MySQL
-   **Manajemen Paket**: Composer (PHP) & Node.js/npm (JavaScript)
-   **Kompilasi Aset**: Vite

## Prasyarat

Sebelum menjalankan proyek ini, pastikan sistem Anda memiliki:

-   PHP >= 8.1
-   Composer
-   Node.js & npm
-   MySQL
-   Web server (Apache/Nginx)

## Instalasi

Untuk mengatur proyek secara lokal, ikuti langkah-langkah berikut:

1.  **Clone repositori:**

    ```bash
    git clone <repository_url>
    cd kulmn
    ```

2.  **Instal dependensi Composer:**

    ```bash
    composer install
    ```

3.  **Instal dependensi Node.js:**

    ```bash
    npm install
    ```

4.  **Salin file lingkungan:**

    ```bash
    cp .env.example .env
    ```

5.  **Buat kunci aplikasi:**

    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi database Anda** di file `.env`.

7.  **Jalankan migrasi database:**

    ```bash
    php artisan migrate
    ```

8.  **Seed database (opsional):**

    ```bash
    php artisan db:seed
    ```

9.  **Jalankan Vite untuk kompilasi aset:**

    ```bash
    npm run dev
    ```

10. **Mulai server pengembangan Laravel:**

    ```bash
    php artisan serve
    ```

    Aplikasi akan tersedia di `http://127.0.0.1:8000` (atau serupa).

## Penggunaan

-   **Halaman Beranda**: Menyediakan gambaran umum tentang Kulmn Barbershop, layanan, dan lokasinya.
-   **Halaman Mitra**: Informasi untuk calon mitra.
-   **Halaman Akademi**: Detail tentang program pelatihan tukang cukur.

## Struktur Proyek

```
kulmn/
├── app/
│   ├── Filament/           # Sumber daya panel admin
│   ├── Http/Controllers/   # Pengontrol
│   ├── Models/            # Model Eloquent
│   └── Services/          # Logika bisnis
├── database/
│   ├── migrations/        # Migrasi database
│   └── seeders/          # Seeder database
├── public/
│   └── img/              # Gambar statis
├── resources/
│   └── views/            # Template Blade
└── routes/
    └── web.php           # Rute web
```

## Kontribusi

Kontribusi sangat diterima! Silakan fork repositori dan kirimkan pull request.

## Lisensi

Proyek ini dilisensikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).

## Dukungan

Untuk pertanyaan atau dukungan, silakan hubungi kami di:

-   Email: deniasitudimas@gmail.com
