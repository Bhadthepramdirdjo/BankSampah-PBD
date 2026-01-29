<?php
session_start();
include '../../Koneksi/koneksi.php';

// Cek Login Dihapus

// 1. Tambah Transaksi
if (isset($_POST['simpan'])) {
    $id_nasabah = $_POST['id_nasabah'];
    $id_jenis   = $_POST['id_jenis'];
    $berat      = $_POST['berat'];
    $catatan    = $_POST['catatan'];
    
    // Ambil harga per satuan dari database untuk hitung total
    $qHarga = mysqli_query($koneksi, "SELECT harga_per_satuan FROM jenis_sampah WHERE id_jenis='$id_jenis'");
    $dHarga = mysqli_fetch_assoc($qHarga);
    $harga_satuan = $dHarga['harga_per_satuan'];
    
    // Hitung total harga
    $total_harga = $berat * $harga_satuan;
    
    $query = mysqli_query($koneksi, "INSERT INTO setoran (id_nasabah, id_jenis, berat, total_harga, catatan) VALUES ('$id_nasabah', '$id_jenis', '$berat', '$total_harga', '$catatan')");
    
    if($query) {
        // [FIX BUG RESUBMIT] Gunakan Session Flash Message & Redirect
        $_SESSION['msg_success'] = "Transaksi berhasil disimpan! Total: Rp " . number_format($total_harga, 0, ',', '.');
        header("Location: transaksi.php"); 
        exit; 
    } else {
        $error = "Transaksi gagal: " . mysqli_error($koneksi);
    }
}

// 2. Hapus Transaksi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM setoran WHERE id_setoran='$id'");
    header("Location: transaksi.php");
    exit;
}

// 3. Pencarian & SORTING
$where = "";
$paramCari = ""; 

if(isset($_GET['cari']) && !empty($_GET['cari'])){
    $cari = $_GET['cari'];
    $where = "WHERE n.nama_lengkap LIKE '%$cari%'";
    $paramCari = "&cari=$cari"; 
}

// Logic Sorting
$orderBy = "ORDER BY s.tanggal_setor DESC"; // Default

if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    switch ($sort) {
        case 'name_asc':  $orderBy = "ORDER BY n.nama_lengkap ASC"; break;
        case 'name_desc': $orderBy = "ORDER BY n.nama_lengkap DESC"; break;
        case 'date_asc':  $orderBy = "ORDER BY s.tanggal_setor ASC"; break;
        case 'date_desc': $orderBy = "ORDER BY s.tanggal_setor DESC"; break;
        default: $orderBy = "ORDER BY s.tanggal_setor DESC"; 
    }
}

// Ambil Data Master untuk Dropdown
$dataNasabah = mysqli_query($koneksi, "SELECT * FROM nasabah ORDER BY nama_lengkap ASC");
$dataJenis   = mysqli_query($koneksi, "SELECT * FROM jenis_sampah ORDER BY nama_jenis ASC");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Setor Sampah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
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
            <a href="transaksi.php" class="active"><i class="fas fa-exchange-alt"></i> Setor Sampah</a>
            <a href="../laporan/laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Transaksi Setor Sampah</h2>
            <div class="search-box">
                <form action="" method="GET" style="display: flex; gap: 5px;">
                    <input type="text" name="cari" placeholder="Cari Nama Nasabah..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
                    <button type="submit" class="btn btn-green"><i class="fas fa-search"></i></button>
                    <?php if(isset($_GET['cari'])): ?>
                        <a href="transaksi.php" class="btn btn-red"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </form>
                <button onclick="toggleForm()" class="btn btn-green"><i class="fas fa-plus"></i> Transaksi Baru</button>
            </div>
        </div>

        <!-- Tampilkan Pesan Sukses dari Session (Anti-Resubmit) -->
        <?php 
        if(isset($_SESSION['msg_success'])) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;'>{$_SESSION['msg_success']}</div>";
            unset($_SESSION['msg_success']); // Hapus session agar tidak muncul lagi saat refresh
        }
        ?>

        <?php if(isset($error)) echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;'>$error</div>"; ?>

        <!-- Form Transaksi (Hidden by default) -->
        <div id="formContainer" class="form-container">
            <h3 style="margin-bottom: 20px; color: #00b894;">Form Setor Sampah</h3>
            <form action="" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Pilih Nasabah</label>
                        <select name="id_nasabah" required>
                            <option value="">-- Pilih Nasabah --</option>
                            <?php while($n = mysqli_fetch_assoc($dataNasabah)): ?>
                                <option value="<?= $n['id_nasabah'] ?>"><?= $n['nama_lengkap'] ?> - <?= $n['alamat'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Jenis Sampah</label>
                        <select name="id_jenis" id="id_jenis" required onchange="updateInfo()">
                            <option value="" data-harga="0" data-satuan="">-- Pilih Sampah --</option>
                            <?php while($j = mysqli_fetch_assoc($dataJenis)): ?>
                                <option value="<?= $j['id_jenis'] ?>" data-harga="<?= $j['harga_per_satuan'] ?>" data-satuan="<?= $j['satuan'] ?>">
                                    <?= $j['nama_jenis'] ?> (Rp <?= number_format($j['harga_per_satuan']) ?>/<?= $j['satuan'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Berat / Jumlah (<span id="labelSatuan">kg</span>)</label>
                        <input type="number" step="0.1" name="berat" id="berat" required onkeyup="hitungTotal()">
                    </div>
                    <div class="form-group">
                        <label>Estimasi Total (Rp)</label>
                        <input type="text" id="total_display" readonly style="background: #eee; font-weight: bold; color: #00b894;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Kondisi sampah, dll..."></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="simpan" class="btn btn-green">Simpan Transaksi</button>
                    <button type="button" onclick="toggleForm()" class="btn btn-red">Batal</button>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">
                            Tgl
                            <span class="sort-icons">
                                <a href="transaksi.php?sort=date_asc<?= $paramCari ?>" title="Urutkan Terlama" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'date_asc') ? 'active' : '' ?>">
                                    <i class="fas fa-sort-amount-down-alt"></i>
                                </a>
                                <a href="transaksi.php?sort=date_desc<?= $paramCari ?>" title="Urutkan Terbaru" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'date_desc') ? 'active' : '' ?>">
                                    <i class="fas fa-sort-amount-down"></i>
                                </a>
                            </span>
                        </th>
                        <th>
                            Nasabah
                            <span class="sort-icons">
                                <a href="transaksi.php?sort=name_asc<?= $paramCari ?>" title="Urutkan A-Z" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'active' : '' ?>">
                                    <i class="fas fa-sort-alpha-down"></i>
                                </a>
                                <a href="transaksi.php?sort=name_desc<?= $paramCari ?>" title="Urutkan Z-A" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'active' : '' ?>">
                                    <i class="fas fa-sort-alpha-up"></i>
                                </a>
                            </span>
                        </th>
                        <th>Jenis Sampah</th>
                        <th>Berat</th>
                        <th>Total Harga</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $query = "SELECT s.*, n.nama_lengkap, j.nama_jenis, j.satuan 
                              FROM setoran s 
                              JOIN nasabah n ON s.id_nasabah = n.id_nasabah 
                              JOIN jenis_sampah j ON s.id_jenis = j.id_jenis 
                              $where 
                              $orderBy";
                    
                    $data = mysqli_query($koneksi, $query);
                    
                    if(mysqli_num_rows($data) == 0): ?>
                        <tr><td colspan="7" style="text-align: center;">Belum ada data transaksi.</td></tr>
                    <?php else:
                        while($row = mysqli_fetch_assoc($data)): 
                    ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal_setor'])) ?></td>
                        <td><b><?= $row['nama_lengkap'] ?></b></td>
                        <td><?= $row['nama_jenis'] ?></td>
                        <td><?= $row['berat'] . ' ' . $row['satuan'] ?></td>
                        <td style="color: #00b894; font-weight: bold;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td><?= $row['catatan'] ?></td>
                        <td>
                            <a href="transaksi.php?hapus=<?= $row['id_setoran'] ?>" class="btn btn-red" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Batalkan transaksi ini?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Kelompok 2 - Bank Sampah - Tugas Besar Pemrograman Basis Data</p>
        </div>
    </div>

    <script src="../../aset/Js/transaksiscript.js"></script>
</body>
</html>
