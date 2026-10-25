<?php
session_start();
include('koneksi.php');

// Cek apakah user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo "error_not_logged_in";
    exit;
}

// Periksa apakah parameter ID dikirim dan tentukan jenis penghapusan
if (isset($_GET['id_hewan'])) {

    // Penghapusan data hewan
    $id_hewan = (int)$_GET['id_hewan'];
    
    $query = "DELETE FROM hewan WHERE id_hewan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_hewan);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

} elseif (isset($_GET['id'])) {

    // Penghapusan data transaksi
    $id_transaksi = (int)$_GET['id'];
    
    $query = "DELETE FROM transaksi WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_transaksi);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

} elseif (isset($_GET['id_pengguna'])) {
    $id_pengguna = (int)$_GET['id_pengguna'];

    // Lakukan penghapusan
    $queryPengguna = "DELETE FROM pengguna WHERE id_pengguna = ?";
    $stmt = $koneksi->prepare($queryPengguna);
    $stmt->bind_param("i", $id_pengguna);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}

$koneksi->close();
?>