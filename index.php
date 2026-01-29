<?php
session_start();
// Akses koneksi dari root
include 'Koneksi/koneksi.php';

// 1. Query Data Ringkasan (Card)
// Total Nasabah
$qNasabah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nasabah");
$dNasabah = mysqli_fetch_assoc($qNasabah);

// Total Transaksi
$qSetoran = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM setoran");
$dSetoran = mysqli_fetch_assoc($qSetoran);

// Total Uang Keluar (Jml yang dibayarkan ke nasabah)
$qUang = mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM setoran");
$dUang = mysqli_fetch_assoc($qUang);

// 2. Query Tabel Transaksi Terbaru (5 Terakhir)
$qRecent = mysqli_query($koneksi, "
    SELECT s.id_setoran, n.nama_lengkap, j.nama_jenis, s.berat, j.satuan, s.total_harga, s.tanggal_setor 
    FROM setoran s
    JOIN nasabah n ON s.id_nasabah = n.id_nasabah
    JOIN jenis_sampah j ON s.id_jenis = j.id_jenis
    ORDER BY s.tanggal_setor DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Bank Sampah Resik</title>
    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="aset/css/style.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-recycle"></i> Bank Sampah
        </div>
        <nav class="menu">
            <a href="index.php" class="active">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="halaman/nasabah/nasabah.php">
                <i class="fas fa-users"></i> Data Nasabah
            </a>
            <a href="halaman/sampah/sampah.php">
                <i class="fas fa-box"></i> Jenis Sampah
            </a>
            <a href="halaman/transaksi/transaksi.php">
                <i class="fas fa-exchange-alt"></i> Setor Sampah
            </a>
            <a href="halaman/laporan/laporan.php">
                <i class="fas fa-file-alt"></i> Laporan
            </a>
            <!-- Logout dihapus -->
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="header-title">Halo halo halo hai! 👋</h2>

        <!-- Statistik Cards -->
        <div class="cards-grid">
            <div class="card">
                <div class="card-info">
                    <h3><?= $dNasabah['total'] ?></h3>
                    <p>Total Nasabah</p>
                </div>
                <div class="card-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="card">
                <div class="card-info">
                    <h3><?= $dSetoran['total'] ?></h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="card-icon"><i class="fas fa-exchange-alt"></i></div>
            </div>
            <div class="card">
                <div class="card-info">
                    <h3>Rp <?= number_format($dUang['total'], 0, ',', '.') ?></h3>
                    <p>Uang Tersalurkan</p>
                </div>
                <div class="card-icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>

        <!-- Tabel Transaksi Terakhir -->
        <div class="table-container">
            <div class="table-header">
                <h3>Riwayat Setoran Terbaru</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>Nasabah</th>
                        <th>Jenis Sampah</th>
                        <th>Berat</th>
                        <th>Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($qRecent)): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal_setor'])) ?></td>
                        <td><b><?= $row['nama_lengkap'] ?></b></td>
                        <td><?= $row['nama_jenis'] ?></td>
                        <td><?= $row['berat'] . ' ' . $row['satuan'] ?></td>
                        <td style="color: #00b894; font-weight: bold;">
                            Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Kelompok 2 - Bank Sampah - Tugas Besar Pemrograman Basis Data</p>
        </div>
    </div>

</body>
</html>