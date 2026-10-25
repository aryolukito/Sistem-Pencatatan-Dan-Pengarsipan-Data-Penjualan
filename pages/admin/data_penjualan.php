<?php
session_start();
include('../../config/koneksi.php');

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

$id_admin = $_SESSION['id_pengguna'];

// Ambil nilai filter pencarian dan tanggal
$searchJenisHewan = isset($_GET['search_jenis_hewan']) ? $_GET['search_jenis_hewan'] : '';
$filterOption = isset($_GET['filter_option']) ? $_GET['filter_option'] : '';
$filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filterMonth = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';
$filterYear = isset($_GET['filter_year']) ? $_GET['filter_year'] : '';

// Query untuk Data Final dengan filter
$whereConditions = "WHERE t.status = 'final'";

// Menambahkan kondisi pencarian jika ada
if (!empty($searchJenisHewan)) {
    $whereConditions .= " AND j.jenis_hewan LIKE '%$searchJenisHewan%'";
}

// Menambahkan kondisi filter berdasarkan opsi yang dipilih
if ($filterOption == 'tanggal' && !empty($filterDate)) {
    $whereConditions .= " AND t.tanggal = '$filterDate'";
} elseif ($filterOption == 'bulan' && !empty($filterMonth)) {
    $whereConditions .= " AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$filterMonth'";
} elseif ($filterOption == 'tahun' && !empty($filterYear)) {
    $whereConditions .= " AND YEAR(t.tanggal) = '$filterYear'";
}

// Query untuk mengambil data transaksi final dengan pagination dan filter
$rowsPerPage = 25;
$pageFinal = isset($_GET['final_page']) ? (int)$_GET['final_page'] : 1;
$offsetFinal = ($pageFinal - 1) * $rowsPerPage;

// Ambil data transaksi final dengan filter dan limit berdasarkan pagination
$queryFinal = "SELECT t.id, t.id_jenis_hewan, t.harga, t.jumlah, t.status, j.jenis_hewan, t.tanggal
               FROM transaksi t
               JOIN hewan j ON t.id_jenis_hewan = j.id_hewan
               $whereConditions
               LIMIT $rowsPerPage OFFSET $offsetFinal";
$resultFinal = $koneksi->query($queryFinal);

// Hitung total data final setelah filter
$totalFinal = $koneksi->query("SELECT COUNT(*) AS total FROM transaksi t
                                JOIN hewan j ON t.id_jenis_hewan = j.id_hewan
                                $whereConditions")->fetch_assoc()['total'];
$totalPagesFinal = ceil($totalFinal / $rowsPerPage);

// Hitung total harga untuk transaksi dengan status final setelah filter
$queryTotalFinal = "SELECT SUM(t.harga * t.jumlah) AS total_harga
                    FROM transaksi t
                    JOIN hewan j ON t.id_jenis_hewan = j.id_hewan
                    $whereConditions";
$resultTotalFinal = $koneksi->query($queryTotalFinal);
$totalPemasukan = 0;

if ($resultTotalFinal->num_rows > 0) {
    $rowTotal = $resultTotalFinal->fetch_assoc();
    $totalPemasukan = $rowTotal['total_harga'];
}

setlocale(LC_TIME, 'id_ID.UTF-8');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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

        table {
            width: 100%;
            font-size: 12px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
        }

        .form-row .col-md-3 {
            margin-bottom: 10px;
        }

        .download-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
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
        <?php include('admin_sidebar.php'); ?>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <div class="container-fluid">

            <!-- Form Pencarian dan Filter -->
            <div class="card mb-1">
                <div class="card-body">
                    <form action="" method="GET" class="form-row gap-1">
                        <!-- Pencarian -->
                        <div class="col-md-3 mb-0">
                            <input type="text" name="search_jenis_hewan" class="form-control" placeholder="Cari Hewan" value="<?php echo $searchJenisHewan; ?>">
                        </div>
                        <!-- Filter -->
                        <div class="col-md-2 mb-0">
                            <select name="filter_option" class="form-control" onchange="toggleFilters(this.value)">
                                <option value="">Pilih Filter</option>
                                <option value="tanggal" <?php echo ($filterOption == 'tanggal') ? 'selected' : ''; ?>>Tanggal</option>
                                <option value="bulan" <?php echo ($filterOption == 'bulan') ? 'selected' : ''; ?>>Bulan</option>
                                <option value="tahun" <?php echo ($filterOption == 'tahun') ? 'selected' : ''; ?>>Tahun</option>
                            </select>
                        </div>
                        <!-- Filter Tanggal -->
                        <div class="col-md-2 mb-0" id="filter_date" style="display: <?php echo ($filterOption == 'tanggal') ? 'block' : 'none'; ?>;">
                            <input type="date" name="filter_date" class="form-control" value="<?php echo $filterDate; ?>">
                        </div>
                        <!--Filter Bulan -->
                        <div class="col-md-2 mb-0" id="filter_month" style="display: <?php echo ($filterOption == 'bulan') ? 'block' : 'none'; ?>;">
                            <input type="month" name="filter_month" class="form-control" value="<?php echo $filterMonth; ?>">
                        </div>
                        <!-- Filter Tahun -->
                        <div class="col-md-2 mb-0" id="filter_year" style="display: <?php echo ($filterOption == 'tahun') ? 'block' : 'none'; ?>;">
                            <input type="number" name="filter_year" class="form-control" value="<?php echo $filterYear; ?>" placeholder="Tahun">
                        </div>

                        <!-- Tombol Pencarian -->
                        <div class="col-md-2 mb-0">
                            <button type="submit" class="btn btn-primary w-40">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M371-289q-121.71 0-206.86-85.2Q79-459.41 79-581.2 79-703 164.2-788q85.21-85 207-85Q493-873 578-787.86q85 85.15 85 206.86 0 47-11.5 85T620-429l214 215q19 20 19 47.48 0 27.48-20 47.52-19.8 19-47.4 19-27.6 0-47.6-19L526.47-332Q497-313 457-301t-86 12Zm-.41-136q66.59 0 111.5-44.5T527-580.59q0-66.59-44.91-111.5T370.59-737Q304-737 259.5-692.09T215-580.59q0 66.59 44.5 111.09T370.59-425Z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Penjualan -->
            <div class="card mb-1">
                <div class="card-header rounded-1 text-white m-3 border-0" style="background: #fac05e;">
                    <h3 class="p-2 fs-5 m-auto">Laporan Penjualan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class=" text-center">
                                    <th>No</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Hewan</th>
                                    <th>Harga Retribusi per Ekor</th>
                                    <th>Jumlah</th>
                                    <th>Pemasukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($resultFinal->num_rows > 0) {
                                        $no = $offsetFinal + 1;
                                        while ($row = $resultFinal->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>{$no}</td>";
                                            echo "<td>" . $row['tanggal'] . "</td>";
                                            echo "<td>{$row['jenis_hewan']}</td>";
                                            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                            echo "<td class='text-center'>{$row['jumlah']}</td>";
                                            echo "<td>Rp " . number_format($row['harga'] * $row['jumlah'], 0, ',', '.') . "</td>";
                                            echo "<td class='text-center'><button class='btn btn-danger btn-sm' onclick='confirmDeleteTransaksi(" . $row['id'] . ")'>
                                                            <svg xmlns='http://www.w3.org/2000/svg' height='18px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                                                <path d='M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm120-160q17 0 28.5-11.5T440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280Zm160 0q17 0 28.5-11.5T600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280Z'/>
                                                            </svg>
                                                    </button></td>";
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

                    <!-- Paginasi -->
                    <div class="row mb-3 d-flex justify-content-between align-items-start mt-3">
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

                        <!-- Cetak PDF dan Total Pemasukan (paling kanan) -->
                        <div class="col-md-6 text-end d-flex justify-content-end">
                            <!-- Cetak PDF -->
                            <a href="cetak_pdf.php?search_jenis_hewan=<?php echo $searchJenisHewan; ?>&filter_option=<?php echo $filterOption; ?>&filter_date=<?php echo $filterDate; ?>&filter_month=<?php echo $filterMonth; ?>&filter_year=<?php echo $filterYear; ?>" class="btn btn-danger d-flex align-items-center justify-content-center w-auto py-2 mb-2 me-2" target="_blank" style="max-width: 150px;">
                                <!-- Ikon -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="18px" fill="#fff">
                                    <path d="M720-680H240v-160h480v160Zm0 220q17 0 28.5-11.5T760-500q0-17-11.5-28.5T720-540q-17 0-28.5 11.5T680-500q0 17 11.5 28.5T720-460Zm-80 260v-160H320v160h320Zm80 80H240v-160H80v-240q0-51 35-85.5t85-34.5h560q51 0 85.5 34.5T880-520v240H720v160Z"/>
                                </svg>
                                <span class="ms-2">CETAK PDF</span>
                            </a>

                            <!-- Total Pemasukan -->
                            <button class="alert alert-warning w-auto d-flex align-items-center justify-content-center py-2 mb-2">Total Pemasukan: Rp <?php echo number_format($totalPemasukan, 0, ',', '.'); ?></button>
                        </div>
                    </div>       
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/validasi_hapus.js"></script>
<script src="../../assets/js/filter.js"></script>

</body>
</html>