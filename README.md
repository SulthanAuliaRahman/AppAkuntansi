# Sistem Akuntansi Interaktif — Perusahaan Jasa "Anugerah Sakti"

Aplikasi web siklus akuntansi lengkap untuk studi kasus Perusahaan Jasa "Anugerah Sakti" periode April 2008. Dibangun dengan **Laravel 13** (MVC), **MySQL**, dan **Tailwind CSS**.

> Tugas Besar — Kelompok 2 | Mata Kuliah Pengantar Akuntansi

---

## Fitur Utama

| Menu | Deskripsi |
|---|---|
| Dashboard | KPI ringkasan keuangan + 3 grafik Chart.js |
| Jurnal Umum | Input/hapus transaksi, tabel kronologis April 2008 |
| Buku Besar | Mutasi saldo per akun (dropdown 19 akun) |
| Neraca Saldo | Trial balance sebelum penyesuaian |
| Jurnal Penyesuaian | 6 AJE dengan toggle aktif/nonaktif |
| Kertas Kerja | Neraca lajur 10 kolom (NSD, L/R, Neraca) |
| Laporan Keuangan | Laba Rugi, Perubahan Modal, Neraca |
| Jurnal Penutup | Penutupan 4 ayat ke Modal |

---

## Persyaratan Sistem

- **PHP** >= 8.2
- **Composer** >= 2.x
- **MySQL** >= 5.7 (atau MariaDB >= 10.3)
- **XAMPP** / **Laragon** / server lokal sejenis

---

## Cara Menjalankan

### 1. Clone / Extract Project

```bash
git clone <url-repo>
cd AppAkuntansi
```

### 2. Install Dependensi PHP

```bash
composer install --ignore-platform-reqs
```

> Flag `--ignore-platform-reqs` diperlukan jika versi PHP lokal adalah 8.2 (project dikembangkan di PHP 8.2, lock file mungkin menyebut 8.3+).

### 3. Salin File Konfigurasi

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Buka file `.env`, sesuaikan bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app_akuntansi
DB_USERNAME=root
DB_PASSWORD=
```

> Jika menggunakan XAMPP dengan password MySQL kosong, konfigurasi di atas sudah benar.

### 5. Pastikan MySQL Berjalan

Nyalakan MySQL dari **XAMPP Control Panel**, atau jalankan manual:

```bash
# Windows (XAMPP)
C:\xampp\mysql\bin\mysqld.exe --standalone
```

### 6. Buat Database

```bash
# Via MySQL CLI
mysql -u root -e "CREATE DATABASE app_akuntansi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Atau buat melalui **phpMyAdmin** di `http://localhost/phpmyadmin`.

### 7. Jalankan Migrasi dan Seeder

```bash
php artisan migrate --seed
```

Perintah ini akan:
- Membuat semua tabel (`akun`, `saldo_awal`, `jurnal`, `entri_penyesuaian`, `pengaturan`)
- Mengisi data awal: 19 akun perkiraan, saldo awal Maret 2008, 11 transaksi April, 6 AJE, dan pengaturan awal

> Jika ingin **reset ulang** seluruh data ke kondisi awal kasus studi:
> ```bash
> php artisan migrate:fresh --seed
> ```

### 8. Jalankan Development Server

```bash
php artisan serve
```

Buka browser di: **http://127.0.0.1:8000**

---

## Struktur Folder & File Penting

```
AppAkuntansi/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php       # KPI + data grafik
│   │   │   ├── JurnalController.php          # CRUD transaksi + reset
│   │   │   ├── BukuBesarController.php       # Filter akun via ?akun=
│   │   │   ├── NeracaSaldoController.php     # Trial balance
│   │   │   ├── PenyesuaianController.php     # AJE + toggle on/off
│   │   │   ├── KertasKerjaController.php     # Neraca lajur 10 kolom
│   │   │   ├── LaporanController.php         # Laporan keuangan formal
│   │   │   └── JurnalPenutupController.php   # Closing entries
│   │   │
│   │   └── Requests/
│   │       └── StoreJurnalRequest.php        # Validasi input transaksi baru
│   │
│   ├── Models/
│   │   ├── Akun.php                          # Tabel: akun (kode, nama, tipe, normal)
│   │   ├── SaldoAwal.php                     # Tabel: saldo_awal (saldo per akun)
│   │   ├── Jurnal.php                        # Tabel: jurnal (transaksi + is_static)
│   │   ├── EntriPenyesuaian.php              # Tabel: entri_penyesuaian (6 AJE)
│   │   └── Pengaturan.php                    # Tabel: pengaturan (key-value settings)
│   │
│   ├── Services/
│   │   └── AkuntansiService.php              # Engine kalkulasi akuntansi (ledger, NSD, adj)
│   │
│   └── Providers/
│       └── AppServiceProvider.php            # Registrasi Blade directive @rupiah
│
├── database/
│   ├── migrations/
│   │   ├── ..._create_akun_table.php
│   │   ├── ..._create_saldo_awal_table.php
│   │   ├── ..._create_jurnal_table.php
│   │   ├── ..._create_entri_penyesuaian_table.php
│   │   └── ..._create_pengaturan_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php                # Entry point, memanggil semua seeder
│       ├── AkunSeeder.php                    # 19 akun perkiraan (Chart of Accounts)
│       ├── SaldoAwalSeeder.php               # Saldo awal per 31 Maret 2008
│       ├── JurnalSeeder.php                  # 11 transaksi kasus studi April 2008
│       ├── EntriPenyesuaianSeeder.php        # 6 jurnal penyesuaian
│       └── PengaturanSeeder.php              # Pengaturan awal (adjustments_enabled=1)
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── akuntansi.blade.php           # Layout utama (header, footer, CDN scripts)
│       │
│       └── akuntansi/
│           ├── partials/
│           │   └── navigation.blade.php      # Tab navigasi antar menu
│           ├── dashboard.blade.php
│           ├── jurnal.blade.php
│           ├── buku-besar.blade.php
│           ├── neraca-saldo.blade.php
│           ├── penyesuaian.blade.php
│           ├── kertas-kerja.blade.php
│           ├── laporan.blade.php
│           └── jurnal-penutup.blade.php
│
└── routes/
    └── web.php                               # Definisi semua route akuntansi
```

---

## Skema Database

| Tabel | Keterangan | Diisi via |
|---|---|---|
| `akun` | 19 akun perkiraan (kode, nama, tipe, normal) | Seeder |
| `saldo_awal` | Saldo awal per akun per 31 Maret 2008 | Seeder |
| `jurnal` | Transaksi harian (`is_static=1` = data kasus, tidak bisa hapus) | Seeder + User |
| `entri_penyesuaian` | 6 AJE per 30 April 2008 | Seeder |
| `pengaturan` | Key-value settings app (`adjustments_enabled`) | Seeder |

---

## Alur Data

```
MySQL Database
      │
      ▼
AkuntansiService          ← Engine kalkulasi (ledger, NSD, adjusted balances)
      │
      ▼
Controller (per menu)     ← Ambil data, hitung, kirim ke view
      │
      ▼
Blade View (per menu)     ← Render HTML dengan @rupiah directive + Chart.js
```

---

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| Laravel 13 | Framework PHP (MVC, Eloquent, Blade, routing) |
| MySQL | Database penyimpanan data akuntansi |
| Tailwind CSS (CDN) | Styling antarmuka |
| Chart.js (CDN) | Grafik dashboard (donut, bar, pie) |
| Font Awesome (CDN) | Ikon antarmuka |

---

## Referensi Kasus Studi

Kasus Perusahaan Jasa **"Anugerah Sakti"** — Kelompok 2  
Periode: **April 2008** | Mata Kuliah: Pengantar Akuntansi
