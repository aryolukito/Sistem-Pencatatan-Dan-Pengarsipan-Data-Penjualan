<?php
session_start();
include('../../config/koneksi.php'); // Koneksi ke database

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $jenis_hewan = $_POST['jenis_hewan'];
    $harga = $_POST['harga'];

    // Validasi input
    if (!empty($jenis_hewan) && !empty($harga) && is_numeric($harga) && $harga > 0) {
        // Cek apakah jenis hewan sudah ada dalam database
        $queryCekHewan = "SELECT 1 FROM hewan WHERE jenis_hewan = '$jenis_hewan' LIMIT 1";
        $resultCekHewan = $koneksi->query($queryCekHewan);

        if ($resultCekHewan->num_rows > 0) {
            // Tampilkan alert jika sudah ada
            echo "<script>alert('Jenis Hewan sudah ada dalam database.');</script>";
        } else {
            // Tambah data jika tidak ada
            $queryInsert = "INSERT INTO hewan (jenis_hewan, harga) VALUES ('$jenis_hewan', '$harga')";
            if ($koneksi->query($queryInsert) === TRUE) {
                echo "<script>alert('Jenis Hewan berhasil ditambahkan');</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menambahkan data.');</script>";
            }
        }
    } else {
        echo "<script>alert('Data tidak valid. Pastikan semua field terisi dengan benar.');</script>";
    }
}

// Menampilkan data hewan yang ada
$queryJenisHewan = "SELECT * FROM hewan"; 
$resultJenisHewan = $koneksi->query($queryJenisHewan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jenis Hewan</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
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

        .table-container {
            margin: 30px;
        }
    </style>
</head>
<body>
<div class="app-wrapper">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <?php include('admin_sidebar.php'); ?>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <div class="container-fluid">
            <!-- Form Input Data -->
            <form method="POST" action="">
                <div class="card mb-3">
                    <div class="card-header rounded-1 text-white m-3 border-0" style="background: #59cd90;">
                        <h3 class="p-2 fs-5 m-auto">Tambah Jenis Hewan</h3>
                    </div>
                    <div class="m-3">
                        <label for="jenis_hewan">Nama Hewan</label>
                        <input type="text" class="form-control" id="jenis_hewan" name="jenis_hewan" required>
                    </div>
                    <div class="m-3">
                        <label for="harga">Harga per Ekor</label>
                        <input type="number" class="form-control" id="harga" name="harga" required min="1">
                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-submit btn-success btn-sm">Tambah Jenis Hewan</button>
                    </div>
                </div>
            </form>

            <!-- Tabel Jenis Hewan -->
            <div class="card mb-1">
                <div class="card-header rounded-1 text-white m-3 border-0" style="background: #fac05e;">
                    <h3 class="p-2 fs-5 m-auto">Daftar Jenis Hewan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class=" text-center">
                                    <th>No</th>
                                    <th>Jenis Hewan</th>
                                    <th>Biaya Retribusi per Ekor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Mengecek apakah ada data jenis hewan
                                    if ($resultJenisHewan->num_rows > 0) {
                                        $no = 1;
                                        while ($row = $resultJenisHewan->fetch_assoc()) {
                                            echo "<tr data-id='{$row['id_hewan']}'>";
                                            echo "<td class='text-center'>{$no}</td>";
                                            echo "<td>{$row['jenis_hewan']}</td>";
                                            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                            echo "<td class='text-center'>
                                                    <button class='btn btn-danger btn-sm' onclick='confirmDeleteHewan(" . $row['id_hewan'] . ")'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' height='18px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                                            <path d='M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm120-160q17 0 28.5-11.5T440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280Zm160 0q17 0 28.5-11.5T600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280Z'/>
                                                        </svg>                                                    
                                                    </button>
                                                </td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center'>Tidak ada data</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/validasi_hapus.js">
</script>
</body>
</html>
