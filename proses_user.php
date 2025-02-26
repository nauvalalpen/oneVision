<?php
session_start();
include 'koneksi.php';

if($_SESSION['level'] != 'admin'){
    header("Location: index.php");
    exit();
}

if(isset($_GET['proses'])){
    if($_GET['proses'] == 'hapus'){
        $id = $_GET['id'];
        mysqli_query($db, "DELETE FROM users WHERE id='$id'");
        header("Location: index.php?page=user");
    }
}

if(isset($_POST['proses'])){
    if($_POST['proses'] == 'tambah'){
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $nama_lengkap = $_POST['nama_lengkap'];
        $level = $_POST['level'];

        $cekEmail = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
        if(mysqli_num_rows($cekEmail) > 0){
            echo "<script>alert('Email sudah terdaftar!'); window.location='index.php?page=user'</script>";
        } else {
            mysqli_query($db, "INSERT INTO users (email, password, nama_lengkap, level) VALUES ('$email', '$password', '$nama_lengkap', '$level')");
            header("Location: index.php?page=user");
        }
    }
    
    if($_POST['proses'] == 'update'){
        $id = $_POST['id'];
        $email = $_POST['email'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $level = $_POST['level'];
        
        if(!empty($_POST['password'])){
            $password = md5($_POST['password']);
            mysqli_query($db, "UPDATE users SET email='$email', password='$password', nama_lengkap='$nama_lengkap', level='$level' WHERE id='$id'");
        } else {
            mysqli_query($db, "UPDATE users SET email='$email', nama_lengkap='$nama_lengkap', level='$level' WHERE id='$id'");
        }
        header("Location: index.php?page=user");
    }
}
