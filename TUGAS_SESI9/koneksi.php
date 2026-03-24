<?php
// =============================================
// koneksi.php
// File ini dipanggil di setiap file yang butuh
// akses ke database menggunakan include/require
// =============================================

$host     = "localhost";
$username = "root";
$password = "";
$database = "sesi9_bootcampeduwork";

// Buat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset agar karakter Indonesia (é, ñ, dll) tidak error
mysqli_set_charset($conn, "utf8");
?>
