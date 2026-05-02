<?php
// config.php

$host = 'localhost';
$db_user = 'root'; 
$db_pass = '';     
$db_name = 'db_monstera';

// Membuat koneksi ke database
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>