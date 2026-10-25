<?php
// File: includes/db_connection.php

$servername = "localhost";  // Sesuaikan dengan host database Anda
$username = "root";         // Sesuaikan dengan username MySQL Anda
$password = "";             // Sesuaikan dengan password MySQL Anda
$dbname = "sipencar";   // Nama database yang sudah dibuat

// Membuat koneksi
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
