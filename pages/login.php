<?php

session_start();
include('../config/koneksi.php');  // Memanggil file koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa username dan password
    $sql = "SELECT * FROM pengguna WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();
        $_SESSION['id_pengguna'] = $user['id_pengguna'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header('Location: admin/admin_dashboard.php');
        } else {
            header('Location: petugas/petugas_dashboard.php');
        }
    } else {
        $error_message = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Pencatatan Dan Pengarsipan Data Penjualan</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body, html {
            font-family: 'Poppins', sans-serif;
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            color: #555555;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 620px;
            min-height: 400px;
            width: 100%;
            padding: 40px;
            border: 2px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            margin: 20px;
        }

        .logo-container {
            min-height: 350px;
            text-align: center;
            margin-right: 30px;
            background-color: #198754;
            padding: 20px;
            border-radius: 10px;
        }

        .logo-container img {
            max-width: 100px;
            margin-bottom: 50px;
        }

        .logo-container h4 {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 50px;
        }

        .form-container {
            min-height: 350px;
            width: 100%;
            max-width: 300px;
        }

        .form-container h2 {
            text-align: center;
        }

        .form-container .form-label {
            font-weight: bold;
        }
        .btn-login {
            align-items: center;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                padding: 30px;
            }

            .logo-container {
                margin-right: 0;
                margin-bottom: 20px;
            }

            .form-container {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .logo-container h4 {
                font-size: 20px;
            }

            .form-container h2 {
                font-size: 20px;
            }
        }

        /* Animasi */
        .modal-content {
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            background-color: #ff5c5c;
            color: white;
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        .modal-body {
            font-size: 12px;
            color: #333;
        }

        .btn-custom {
            background-color: #ff5c5c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-custom:hover {
            background-color: #e04e4e;
        }
        .modal-dialog {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo dan Nama Sistem -->
        <div class="logo-container">
            <img src="../assets/gambar/logo.png" alt="Logo">
            <h4>Sistem Informasi Pencatatan Dan Pengarsipan Data Penjualan</h4>
            <small class="text-white text-wrap text-center text-footer" style="margin: 0;">&copy; 2025 UPT PTR dan RPH.</small>
        </div>

        <!-- Form Login -->
        <div class="form-container">
        <br><h4>Hai, <span class="text-success">Selamat Datang</span></h4><br>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-50 btn-login">Login</button>
            </form>
        </div>
    </div>

    <!-- Modal Error Message -->
    <?php if (isset($error_message)) { ?>
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Kesalahan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $error_message; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>

    <script>
        <?php if (isset($error_message)) { ?>
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php } ?>
    </script>
</body>
</html>