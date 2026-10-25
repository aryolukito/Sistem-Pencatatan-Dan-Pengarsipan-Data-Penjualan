<?php
session_start();
include('../../config/koneksi.php'); // Pastikan koneksi database disertakan

// Cek apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Mendapatkan ID pengguna dari URL
if (!isset($_GET['id_pengguna']) || empty($_GET['id_pengguna'])) {
    header("Location: data_pengguna.php");
    exit();
}
$id_pengguna = (int)$_GET['id_pengguna'];

// Ambil data pengguna berdasarkan ID
$queryPengguna = "SELECT * FROM pengguna WHERE id_pengguna = $id_pengguna";
$resultPengguna = $koneksi->query($queryPengguna);

if ($resultPengguna->num_rows == 0) {
    header("Location: data_pengguna.php");
    exit();
}
$pengguna = $resultPengguna->fetch_assoc();

// Proses update data pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Validasi input tidak boleh kosong
    if (empty($nama) || empty($username) || empty($password) || empty($role)) {
        echo "<script>alert('Harap isi semua field.');</script>";
    } else {
        // Cek apakah username sudah digunakan oleh pengguna lain
        $queryCekUsername = "SELECT id_pengguna FROM pengguna WHERE username = ? AND id_pengguna != ?";
        $stmt = $koneksi->prepare($queryCekUsername);
        $stmt->bind_param("si", $username, $id_pengguna);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Username sudah digunakan! Silakan pilih username lain.');</script>";
        } else {
            // Update data pengguna jika username belum digunakan
            $queryUpdate = "UPDATE pengguna SET nama = ?, username = ?, password = ?, role = ? WHERE id_pengguna = ?";
            $stmt = $koneksi->prepare($queryUpdate);
            $stmt->bind_param("ssssi", $nama, $username, $password, $role, $id_pengguna);

            if ($stmt->execute()) {
                echo "<script>alert('Data pengguna berhasil diperbarui.'); window.location.href = 'data_pengguna.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat memperbarui data.');</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
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
    <div class="sidebar p-3">
        <?php include('admin_sidebar.php'); ?>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <div class="container-fluid">
            <!-- Form Edit Pengguna -->
            <form method="POST" action="">
                <div class="card mb-3">
                    <div class="card-header rounded-1 text-white m-3 border-0" style="background: #59cd90;">
                        <h3 class="p-2 fs-5 m-auto">Edit Pengguna</h3>
                    </div>
                    <div class="m-3">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $pengguna['nama']; ?>" required>
                    </div>
                    <div class="m-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $pengguna['username']; ?>" required>
                    </div>
                    <div class="m-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo $pengguna['password']; ?>" required>
                    </div>
                    <div class="m-3">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin" <?php echo ($pengguna['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="petugas" <?php echo ($pengguna['role'] == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
                        </select>
                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-submit btn-success btn-sm">Simpan Perubahan</button>
                        <a href="data_pengguna.php" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
