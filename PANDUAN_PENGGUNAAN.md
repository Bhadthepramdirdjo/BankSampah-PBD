# PANDUAN PENGGUNAAN APLIKASI BANK SAMPAH
Kelompok 2 - Pemrograman Basis Data

Dokumen ini berisi panduan lengkap mengenai tata cara instalasi dan penggunaan aplikasi Bank Sampah. Aplikasi ini dirancang untuk mengelola data nasabah, jenis sampah, dan transaksi setoran sampah dalam sebuah sistem bank sampah.

## 1. PENDAHULUAN

Aplikasi Bank Sampah adalah sistem informasi berbasis web yang memfasilitasi pencatatan kegiatan operasional bank sampah. Aplikasi ini memungkinkan administrator untuk mengelola data nasabah yang terdaftar, mengatur jenis dan harga sampah, serta mencatat setiap transaksi penyetoran sampah yang dilakukan oleh nasabah. Sistem ini juga menyediakan laporan rekapitulasi untuk memantau aliran sampah dan keuangan.

## 2. PERSYARATAN SISTEM

Untuk menjalankan aplikasi ini, diperlukan perangkat lunak sebagai berikut:
1.  **XAMPP**: Paket server web lokal yang mencakup Apache (Web Server) dan MySQL (Database Server).
2.  **Web Browser**: Google Chrome, Mozilla Firefox, atau Microsoft Edge versi terbaru.
3.  **Koneksi Internet**: Opsional, hanya diperlukan jika ingin memuat font atau ikon dari CDN (Google Fonts / FontAwesome). Secara default aplikasi tetap berjalan namun ikon mungkin tidak tampil jika offline.

## 3. INSTALASI DAN KONFIGURASI

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal:

1.  **Persiapan Folder**:
    *   Pastikan folder proyek `BankSampah` tersimpan di dalam direktori `htdocs` pada instalasi XAMPP Anda (misalnya: `D:\Xampp\htdocs\BankSampah`).

2.  **Konfigurasi Database**:
    *   Buka aplikasi XAMPP Control Panel dan jalankan modul **Apache** dan **MySQL** dengan menekan tombol **Start**.
    *   Buka browser dan akses `http://localhost/phpmyadmin`.
    *   Buat database baru dengan nama `db_banksampah`.
    *   Pilih database tersebut, lalu masuk ke menu **Import**.
    *   Klik **Choose File**, arahkan ke file `db_banksampah.sql` yang berada di dalam folder `Database` pada proyek ini.
    *   Klik tombol **Go** atau **Kirim** untuk memproses impor. Pastikan kueri berhasil dijalankan.

3.  **Menjalankan Aplikasi**:
    *   Buka web browser.
    *   Ketikkan alamat `http://localhost/BankSampah` pada address bar.
    *   Anda akan langsung diarahkan ke halaman **Dashboard** utama.

## 4. TATA CARA PENGGUNAAN

Aplikasi ini menggunakan sistem navigasi sidebar di sebelah kiri. Berikut adalah penjelasan fungsi setiap menu:

### A. DASHBOARD (Beranda)
Halaman ini adalah tampilan pertama saat aplikasi dibuka.

*   **Kartu Statistik**: Menampilkan ringkasan data secara real-time, meliputi Total Nasabah Terdaftar, Total Transaksi yang terjadi, dan Total Uang yang telah disalurkan ke nasabah.
*   **Riwayat Setoran Terbaru**: Menampilkan tabel berisi 5 transaksi terakhir yang masuk ke dalam sistem, memudahkan pemantauan aktivitas terkini.

### B. DATA NASABAH
Menu ini digunakan untuk mengelola data anggota bank sampah.

1.  **Menambah Nasabah Baru**:
    *   Klik tombol **Tambah Nasabah** di pojok kanan atas.
    *   Isi formulir yang muncul (Nama Lengkap, Nomor Telepon, Alamat).
    *   Klik tombol **Simpan Data**. Data akan otomatis muncul di tabel.

2.  **Mengedit Data Nasabah**:
    *   Pada tabel daftar nasabah, klik tombol berwarna biru dengan ikon pensil pada baris nasabah yang ingin diubah.
    *   Formulir akan terisi dengan data nasabah tersebut.
    *   Lakukan perubahan yang diperlukan, lalu klik **Simpan Data**.

3.  **Menghapus Data Nasabah**:
    *   Klik tombol berwarna merah dengan ikon tempat sampah.
    *   Konfirmasi penghapusan pada jendela dialog yang muncul.

4.  **Pengurutan (Sorting)**:
    *   Klik ikon panah pada kolom "Nama Lengkap" untuk mengurutkan nama dari A ke Z atau Z ke A.

### C. JENIS SAMPAH
Menu ini digunakan untuk mengatur katalog sampah yang diterima.

1.  **Menambah Jenis Sampah**:
    *   Klik tombol **Tambah** dan isi detail sampah (Nama Jenis, Satuan seperti kg/pcs, dan Harga per Satuan).
    *   Klik **Simpan Data**.

2.  **Mengedit Jenis Sampah**:
    *   Klik tombol edit (biru) pada jenis sampah yang diinginkan.
    *   Ubah data harga atau nama, lalu simpan.

3.  **Mencari Jenis Sampah**:
    *   Gunakan kolom pencarian di bagian atas untuk mencari jenis sampah tertentu.

*Catatan: Jenis sampah yang sudah pernah digunakan dalam transaksi tidak dapat dihapus sembarangan untuk menjaga integritas data laporan.*

### D. SETOR SAMPAH (TRANSAKSI)
Menu ini adalah inti dari operasional harian, yaitu mencatat penyetoran sampah dari nasabah.

1.  **Mencatat Transaksi Baru**:
    *   Pada formulir "Tambah Transaksi Baru":
        *   **Pilih Nasabah**: Pilih nama nasabah dari daftar dropdown.
        *   **Pilih Jenis Sampah**: Pilih jenis sampah yang disetor. Harga satuan akan terdeteksi otomatis oleh sistem.
        *   **Berat / Jumlah**: Masukkan angka berat atau jumlah satuan.
        *   **Catatan**: Opsional, untuk keterangan tambahan.
    *   Sistem akan secara otomatis menghitung **Total Harga** yang harus dibayarkan.
    *   Klik **Simpan Transaksi**. Bukti transaksi akan tercatat di tabel riwayat di sebelah kanan/bawah.

2.  **Filter dan Pencarian**:
    *   Anda dapat mencari riwayat transaksi berdasarkan nama nasabah menggunakan kolom pencarian.
    *   Anda juga dapat mengurutkan riwayat berdasarkan Tanggal (Terbaru/Terlama) atau Nama Nasabah (A-Z/Z-A) menggunakan tombol filter yang tersedia.

### E. LAPORAN
Menu ini menyajikan rekapitulasi data untuk keperluan evaluasi.

1.  **Rekapitulasi Sampah Masuk**: Tabel ini menampilkan akumulasi total berat dan total uang yang dikeluarkan untuk setiap kategori sampah. Ini berguna untuk melihat jenis sampah apa yang paling dominan.
2.  **Transaksi Terbesar**: Menampilkan daftar 5 transaksi dengan nilai nominal tertinggi sebagai apresiasi atau analisis.

Setiap halaman pada aplikasi ini dilengkapi dengan Footer yang menandakan identitas pengembang aplikasi: **Kelompok 2 - Bank Sampah - Tugas Besar Pemrograman Basis Data**.

---
Dokumen ini dibuat pada Januari 2026.
Jika terjadi kendala teknis, silakan hubungi tim pengembang (Kelompok 2).
