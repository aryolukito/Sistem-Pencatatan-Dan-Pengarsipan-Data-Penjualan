<?php
session_start();
include('../../config/koneksi.php');

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Dapatkan bulan dan tahun saat ini
$TanggalTerbaru = date('d');
$BulanTerbaru = date('m');
$TahunTerbaru = date('Y');

// Total Transaksi
$queryTotalTransaksi = "SELECT SUM(jumlah) AS total_transaksi FROM transaksi WHERE status = 'final' AND MONTH(tanggal) ='$BulanTerbaru'  AND YEAR(tanggal) = '$TahunTerbaru'";
$hasilTransaksi = mysqli_query($koneksi, $queryTotalTransaksi);
$totalTransaksi = mysqli_fetch_assoc($hasilTransaksi)['total_transaksi'];

// Total Hewan
$queryTotalHewan = "SELECT COUNT(*) AS total_hewan FROM hewan";
$hasilHewan = mysqli_query($koneksi, $queryTotalHewan);
$totalHewan = mysqli_fetch_assoc($hasilHewan)['total_hewan'];

// Total Pemasukan
$queryTotalPemasukan = "SELECT SUM(harga * jumlah) AS total_pemasukan FROM transaksi WHERE status = 'final' AND MONTH(tanggal) = '$BulanTerbaru' AND YEAR(tanggal) = '$TahunTerbaru'";
$resultPemasukan = mysqli_query($koneksi, $queryTotalPemasukan);
$totalPemasukan = mysqli_fetch_assoc($resultPemasukan)['total_pemasukan'];

// Draft penjualan
$queryTransaksiBaru = "SELECT COUNT(*) AS transaksi_baru FROM transaksi WHERE status = 'draft'";
$hasilTransaksiBaru = mysqli_query($koneksi, $queryTransaksiBaru);
$transaksiBaru = mysqli_fetch_assoc($hasilTransaksiBaru)['transaksi_baru'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9f9f9;
            color: #333;
        }

        .sidebar {
            width: 250px;
            background-color: #e9ecef;
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

        .dashboard-box {
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: #fff;
            transition: all 0.3s ease;
            height: 100px;
        }

        .dashboard-box h3 {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .dashboard-box .d-flex {
            align-items: center;
        }

        .dashboard-box .text-content {
            margin-left: 15px;
            flex: 1;
        }

        .dashboard-box .text-content h3 {
            margin: 0;
            font-size: 18px;
        }

        .dashboard-box .text-content p {
            margin: 0;
            font-size: 12px;
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

            .dashboard-box {
                flex-direction: column;
                text-align: center;
                height: auto;
            }

            .dashboard-box .d-flex {
                flex-direction: column;
            }

            .dashboard-box .text-content {
                margin-left: 0;
            }

            .dashboard-box .text-content h3 {
                margin-top: 10px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="app-wrapper">
    <div class="sidebar">
        <!-- Sidebar content -->
        <?php include('admin_sidebar.php'); ?>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="dashboard-box" style="background: #16a085;">
                        <h3>Hai, <?php echo $_SESSION['nama']; ?></h3>
                        <p>Ini adalah Dashboard Anda. Anda dapat memantau dan mengelola aktivitas sistem  secara keseluruhan.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Box for Total Transaksi -->
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-box box-total-transaksi" style="background: #3fa7d6;">
                        <div class="d-flex">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#fff"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM208-800h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Z"/></svg>
                            </div>
                            <div class="text-content">
                                <h3>Total Transaksi</h3>    
                                <p class="value"><?= $totalTransaksi ?> Transaksi Bulanan</p>
                            </div>
                        </div>                    
                    </div>
                </div>

                <!-- Box for Total Hewan -->
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-box box-total-hewan" style="background: #59cd90;">
                        <div class="d-flex">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#fff"><path d="M180-475q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Zm180-160q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Zm240 0q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Zm180 160q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM266-75q-45 0-75.5-34.5T160-191q0-52 35.5-91t70.5-77q29-31 50-67.5t50-68.5q22-26 51-43t63-17q34 0 63 16t51 42q28 32 49.5 69t50.5 69q35 38 70.5 77t35.5 91q0 47-30.5 81.5T694-75q-54 0-107-9t-107-9q-54 0-107 9t-107 9Z"/></svg>
                            </div>
                            <div class="text-content">
                                <h3>Total Jenis Hewan</h3>
                                <p class="value"><?= $totalHewan ?> Jenis Hewan</p>
                            </div>
                        </div>                    
                    </div>
                </div>

                <!-- Box for Total Pemasukan -->
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-box box-total-pemasukan" style="background: #fac05e;">
                        <div class="d-flex">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#fff"><path d="M441-120v-86q-53-12-91.5-46T293-348l74-30q15 48 44.5 73t77.5 25q41 0 69.5-18.5T587-356q0-35-22-55.5T463-458q-86-27-118-64.5T313-614q0-65 42-101t86-41v-84h80v84q50 8 82.5 36.5T651-650l-74 32q-12-32-34-48t-60-16q-44 0-67 19.5T393-614q0 33 30 52t104 40q69 20 104.5 63.5T667-358q0 71-42 108t-104 46v84h-80Z"/></svg>
                            </div>
                            <div class="text-content">
                                <h3>Total Pemasukan</h3>
                                <p class="value">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
                            </div>
                        </div>                    
                    </div>
                </div>

                <!-- Box for Aktivitas Terbaru -->
                <div class="col-lg-3 col-md-6">
                    <div class="dashboard-box box-aktivitas-terbaru" style="background: #ee6352;">
                        <div class="d-flex">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#fff"><path d="M610-760q-21 0-35.5-14.5T560-810q0-21 14.5-35.5T610-860q21 0 35.5 14.5T660-810q0 21-14.5 35.5T610-760Zm0 660q-21 0-35.5-14.5T560-150q0-21 14.5-35.5T610-200q21 0 35.5 14.5T660-150q0 21-14.5 35.5T610-100Zm160-520q-21 0-35.5-14.5T720-670q0-21 14.5-35.5T770-720q21 0 35.5 14.5T820-670q0 21-14.5 35.5T770-620Zm0 380q-21 0-35.5-14.5T720-290q0-21 14.5-35.5T770-340q21 0 35.5 14.5T820-290q0 21-14.5 35.5T770-240Zm60-190q-21 0-35.5-14.5T780-480q0-21 14.5-35.5T830-530q21 0 35.5 14.5T880-480q0 21-14.5 35.5T830-430ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880v80q-134 0-227 93t-93 227q0 134 93 227t227 93v80Zm132-212L440-464v-216h80v184l148 148-56 56Z"/></svg>
                            </div>
                            <div class="text-content">
                                <h3>Draft Transaksi</h3>
                                <p class="value"><?= $transaksiBaru ?> Draft Transaksi</p>
                            </div>
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

