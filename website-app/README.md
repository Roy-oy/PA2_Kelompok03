# Sistem Informasi Puskesmas

Aplikasi Sistem Informasi Puskesmas adalah sistem manajemen modern untuk pusat kesehatan masyarakat yang membantu pengelolaan pasien, jadwal dokter, dan administrasi puskesmas.

## Fitur Utama

-   🔐 **Autentikasi Multi-level**: Sistem login yang aman dengan peran berbeda (admin, dokter, perawat, staf)
-   👥 **Manajemen Pengguna**: Pengelolaan akun staf puskesmas dengan hak akses berbeda
-   🏥 **Manajemen Pasien**: Pencatatan data pasien, riwayat kesehatan, dan kunjungan
-   📅 **Penjadwalan**: Manajemen jadwal dokter dan janji temu pasien
-   💊 **Farmasi**: Pengelolaan inventaris obat dan resep
-   📊 **Laporan**: Statistik kunjungan pasien, layanan, dan kinerja puskesmas
-   📱 **Responsif**: Antarmuka yang dapat diakses dari berbagai perangkat
-   🎨 **Tema Puskesmas**: Desain dengan warna hijau yang elegan dan profesional
-   ✨ **UI Modern**: Tampilan login yang elegan, simple dan modern

## Teknologi

-   **Framework**: Laravel 10
-   **Database**: MySQL
-   **Frontend**: Bootstrap 5, jQuery
-   **Icons**: Font Awesome 6

## Persyaratan Sistem

-   PHP >= 8.1
-   Composer
-   MySQL atau MariaDB
-   Node.js & NPM (untuk kompilasi aset)

## Instalasi

1. Clone repositori ini:

    ```
    git clone https://github.com/username/sistem-informasi-puskesmas.git
    ```

2. Pindah ke direktori proyek:

    ```
    cd sistem-informasi-puskesmas
    ```

3. Instal dependensi PHP:

    ```
    composer install
    ```

4. Salin file .env.example menjadi .env:

    ```
    cp .env.example .env
    ```

5. Konfigurasi file .env dengan detail database Anda

6. Generate kunci aplikasi:

    ```
    php artisan key:generate
    ```

7. Jalankan migrasi database:

    ```
    php artisan migrate
    ```

8. (Opsional) Jalankan seeder untuk data awal:

    ```
    php artisan db:seed
    ```

9. Buat folder dan tambahkan logo:

    ```
    mkdir -p public/images
    ```

    Tambahkan logo puskesmas ke folder public/images dengan nama puskesmas-logo.png

10. Jalankan server development:

    ```
    php artisan serve
    ```

11. Akses aplikasi di browser: `http://localhost:8000`

## Login Default

-   Admin:

    -   Email: admin@puskesmas.com
    -   Password: admin123

-   Dokter:

    -   Email: dokter@puskesmas.com
    -   Password: dokter123

-   Perawat:

    -   Email: perawat@puskesmas.com
    -   Password: perawat123

-   Staf:
    -   Email: staf@puskesmas.com
    -   Password: staf123

## Tampilan UI

Aplikasi menggunakan desain UI yang modern dan elegan:

-   **Login**: Tampilan login yang simple, elegan dan modern dengan warna yang tidak mencolok
-   **Dashboard**: Tampilan dashboard yang profesional dengan navigasi yang intuitif
-   **Responsif**: Tampilan yang menyesuaikan dengan berbagai ukuran layar

## Tema Aplikasi

Aplikasi menggunakan tema hijau sesuai dengan identitas Puskesmas dengan variabel warna:

-   Hijau tua: #15803d (Primary)
-   Hijau lebih terang: #22c55e (Secondary)
-   Hijau sangat tua: #166534 (Dark)
-   Hijau sangat terang: #dcfce7 (Light)

## Pengembangan

Untuk pengembangan frontend, jalankan:

```
npm install
npm run dev
```

## Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau laporkan masalah melalui issues.

## Lisensi

Sistem Informasi Puskesmas adalah perangkat lunak open-source yang dilisensikan di bawah [MIT license](LICENSE).
