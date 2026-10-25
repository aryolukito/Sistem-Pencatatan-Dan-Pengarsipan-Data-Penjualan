<?php
session_start();
include('../../config/koneksi.php');

// Cek apakah user sudah login dan memiliki role petugas
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    header('Location: ../login.php'); 
    exit;
}

// Ambil ID petugas dari session
if (!isset($_SESSION['id_pengguna'])) {
    die("Error: ID petugas tidak ditemukan di session.");
}
$petugas_id = $_SESSION['id_pengguna'];

// Validasi apakah ID petugas ada di tabel pengguna
$queryCekPetugas = "SELECT id_pengguna FROM pengguna WHERE id_pengguna = '$petugas_id'";
$resultCekPetugas = $koneksi->query($queryCekPetugas);
if ($resultCekPetugas->num_rows == 0) {
    die("Error: ID petugas tidak ditemukan dalam tabel pengguna.");
}

// Ambil data jenis hewan dan harga dari database
$queryHewan = "SELECT * FROM hewan";
$resultHewan = $koneksi->query($queryHewan);

// Proses penyimpanan data transaksi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_jenis_hewan = $_POST['id_jenis_hewan'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];
    $status = 'draft';

    // Ambil nama jenis hewan berdasarkan id_jenis_hewan
    $queryNamaHewan = "SELECT jenis_hewan FROM hewan WHERE id_hewan = '$id_jenis_hewan'";
    $resultNamaHewan = $koneksi->query($queryNamaHewan);
    $rowNamaHewan = $resultNamaHewan->fetch_assoc();
    $nama_hewan = $rowNamaHewan['jenis_hewan'];

    // Cek apakah sudah ada data draft dengan jenis hewan yang sama untuk petugas ini
    $queryCekDraft = "SELECT id, jumlah FROM transaksi 
                      WHERE id_jenis_hewan = '$id_jenis_hewan' 
                      AND status = 'draft' 
                      AND id_petugas = '$petugas_id'";
    $resultCekDraft = $koneksi->query($queryCekDraft);

    if ($resultCekDraft->num_rows > 0) {
        // Jika data ada, update jumlahnya
        $rowDraft = $resultCekDraft->fetch_assoc();
        $idTransaksi = $rowDraft['id'];
        $jumlahLama = $rowDraft['jumlah'];
        $jumlahBaru = $jumlahLama + $jumlah;

        // Update jumlah di data yang ada
        $queryUpdate = "UPDATE transaksi SET jumlah = '$jumlahBaru' WHERE id = '$idTransaksi'";
        if ($koneksi->query($queryUpdate) === TRUE) {
            header('Location: penjualan.php');
            exit;
        } else {
            die("Error saat update: " . $koneksi->error);
        }
    } else {
        // Jika tidak ada data, insert data baru
        $queryInsert = "INSERT INTO transaksi (id_jenis_hewan, harga, jumlah, status, id_petugas, jenis_hewan) 
                        VALUES ('$id_jenis_hewan', '$harga', '$jumlah', '$status', '$petugas_id', '$nama_hewan')";

        if ($koneksi->query($queryInsert) === TRUE) {
            header('Location: penjualan.php');
            exit;
        } else {
            die("Error saat insert: " . $koneksi->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penjualan</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: #ececec;
            color: #555555;
        }
        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            position: fixed;
            height: 100%;
            padding-top: 20px;
            padding-left: 20px;
        }
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="app-wrapper">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <?php include('petugas_sidebar.php'); ?>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <div class="container-fluid">
            <!-- Form Input Data -->
            <form method="POST" action="">
                <div class="card mb-3">
                    <div class="card-header rounded-1 text-white m-3 border-0" style="background: #59cd90;">
                        <h3 class="p-2 fs-5 m-auto">Tambah Transaksi</h3>
                    </div>
                    <div class="m-3">
                        <label for="id_jenis_hewan">Jenis Hewan</label>
                        <select class="form-control" id="id_jenis_hewan" name="id_jenis_hewan" required>
                            <option value="">Pilih Jenis Hewan</option>
                            <?php
                            while ($row = $resultHewan->fetch_assoc()) {
                                echo "<option value='{$row['id_hewan']}' data-harga='{$row['harga']}'>{$row['jenis_hewan']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="m-3">
                        <label for="harga">Biaya Retribusi per Ekor (Rp)</label>
                        <input type="number" class="form-control" id="harga" name="harga" required readonly>
                    </div>
                    <div class="m-3">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-submit btn-success btn-sm">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Ketika pilihan jenis hewan berubah
        $('#id_jenis_hewan').change(function() {
            var harga = $('option:selected', this).data('harga');
            $('#harga').val(harga); // Set nilai harga ke input harga
        });
    });
</script>

</body>
</html>