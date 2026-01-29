<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_banksampah";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");
?>
