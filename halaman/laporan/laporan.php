<?php
session_start();
include '../../Koneksi/koneksi.php';

// Cek Login dihapus

// Laporan 1: Rekapitulasi Per Jenis Sampah
// Query untuk menjumlahkan berat dan harga berdasarkan jenis sampah (GROUP BY)
$queryRekap = "SELECT j.nama_jenis, j.satuan, SUM(s.berat) as total_berat, SUM(s.total_harga) as total_uang 
               FROM setoran s
               JOIN jenis_sampah j ON s.id_jenis = j.id_jenis
               GROUP BY j.id_jenis, j.nama_jenis";
$dataRekap = mysqli_query($koneksi, $queryRekap);

// Laporan 2: Transaksi Terbesar (Top 5)
$queryTop = "SELECT s.*, n.nama_lengkap, j.nama_jenis 
             FROM setoran s
             JOIN nasabah n ON s.id_nasabah = n.id_nasabah
             JOIN jenis_sampah j ON s.id_jenis = j.id_jenis
             ORDER BY s.total_harga DESC LIMIT 5";
$dataTop = mysqli_query($koneksi, $queryTop);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../../aset/css/style.css">
</head>
<body>

    <div class="sidebar">
        <div class="brand"><i class="fas fa-recycle"></i> RESIK APP</div>
        <nav class="menu">
            <a href="../../index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="../nasabah/nasabah.php"><i class="fas fa-users"></i> Data Nasabah</a>
            <a href="../sampah/sampah.php"><i class="fas fa-box"></i> Jenis Sampah</a>
            <a href="../transaksi/transaksi.php"><i class="fas fa-exchange-alt"></i> Setor Sampah</a>
            <a href="laporan.php" class="active"><i class="fas fa-file-alt"></i> Laporan</a>
        </nav>
    </div>

    <div class="main-content">
        <h2 style="margin-bottom: 20px;">Laporan & Rekapitulasi</h2>

        <!-- Tabel 1: Total Sampah Masuk -->
        <div class="card">
            <h3><i class="fas fa-chart-pie"></i> Rekapitulasi Sampah Masuk</h3>
            <p style="margin-bottom: 10px; color: #666;">Total berat sampah dan pengeluaran uang berdasarkan jenisnya.</p>
            <table>
                <thead>
                    <tr>
                        <th>Jenis Sampah</th>
                        <th>Total Berat</th>
                        <th>Total Uang Keluar (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($dataRekap)): ?>
                    <tr>
                        <td style="font-weight: bold;"><?= $row['nama_jenis'] ?></td>
                        <td><?= $row['total_berat'] . ' ' . $row['satuan'] ?></td>
                        <td style="color: #00b894;">Rp <?= number_format($row['total_uang'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel 2: Transaksi Terbesar -->
        <div class="card">
            <h3><i class="fas fa-trophy"></i> 5 Transaksi Terbesar</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>Nasabah</th>
                        <th>Sampah</th>
                        <th>Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($top = mysqli_fetch_assoc($dataTop)): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($top['tanggal_setor'])) ?></td>
                        <td><?= $top['nama_lengkap'] ?></td>
                        <td><?= $top['nama_jenis'] ?> (<?= $top['berat'] ?>)</td>
                        <td style="font-weight: bold;">Rp <?= number_format($top['total_harga'], 0, ',', '.') ?></td>
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
