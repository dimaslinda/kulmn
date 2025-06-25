# Kulmn Barbershop Website

Ini adalah situs web resmi untuk Kulmn Barbershop, dibangun dengan Laravel.

## Fitur

-   **Desain Modern**: Antarmuka pengguna yang bersih dan responsif.
-   **SEO Optimized**: Mengimplementasikan tag meta Schema.org dan Open Graph untuk visibilitas mesin pencari yang lebih baik dan berbagi di media sosial.
-   **Pencari Lokasi**: Membantu pengguna menemukan lokasi Kulmn Barbershop terdekat.
-   **Profil Pemilik**: Informasi tentang pemilik dan keahlian mereka.
-   **Program Kemitraan**: Detail tentang cara bergabung sebagai mitra Kulmn.
-   **Pelatihan Tukang Cukur Profesional**: Informasi tentang menjadi tukang cukur profesional dengan Kulmn.

## Teknologi yang Digunakan

-   **Laravel**: Framework PHP untuk pengembangan web.
-   **Composer**: Manajer dependensi untuk PHP.
-   **Node.js & npm**: Lingkungan runtime JavaScript dan manajer paket.
-   **Vite**: Tool untuk kompilasi aset frontend.
-   **MySQL**: Sistem manajemen basis data relasional.

## Installation

To set up the project locally, follow these steps:

1.  **Clone the repository:**

    ```bash
    git clone <repository_url>
    cd kulmn
    ```

2.  **Install Composer dependencies:**

    ```bash
    composer install
    ```

3.  **Install Node.js dependencies:**

    ```bash
    npm install
    ```

4.  **Copy the environment file:**

    ```bash
    cp .env.example .env
    ```

5.  **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

6.  **Configure your database** in the `.env` file.

7.  **Run database migrations:**

    ```bash
    php artisan migrate
    ```

8.  **Seed the database (optional):**

    ```bash
    php artisan db:seed
    ```

9.  **Run Vite for asset compilation:**

    ```bash
    npm run dev
    ```

10. **Start the Laravel development server:**

    ```bash
    php artisan serve
    ```

    The application will be available at `http://127.0.0.1:8000` (or similar).

## Penggunaan

-   **Halaman Beranda**: Menyediakan gambaran umum tentang Kulmn Barbershop, layanan, dan lokasinya.
-   **Halaman Mitra**: Informasi untuk calon mitra.
-   **Halaman Akademi**: Detail tentang program pelatihan tukang cukur.

## Kontribusi

Situs Web Kulmn Barbershop adalah perangkat lunak open-source yang dilisensikan di bawah [lisensi MIT](https://opensource.org/licenses/MIT).

## Kontak

Untuk pertanyaan atau dukungan, silakan hubungi kami di:

-   Email: info@kulmnbarbershop.com
-   Website: [kulmnbarbershop.com](http://kulmnbarbershop.com)
