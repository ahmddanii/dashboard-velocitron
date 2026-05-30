# Dashboard Velocitron

Dashboard Velocitron adalah platform Decision Support System (DSS) berbasis web yang komprehensif, dibangun menggunakan **Laravel 11** dan terintegrasi dengan **Data Warehouse API berbasis Flask**. Platform ini dirancang untuk memantau, menganalisis, dan memprediksi profitabilitas transaksi atau kontrak bisnis di berbagai divisi perusahaan melalui pendekatan berbasis data (*data-driven*).

## 🌟 Fitur Utama

- **Decision Support System (DSS) & Predictive Analytics:**
  Memprediksi profitabilitas usulan transaksi (`Profitable` atau `Loss`) lengkap dengan tingkat keyakinan (*confidence level*) menggunakan integrasi Machine Learning dari Flask API.
- **Integrasi Data Warehouse (Flask API):**
  Menyinkronkan data ritel/transaksi untuk menyajikan metrik bisnis waktu nyata (*real-time*), seperti tren bulanan (*monthly trend*), profit per kategori (*profit by category*), penjualan per wilayah (*sales by region*), penjualan per segmen (*sales by segment*), dan produk terlaris (*top products*).
- **Role-Based Access Control (RBAC) yang Personalisasi:**
  Dashboard dinamis yang disesuaikan secara khusus berdasarkan 5 peran utama dalam organisasi menggunakan `spatie/laravel-permission`:
  1. **Head of Data Analytics (`head-analytics`):** Akses penuh memantau volume prediksi, akurasi estimasi model (*model health*), manajemen pengguna (`/users`), serta mengelola umpan kecerdasan (*intelligence feed*).
  2. **Financial Controller (`financial-controller`):** Analisis kelayakan finansial mendalam, pemantauan tingkat persetujuan (*approval rate*), identifikasi kategori produk paling berisiko, dan pratinjau laporan eksekutif.
  3. **Logistics Officer (`logistics-officer`):** Manajemen pengajuan pengiriman (*shipment requests*), analisis performa logistik, pemantauan moda pengiriman paling berisiko (*risky ship mode*), serta wawasan pengiriman.
  4. **Procurement Director (`procurement-director`):** Memantau pengajuan pengadaan (*procurement requests*), menganalisis tingkat persetujuan pengadaan, serta mendeteksi kategori barang yang paling sering ditolak.
  5. **Key Account Manager (`key-account-manager`):** Mengelola usulan kontrak (*contract requests*) khusus untuk segmen Corporate dan Home Office, serta melakukan analisis performa kontrak.
- **Alur Kerja Pengajuan Transaksi (Transaction Request Workflow):**
  Sistem persetujuan terintegrasi di mana setiap divisi mengajukan transaksi, dievaluasi otomatis oleh DSS untuk mendapatkan prediksi profitabilitas, kemudian ditinjau (Approve/Reject) oleh divisi atau manajer yang berwenang.
- **Manajemen Data (Import/Export):**
  - Mengimpor transaksi massal menggunakan file CSV berbasis templat yang disediakan.
  - Mengekspor hasil analisis DSS dan riwayat transaksi lengkap ke format CSV.
- **User Interface Modern & Interaktif:**
  Antarmuka responsif yang dibangun dengan **Tailwind CSS**, diperkaya dengan micro-interaction dari **Alpine.js**, serta visualisasi grafik interaktif menggunakan **Chart.js**.

## 🛠️ Tech Stack

- **Backend Utama:** Laravel 11, PHP 8.4
- **DSS & Analytics Engine:** Flask API (Python)
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templates, Vite
- **Visualisasi Grafik:** Chart.js
- **HTTP Client (Frontend):** Axios
- **Database:** MySQL / SQLite

## 🚀 Panduan Memulai

### Prasyarat

Sebelum memulai, pastikan perangkat Anda telah terinstal:
- PHP >= 8.4
- Composer
- Node.js & NPM
- Database Server (MySQL/MariaDB atau SQLite)
- Python (untuk menjalankan server Flask Data Warehouse API)

### Langkah Instalasi

1. **Clone repositori:**
   ```bash
   git clone <repository-url>
   cd uasprototype
   ```

2. **Instal dependensi PHP:**
   ```bash
   composer install
   ```

3. **Instal dependensi Frontend:**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment:**
   Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda serta URL API Flask Anda.
   ```bash
   cp .env.example .env
   ```
   *Pastikan parameter `FLASK_API_URL` dikonfigurasi dengan benar (default: `http://localhost:5000/api`).*

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi & Seeding Database:**
   Buat skema tabel dan isi data awal (role, izin, dan data bawaan).
   ```bash
   php artisan migrate --seed
   ```

7. **Kompilasi Aset Frontend:**
   ```bash
   # Mode pengembangan (HMR):
   npm run dev

   # Mode produksi (Build):
   npm run build
   ```

8. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi Anda kini dapat diakses di `http://localhost:8000`.

## 🛡️ Struktur Hak Akses Peran (Roles)

Setiap peran memiliki otorisasi spesifik pada alur kerja pengajuan transaksi (*Transaction Request*):
- **Head Analytics:** 
  - Mengelola daftar pengguna (tambah/hapus user).
  - Akses penuh pratinjau dan unduh laporan DSS lengkap.
  - Memantau keandalan model prediksi (*model health*).
- **Financial Controller:**
  - Melakukan review pengajuan transaksi secara finansial.
  - Akses pratinjau metrik keputusan sebelum diekspor.
- **Logistics Officer:**
  - Membuat dan mengelola pengajuan bertipe `shipment`.
- **Procurement Director:**
  - Membuat dan mengelola pengajuan bertipe `procurement`.
- **Key Account Manager:**
  - Membuat dan mengelola pengajuan bertipe `contract` khusus segmen Corporate dan Home Office.

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
