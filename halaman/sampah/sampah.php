<?php
session_start();
include '../../Koneksi/koneksi.php';

// Cek Status Login Dihapus

// 1. Tambah Data
if (isset($_POST['simpan'])) {
    $jenis = $_POST['jenis'];
    $satuan = $_POST['satuan'];
    $harga = $_POST['harga'];
    
    // Insert ke db
    $query = mysqli_query($koneksi, "INSERT INTO jenis_sampah (nama_jenis, satuan, harga_per_satuan) VALUES ('$jenis', '$satuan', '$harga')");
    if($query) {
        // Fix Bug Resubmission: Redirect
        $_SESSION['msg'] = "Berhasil menambah jenis sampah baru!";
        header("Location: sampah.php");
        exit;
    } else {
        $error = "Gagal menyimpan: " . mysqli_error($koneksi);
    }
}

// 2. Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = mysqli_query($koneksi, "DELETE FROM jenis_sampah WHERE id_jenis='$id'");
    if($cek) {
        // Fix Bug Resubmission: Redirect
        $_SESSION['msg'] = "Data berhasil dihapus!";
        header("Location: sampah.php");
        exit;
    } else {
        $error = "Data tidak bisa dihapus karena sudah ada transaksi (cascade setoran).";
    }
}

// 3. Update Data
if (isset($_POST['update'])) {
    $id = $_POST['id_jenis'];
    $jenis = $_POST['jenis'];
    $satuan = $_POST['satuan'];
    $harga = $_POST['harga'];

    $query = mysqli_query($koneksi, "UPDATE jenis_sampah SET nama_jenis='$jenis', satuan='$satuan', harga_per_satuan='$harga' WHERE id_jenis='$id'");
    if($query) {
        // Fix Bug Resubmission: Redirect
        $_SESSION['msg'] = "Data berhasil diperbarui!";
        header("Location: sampah.php");
        exit;
    }
}

// 4. Pencarian
$where = "";
if(isset($_GET['cari'])){
    $cari = $_GET['cari'];
    $where = "WHERE nama_jenis LIKE '%$cari%'";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jenis Sampah</title>
    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../aset/css/style.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand"><i class="fas fa-recycle"></i> RESIK APP</div>
        <nav class="menu">
            <a href="../../index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="../nasabah/nasabah.php"><i class="fas fa-users"></i> Data Nasabah</a>
            <a href="sampah.php" class="active"><i class="fas fa-box"></i> Jenis Sampah</a>
            <a href="../transaksi/transaksi.php"><i class="fas fa-exchange-alt"></i> Setor Sampah</a>
            <a href="../laporan/laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Data Jenis Sampah</h2>
            <div class="search-box">
                <form action="" method="GET" style="display: flex; gap: 5px;">
                    <input type="text" name="cari" placeholder="Cari jenis sampah..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
                    <button type="submit" class="btn btn-blue" style="padding: 8px 15px;"><i class="fas fa-search"></i></button>
                    <?php if(isset($_GET['cari'])): ?>
                        <a href="sampah.php" class="btn btn-red" style="padding: 8px 15px;"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </form>
                <button onclick="openModal()" class="btn btn-green"><i class="fas fa-plus"></i> Tambah</button>
            </div>
        </div>

        <!-- Tampilkan Pesan Sukses Session -->
        <?php 
        if(isset($_SESSION['msg'])) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;'>{$_SESSION['msg']}</div>";
            unset($_SESSION['msg']);
        }
        ?>

        <?php if(isset($error)) echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;'>$error</div>"; ?>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Sampah</th>
                        <th>Satuan</th>
                        <th>Harga / Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $data = mysqli_query($koneksi, "SELECT * FROM jenis_sampah $where ORDER BY id_jenis DESC");
                    while($row = mysqli_fetch_assoc($data)): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><b><?= $row['nama_jenis'] ?></b></td>
                        <td><?= $row['satuan'] ?></td>
                        <td>Rp <?= number_format($row['harga_per_satuan'], 0, ',', '.') ?></td>
                        <td>
                            <button onclick="edit(<?= $row['id_jenis'] ?>, '<?= $row['nama_jenis'] ?>', '<?= $row['satuan'] ?>', '<?= $row['harga_per_satuan'] ?>')" class="btn btn-blue" style="padding: 5px 10px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                            <a href="sampah.php?hapus=<?= $row['id_jenis'] ?>" class="btn btn-red" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Yakin hapus? Data transaksi terkait juga akan terhapus!')"><i class="fas fa-trash"></i></a>
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

    <!-- Modal Form -->
    <div id="modalForm" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Jenis Sampah</h3>
                <span onclick="closeModal()" style="cursor: pointer; font-size: 20px;">&times;</span>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="id_jenis" id="id_jenis">
                <div class="form-group">
                    <label>Nama Jenis Sampah</label>
                    <input type="text" name="jenis" id="jenis" placeholder="Contoh: Kardus Bekas" required>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" id="satuan" placeholder="Contoh: kg / botol / pcs" required>
                </div>
                <div class="form-group">
                    <label>Harga per Satuan (Rp)</label>
                    <input type="number" name="harga" id="harga" placeholder="Contoh: 3000" required>
                </div>
                <button type="submit" name="simpan" id="btnSimpan" class="btn btn-green" style="width: 100%">Simpan Data</button>
                <button type="submit" name="update" id="btnUpdate" class="btn btn-blue" style="width: 100%; display: none;">Update Data</button>
            </form>
        </div>
    </div>

    <script src="../../aset/Js/sampahscript.js"></script>
</body>
</html>
