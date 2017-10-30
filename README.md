# SemartHris

SemartHris adalah Human Resources Information System (HRIS) yang dapat digunakan untuk membantu memudahkan tugas HRD Perusahaan.

## Tahap Pengembangan

SemartHris belum dapat digunakan untuk produksi dan sedang dalam proses pengembangan.

## Minimum Requirement

- [X] PHP versi 7.1.7
- [X] RDBMS (MySQL, MariaDB, PostgreSQL atau lainnya) yang disupport oleh [Doctrine](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/platforms.html)
- [X] Web Server (Apache, Nginx atau IIS)
- [X] APCu extension (untuk Production)

## Fitur

- [X] Manajemen Perusahaan
- [X] Support Multi Perusahaan
- [X] Manajemen Jabatan
- [X] Manajemen Karyawan
- [X] Support Multi Alamat
- [X] Support Penempatan Karyawan
- [X] Manajemen Kontrak Kerja
- [X] Manajemen Kontrak Perusahaan dengan Rekanan/Klien
- [X] Karir History
- [X] Promosi, Mutasi, dan Demosi
- [X] Manajemen Shift Kerja
- [X] Manajemen Jadwal Kerja
- [X] Manajemen Absensi
- [X] Manajemen Hari Libur
- [X] Manajemen Lembur sesuai dengan [peraturan yang berlaku](https://gajimu.com/main/pekerjaan-yanglayak/kompensasi/upah-lembur)
- [X] Backend Site and API sekaligus
- [X] Soft Delete (Data tidak benar-benar dihapus)
- [X] Pelacakan Data (CreatedAt, CreatedBy, UpdatedAt, UpdatedBy, dan DeletedAt)

## Cara Install

- [X] Clone/Download repository `git clone https://github.com/KejawenLab/SemartHris.git` dan pindah ke folder `SemartHris`
- [X] Jalankan [Composer](https://getcomposer.org/download) Install/Update `composer update --prefer-dist -vvv`
- [X] Setup koneksi database pada `.env`
```bash
  SEMART_DB_DRIVER="mysql"
  SEMART_DB_USER="root"
  SEMART_DB_PASSWORD="password"
  SEMART_DB_HOST="127.0.0.1"
  SEMART_DB_PORT="3306"
  SEMART_DB_NAME="semarthris"
```
- [X] Jalankan perintah `php bin/console doctrine:database:create` untuk membuat database
- [X] Jalankan perintah `php bin/console doctrine:schema:update --force` untuk membuat table yang dibutuhkan
- [X] Jalankan perintah `make serve` untuk mengaktifkan web server
- [X] Buka halaman `<HOST>:<PORT>/admin` untuk halaman admin
- [X] Buka halaman `<HOST>:<PORT>/api` untuk halaman API

## Kontributor

Proyek ini dikembangkan oleh [Muhamad Surya Iksanudin](https://github.com/ad3n) dan para [kontributor](https://github.com/KejawenLab/SemartHris/graphs/contributors)
untuk [KejawenLab](https://github.com/KejawenLab).

## Lisensi

Proyek ini menggunakan lisensi [Apache License 2.0 (Apache-2.0)](https://tldrlegal.com/license/apache-license-2.0-(apache-2.0)) &copy; Muhamad Surya Iksanudin.
Pastikan Anda memahami kewajiban dan hak Anda sebelum Anda memutuskan untuk menggunakan software ini.

## Preview

![SemartHris Preview](preview.png)

![SemartHris API Preview](api-preview.png)
