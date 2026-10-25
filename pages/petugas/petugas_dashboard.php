<?php
session_start();
include('../../config/koneksi.php');

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    header('Location: ../login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
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

            .dashboard-box {
                flex-direction: column;
                text-align: center;
            }

            .dashboard-box .d-flex {
                flex-direction: column;
            }

            .dashboard-box .text-content {
                margin-left: 0;
                text-align: center;
            }

            .dashboard-box .text-content h3 {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <!-- Sidebar content -->
    <?php include('petugas_sidebar.php'); ?>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="dashboard-box" style="background: #16a085;">
                    <h3>Hai, <?php echo $_SESSION['nama']; ?></h3>
                    <p>Anda telah login sebagai <?php echo $_SESSION['role']; ?> pencatatan. Disini Anda dapat melakukan pencatatan transaksi dan melihat data transaksi yang telah dilakukan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

