# SemartHris

SemartHris adalah Human Resources Information System (HRIS) yang dapat digunakan untuk membantu memudahkan tugas HRD Perusahaan.

## Tahap Pengembangan

SemartHris belum dapat digunakan untuk produksi dan sedang dalam proses pengembangan.

## Fitur

- [X] Menejemen Perusahaan
- [X] Suport Multi Perusahaan
- [X] Menejemen Jabatan
- [X] Menejemen Shift Kerja
- [X] Menejemen Karyawan
- [X] Support Multi Alamat
- [X] Support Penempatan Karyawan
- [X] Menejemen Kontrak Kerja
- [X] Menejemen Kontrak Perusahaan dengan Rekanan/Klien
- [X] Backend Site and API Sekaligus
- [X] Pelacakan Data (CreatedAt, CreatedBy, UpdatedAt, UpdatedBy dan DeletedAt)

## Cara Install

- [X] Clone/Download Repository `git clone https://github.com/KejawenLab/SemartHris.git` dan pindah ke folder `SemartHris`
- [X] Jalankan [Composer](https://getcomposer.org/download) Install/Update `composer update --prefer-dist -vvv`
- [X] Setup koneksi database pada `.env` menggunakan format URL yaitu: `<PROTOCOL>://<USER>:<PASSWORD>@<HOST>:<PORT>/<DATABASE>`
- [X] Jalankan perintah `php bin/console doctrine:database:crate` untuk membuat database
- [X] Jalankan perintah `php bin/console doctrine:schema:update --force` untuk membuat table yang dibutuhkan
- [X] Jalankan perintah `make serve` untuk mengaktifkan web server
- [X] Buka halaman `<HOST>:<PORT>/admin` untuk halaman admin
- [X] Buka halaman `<HOST>:<PORT>/api` untuk halaman API

## TODO LIST

- [X] Master Alasan Ketidakhadiran dan Cuti
- [X] Master Gelar Pendidikan dan Instansi Pendidikan
- [X] Master Propinsi dan Kota
- [X] Master Keahlian dan Group Keahlian
- [X] Master Perusahaan dan Department
- [X] Master Alamat Perusahaan
- [X] Remove Default From Address When Default Address is Submitted
- [X] Set Random Default When Default Address is Deleted
- [X] Repository yang dibutuhkan untuk Karyawan
- [X] Data Transformer untuk Karyawan
- [X] Improvisasi Form Karyawan
- [X] Form Manipulator untuk Karyawan
- [X] Perancangan Form Edit Karyawan
- [X] Refactor Menu
- [X] Master Jabatan dan Level Jabatan
- [X] Master Shift Kerja
- [X] Create Username Generator Service
- [X] Implement User Model and Repository
- [X] Add Listener to Generate Username, Role and Applying Default Password
- [X] Improvement Company Address
- [X] Improvement Company Employee
- [X] Contract Management
- [X] Soft Delete and Blameable
- [X] Change Password
- [X] Change Role
- [ ] Create Job Allocation
- [ ] Create Job History
- [ ] Create Job Promotion
- [ ] Create Job Mutation
- [ ] Menejemen Absensi
- [ ] Login Page
- [ ] Delete Session Notification

## Kontributor

Proyek ini dikembangkan oleh [Muhamad Surya Iksanudin](https://github.com/ad3n) dan para [kontributor](https://github.com/KejawenLab/SemartHris/graphs/contributors)
untuk [KejawenLab](https://github.com/KejawenLab)

## Lisensi

Proyek ini menggunakan lisensi [Apache License 2.0 (Apache-2.0)](https://tldrlegal.com/license/apache-license-2.0-(apache-2.0)). 
Pastikan Anda memahami kewajiban dan hak Anda sebelum Anda memutuskan untuk menggunakan software ini.

## Preview

![SemartHris Preview](preview.png)

![SemartHris API Preview](api-preview.png)
