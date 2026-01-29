<?php
session_start();
include '../../Koneksi/koneksi.php';

// Cek Status Login Dihapus
// LOGIKA SATU PINTU (UNIFIED FORM logic)
if (isset($_POST['simpan'])) {
    $id     = mysqli_real_escape_string($koneksi, $_POST['id_nasabah']);
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $hp     = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    if(!empty($id)) {
        // Mode Update
        $query = mysqli_query($koneksi, "UPDATE nasabah SET nama_lengkap='$nama', nomor_telepon='$hp', alamat='$alamat' WHERE id_nasabah='$id'");
        $msg = "Data berhasil diperbarui!";
    } else {
        // Mode Insert
        $query = mysqli_query($koneksi, "INSERT INTO nasabah (nama_lengkap, nomor_telepon, alamat) VALUES ('$nama', '$hp', '$alamat')");
        $msg = "Data berhasil disimpan!";
    }
}

// Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM nasabah WHERE id_nasabah='$id'");
    header("Location: nasabah.php");
}

// LOGIKA SORTING (A-Z / Z-A)
$orderBy = "ORDER BY id_nasabah DESC"; // Default (Terbaru)

if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    if($sort == 'asc') {
        $orderBy = "ORDER BY nama_lengkap ASC"; // A ke Z
    } else if($sort == 'desc') {
        $orderBy = "ORDER BY nama_lengkap DESC"; // Z ke A
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nasabah</title>
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
            <a href="nasabah.php" class="active"><i class="fas fa-users"></i> Data Nasabah</a>
            <a href="../sampah/sampah.php"><i class="fas fa-box"></i> Jenis Sampah</a>
            <a href="../transaksi/transaksi.php"><i class="fas fa-exchange-alt"></i> Setor Sampah</a>
            <a href="../laporan/laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Data Nasabah</h2>
            <button onclick="openModal()" class="btn btn-green"><i class="fas fa-plus"></i> Tambah Nasabah</button>
        </div>

        <?php if(isset($msg)) echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;'>$msg</div>"; ?>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>
                            Nama Lengkap 
                            <span class="sort-icons">
                                <a href="nasabah.php?sort=asc" title="Urutkan A-Z" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'active' : '' ?>">
                                    <i class="fas fa-sort-alpha-down"></i>
                                </a>
                                <a href="nasabah.php?sort=desc" title="Urutkan Z-A" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'desc') ? 'active' : '' ?>">
                                    <i class="fas fa-sort-alpha-up"></i>
                                </a>
                            </span>
                        </th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $data = mysqli_query($koneksi, "SELECT * FROM nasabah $orderBy");
                    while($row = mysqli_fetch_assoc($data)): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><b><?= $row['nama_lengkap'] ?></b></td>
                        <td><?= $row['nomor_telepon'] ?></td>
                        <td><?= $row['alamat'] ?></td>
                        <td>
                            <button onclick="edit('<?= $row['id_nasabah'] ?>', '<?= htmlspecialchars($row['nama_lengkap'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['nomor_telepon'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['alamat'], ENT_QUOTES) ?>')" class="btn btn-blue" style="padding: 5px 10px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                            <a href="nasabah.php?hapus=<?= $row['id_nasabah'] ?>" class="btn btn-red" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
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
                <h3 id="modalTitle">Tambah Nasabah Baru</h3>
                <span onclick="closeModal()" style="cursor: pointer; font-size: 20px;">&times;</span>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="id_nasabah" id="id_nasabah"> 
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" required>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="hp" id="hp" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" required></textarea>
                </div>
                <button type="submit" name="simpan" id="btnSimpan" class="btn btn-green" style="width: 100%">Simpan Data</button>
            </form>
        </div>
    </div>

    <script src="../../aset/Js/nasabahscript.js"></script>
</body>
</html>
