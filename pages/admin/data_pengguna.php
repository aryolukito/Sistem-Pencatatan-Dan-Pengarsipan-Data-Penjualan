<?php
session_start();
include('../../config/koneksi.php');


// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

$current_user_id = isset($_SESSION['id_pengguna']) ? $_SESSION['id_pengguna'] : 0;

// Menampilkan data pengguna
$queryPengguna = "SELECT * FROM pengguna";
$resultPengguna = $koneksi->query($queryPengguna);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
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
            .text-center {
                margin-bottom: 2px;
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
            <!-- Tombol Tambah Pengguna -->
            <div class="d-flex justify-content-end mb-3">
                <a href="tambah_pengguna.php" class="btn btn-primary">Tambah Pengguna</a>
            </div>

            <!-- Tabel Data Pengguna -->
            <div class="card mb-1">
                <div class="card-header rounded-1 text-white m-3 border-0" style="background: #fac05e;">
                    <h3 class="p-2 fs-5 m-auto">Daftar Pengguna</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class=" text-center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if ($resultPengguna->num_rows > 0) {
                                    $no = 1;
                                    while ($row = $resultPengguna->fetch_assoc()) {
                                        echo "<tr data-id='{$row['id_pengguna']}'>";
                                        echo "<td class='text-center'>{$no}</td>";
                                        echo "<td>{$row['nama']}</td>";
                                        echo "<td>{$row['username']}</td>";
                                        echo "<td>{$row['role']}</td>";
                                        echo "<td class='text-center me-2'>
                                                <a href='edit_pengguna.php?id_pengguna=" . $row['id_pengguna'] . "' class='btn btn-warning btn-sm me-1'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' height='18px' viewBox='0 -960 960 960' width='18px' fill='#fff'><path d='M120-120v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm584-528 56-56-56-56-56 56 56 56Z'/></svg> 
                                                </a>";
                                        // Tampilkan tombol hapus hanya jika bukan user yang sedang login
                                        if ($row['id_pengguna'] != $current_user_id) {
                                            echo "<button class='btn btn-danger btn-sm ms-1' onclick='confirmDeletePengguna(" . $row['id_pengguna'] . ")'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' height='18px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                                        <path d='M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm120-160q17 0 28.5-11.5T440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280Zm160 0q17 0 28.5-11.5T600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280Z'/>
                                                    </svg>                                                    
                                                </button>";
                                        }
                                        echo "</td></tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data pengguna.</td></tr>";
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

<script src="../..//assets/js/validasi_hapus.js"></script>
</body>
</html>
