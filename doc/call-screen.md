# Call Screen (AI Phone Screening)

Modul screening telepon untuk pelamar, diporting dari CallScreen AI
(Next.js) ke SemartHris (Symfony 4.2). Alur: Kandidat > Panggil >
Ekstrak Bukti > Nilai > Keputusan Recruiter.

## Konsep

- `ScreeningCandidate`: pelamar, bukan karyawan. Punya nama, no HP
  format E.164, posisi, domisili, pengalaman, status, dan jabatan
  tujuan (opsional).
- `Screening`: hasil satu panggilan. Outcome dihitung oleh
  `ScreeningScorer` dari bukti per kriteria, keputusan akhir selalu
  dicatat manual oleh recruiter (`interview`, `backup`, `pass`).
- `CallScreenEngine`: baca mode dari setting `SEMART_CALLSCREEN_MODE`.
  `mock` = simulator lokal. `live` = panggil keluar beneran, wajib
  disambungkan ke worker CALL-E, service ini hanya menandai kandidat
  sebagai `calling`.

## Aturan nilai (deterministik, tanpa skor keyakinan palsu)

- 4/4 kriteria cocok: Qualified.
- 2-3 cocok: Maybe, wajib ditinjau recruiter.
- 0-1 cocok: Not Fit.
- Tanpa bukti: Incomplete.
- Konfirmasi nama dan peran dicatat sebagai bukti, tidak
  menggugurkan kandidat.

## Privasi nomor HP

Daftar dan tabel selalu tampil masker (`+628 .... 7890`) via filter
`semart_phone_mask` atau `PhoneMasker::mask()`. Nomor penuh hanya
muncul di modal konfirmasi sebelum menelepon.

## Endpoint JSON (butuh login admin)

- `GET /screening/confirm?ids[]=` : payload modal konfirmasi.
- `POST /screening/start` `{candidateIds: []}` : mulai batch.
- `POST /screening/{id}/decision` `{decision}` : catat keputusan.
- `GET /screening-candidate/search?q=` : cari kandidat,
  nomor termasker.

## Setup

1. Tambah env dari `.env.dist`: `SEMART_CALLSCREEN_MODE`,
   `SEMART_CALLSCREEN_SOURCE`, `SEMART_CALLSCREEN_INTEGRATION`,
   `SEMART_SECURITY_SCREENING_MENU`.
2. Buat tabel: `php bin/console doctrine:schema:update --force`.
3. Menu Rekrutmen muncul otomatis (EasyAdmin glob `config/admin/*`).
4. Ganti ke live: set `SEMART_CALLSCREEN_MODE=live` dan sambungkan
   worker CALL-E ke `POST /screening/start`.

## Test

`php vendor/bin/phpunit tests/Component/Screening`
