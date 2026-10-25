<?php
session_start();
include('../../config/koneksi.php'); // Koneksi ke database

// Cek apakah user memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Proses penambahan pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi input
    if (!empty($nama) && !empty($username) && !empty($password) && !empty($role)) {
        // Cek apakah username sudah ada
        $queryCekUsername = "SELECT * FROM pengguna WHERE username = '$username'";
        $resultCekUsername = $koneksi->query($queryCekUsername);

        if ($resultCekUsername->num_rows > 0) {
            echo "<script>alert('username sudah terdaftar.');</script>";
        } else {
            // Tambah data pengguna baru
            $queryInsert = "INSERT INTO pengguna (nama, username, password, role) VALUES ('$nama', '$username', '$password', '$role')";
            if ($koneksi->query($queryInsert) === TRUE) {
                echo "<script>alert('Pengguna berhasil ditambahkan.'); window.location.href = 'data_pengguna.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menambahkan pengguna.');</script>";
            }
        }
    } else {
        echo "<script>alert('Semua field wajib diisi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
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
    </style>
</head>
<body>
<div class="app-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <?php include('admin_sidebar.php'); ?>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <div class="container-fluid">
            <!-- Form Input Data -->
            <form method="POST" action="">
                <div class="card mb-3">
                    <div class="card-header rounded-1 text-white m-3 border-0" style="background: #59cd90;">
                        <h3 class="p-2 fs-5 m-auto">Tambah Pengguna</h3>
                    </div>
                    <div class="m-3">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="m-3">
                        <label for="username">Username</label>
                        <input type="username" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="m-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="m-3">
                        <label for="role">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-submit btn-success btn-sm">Tambah Pengguna</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
