<?php

    //konfigurasi database
    $host = "localhost";        // Nama host server database
    $user = "root";             // Username untuk database
    $password = "";             // Password untuk database
    $database = "web_trpl2d";   // Nama database yang akan diakses

    //membuat koneksi ke database
    $db = mysqli_connect($host, $user, $password, $database);

    //memeriksa apakah koneksi berhasil atau tidak
    if (!$db) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }
?>