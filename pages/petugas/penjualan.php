<?php
session_start();
include('../../config/koneksi.php'); // Koneksi ke database

// Cek apakah user sudah login dan memiliki role petugas
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    header('Location: ../login.php');
    exit;
}

// Ambil ID petugas dari session
$id_petugas = $_SESSION['id_pengguna'];

// Proses finalisasi jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_transactions'])) {
    
    $selectedTransactions = $_POST['selected_transactions'];

    // Gabungkan ID transaksi yang dipilih menjadi string
    $ids = implode(",", $selectedTransactions);

    // Query untuk mengambil transaksi draft yang dipilih
    $queryDraft = "SELECT t.id, t.id_jenis_hewan, t.harga, t.jumlah, t.tanggal, j.jenis_hewan
                   FROM transaksi t
                   JOIN hewan j ON t.id_jenis_hewan = j.id_hewan
                   WHERE t.id IN ($ids) AND t.status = 'draft'";

    $resultDraft = $koneksi->query($queryDraft);

    // Jika transaksi draft ditemukan
    if ($resultDraft->num_rows > 0) {
        // Proses untuk setiap transaksi draft yang dipilih
        while ($row = $resultDraft->fetch_assoc()) {
            $id_jenis_hewan = $row['id_jenis_hewan'];
            $harga = $row['harga'];
            $jumlah = $row['jumlah'];
            $tanggal = $row['tanggal'];

            // Cek apakah ada transaksi final dengan jenis hewan dan tanggal yang sama
            $queryFinalCheck = "SELECT id, jumlah FROM transaksi 
                                WHERE id_jenis_hewan = '$id_jenis_hewan' AND tanggal = '$tanggal' AND status = 'final'";
            $resultFinalCheck = $koneksi->query($queryFinalCheck);

            if ($resultFinalCheck->num_rows > 0) {
                // Jika sudah ada transaksi final, update jumlahnya
                $finalTransaction = $resultFinalCheck->fetch_assoc();
                $newJumlah = $finalTransaction['jumlah'] + $jumlah;

                // Update jumlah transaksi final
                $queryUpdateFinal = "UPDATE transaksi SET jumlah = '$newJumlah' 
                                     WHERE id = {$finalTransaction['id']}";
                $koneksi->query($queryUpdateFinal);
            } else {
                // Jika belum ada transaksi final, insert data baru
                $queryInsertFinal = "INSERT INTO transaksi (id_jenis_hewan, harga, jumlah, tanggal, status, id_petugas)
                                     VALUES ('$id_jenis_hewan', '$harga', '$jumlah', '$tanggal', 'final', '$id_petugas')";
                $koneksi->query($queryInsertFinal);
            }

            // Setelah diproses, hapus transaksi dari data draft
            $queryDeleteDraft = "DELETE FROM transaksi WHERE id = {$row['id']} AND status = 'draft'";
            $koneksi->query($queryDeleteDraft);
        }

        // Redirect ke halaman ini setelah berhasil finalisasi
        header('Location: penjualan.php');
        exit;
    } else {
        // Menampilkan pesan error jika tidak ada transaksi yang dipilih
        echo "Error: Tidak ada transaksi yang dipilih.";
    }
}

// Ambil parameter filter dari URL
$searchJenisHewan = isset($_GET['search_jenis_hewan']) ? $_GET['search_jenis_hewan'] : '';
$filterOption = isset($_GET['filter_option']) ? $_GET['filter_option'] : '';
$filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filterMonth = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';
$filterYear = isset($_GET['filter_year']) ? $_GET['filter_year'] : '';

// Query untuk Data Draft
$queryDraft = "SELECT t.id, t.id_jenis_hewan, t.harga, t.jumlah, t.status, j.jenis_hewan
               FROM transaksi t
               JOIN hewan j ON t.id_jenis_hewan = j.id_hewan
               WHERE t.status = 'draft'";
$resultDraft = $koneksi->query($queryDraft);

// Query untuk Data Final dengan pagination
$rowsPerPage = 30; // Maksimal 30 baris per halaman
$pageFinal = isset($_GET['final_page']) ? (int)$_GET['final_page'] : 1;
$offsetFinal = ($pageFinal - 1) * $rowsPerPage;

// Query dengan LIMIT untuk Data Final
$queryFinal = "SELECT t.id, t.id_jenis_hewan, t.harga, t.jumlah, t.status, j.jenis_hewan, t.tanggal
               FROM transaksi t
               JOIN hewan j ON t.id_jenis_hewan = j.id_hewan
               WHERE t.status = 'final'
               LIMIT $rowsPerPage OFFSET $offsetFinal";
$resultFinal = $koneksi->query($queryFinal);

// Hitung total data final
$totalFinal = $koneksi->query("SELECT COUNT(*) AS total FROM transaksi WHERE status = 'final'")->fetch_assoc()['total'];
$totalPagesFinal = ceil($totalFinal / $rowsPerPage);

// Hitung total pemasukan (total harga)
$totalPemasukan = 0;
$queryTotalFinal = "SELECT SUM(t.harga * t.jumlah) AS total_harga
                    FROM transaksi t
                    WHERE t.status = 'final'";
$resultTotalFinal = $koneksi->query($queryTotalFinal);
if ($resultTotalFinal->num_rows > 0) {
    $rowTotal = $resultTotalFinal->fetch_assoc();
    $totalPemasukan = $rowTotal['total_harga'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body{
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

        table {
            width: 100%;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

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
            <!-- Form untuk Finalisasi -->
            <form method="POST" action="">
                <!-- Tabel Data Draft-->
                <div class="card mb-3">
                    <div class="card-header rounded-1 text-white m-3 border-0" style="background: #59cd90;">
                        <h3 class="p-2 fs-5 m-auto">Draft Penjualan</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Hewan</th>
                                        <th>Biaya Retribusi per Ekor</th>
                                        <th>Jumlah</th>
                                        <th>Pemasukan</th>
                                        <th>Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($resultDraft->num_rows > 0) {
                                        $no = 1;
                                        while ($row = $resultDraft->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>{$no}</td>";
                                            echo "<td>{$row['jenis_hewan']}</td>";
                                            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                            echo "<td class='text-center'>{$row['jumlah']}</td>";
                                            echo "<td>Rp " . number_format($row['harga'] * $row['jumlah'], 0, ',', '.') . "</td>";
                                            echo "<td class='text-center'><input type='checkbox' name='selected_transactions[]' value='{$row['id']}'></td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>Tidak ada data</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pe-5 mt-2">
                            <button type="submit" class="btn btn-success btn-sm border-2">Konfirmasi</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabel Data Penjualan -->
            <div class="card mb-1">
                <div class="card-header rounded-1 text-white m-3 border-0" style="background: #fac05e;">
                    <h3 class="p-2 fs-5 m-auto">Data Penjualan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Hewan</th>
                                    <th>Biaya Retribusi per Ekor</th>
                                    <th>Jumlah</th>
                                    <th>Pemasukan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($resultFinal->num_rows > 0) {
                                    $no = 1;
                                    while ($row = $resultFinal->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$no}</td>";
                                        echo "<td>{$row['tanggal']}</td>";
                                        echo "<td>{$row['jenis_hewan']}</td>";
                                        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                        echo "<td class='text-center'>{$row['jumlah']}</td>";
                                        echo "<td>Rp " . number_format($row['harga'] * $row['jumlah'], 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginasi dan Total Pemasukan -->
                    <div class="row mb-3 d-flex justify-content-between align-items-start mt-3">
                        <!-- Pagination -->
                        <div class="col-12 col-md-6 text-center text-md-start d-flex justify-content-center justify-content-md-start">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item <?php echo ($pageFinal <= 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?final_page=<?php echo max(1, $pageFinal - 1); ?>">Sebelumnya</a>
                                    </li>
                                    <?php for ($i = 1; $i <= $totalPagesFinal; $i++): ?>
                                        <li class="page-item <?php echo ($i == $pageFinal) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?final_page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?php echo ($pageFinal >= $totalPagesFinal) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?final_page=<?php echo min($totalPagesFinal, $pageFinal + 1); ?>">Selanjutnya</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        <!-- Total Pemasukan -->
                        <div class="col-12 col-md-6 text-center text-md-end d-flex justify-content-center justify-content-md-end">
                            <button class="alert alert-warning w-auto d-flex align-items-center justify-content-center py-2 mb-2">
                                Total Pemasukan: Rp <?php echo number_format($totalPemasukan, 0, ',', '.'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>