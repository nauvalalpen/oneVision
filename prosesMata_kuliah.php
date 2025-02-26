<?php
require 'koneksi.php';

if(isset($_GET['proses'])) {
    $proses = $_GET['proses'];
    
    if($proses == 'simpan') {
        if(isset($_POST['submit'])) {
            $kode_mk = $_POST['kode_mk'];
            $nama_mk = $_POST['nama_mk'];
            $sks = $_POST['sks'];
            $dosen_nip = $_POST['dosen_nip'];
            $semester = $_POST['semester'];
            
            $cekKode = mysqli_query($db, "SELECT * FROM mata_kuliah WHERE kode_mk = '$kode_mk'");
            if(mysqli_num_rows($cekKode) > 0) {
                echo "<script>alert('Data gagal disimpan! Kode Mata Kuliah sudah terdaftar'); window.location='index.php?page=mata_kuliah&aksi=create'</script>";
                exit();
            }
            
            $query = mysqli_query($db, "INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, dosen_nip, semester) 
                     VALUES ('$kode_mk', '$nama_mk', '$sks', '$dosen_nip', '$semester')");
            
            if($query) {
                echo "<script>alert('Data Berhasil Disimpan'); window.location='index.php?page=mata_kuliah'</script>";
            } else {
                echo "<script>alert('Data Gagal Disimpan'); window.location='index.php?page=mata_kuliah&aksi=create'</script>";
            }
        }
    }
    elseif($proses == 'edit') {
        if(isset($_POST['submit'])) {
            $kode_mk = $_POST['kode_mk'];
            $nama_mk = $_POST['nama_mk'];
            $sks = $_POST['sks'];
            $dosen_nip = $_POST['dosen_nip'];
            $semester = $_POST['semester'];
            
            $query = mysqli_query($db, "UPDATE mata_kuliah SET 
                     nama_mk='$nama_mk',
                     sks='$sks',
                     dosen_nip='$dosen_nip',
                     semester='$semester'
                     WHERE kode_mk='$kode_mk'");
                     
            if($query) {
                echo "<script>alert('Data Berhasil Diupdate'); window.location='index.php?page=mata_kuliah'</script>";
            } else {
                echo "<script>alert('Data Gagal Diupdate'); window.location='index.php?page=mata_kuliah'</script>";
            }
        }
    }
    elseif($proses == 'hapus') {
        session_start();
        if($_SESSION['level'] != 'admin') {
            echo "<script>alert('Anda tidak memiliki izin untuk menghapus data.'); window.location='index.php?page=mata_kuliah'</script>";
            exit;
        }
        
        $kode = $_GET['kode'];
        $query = mysqli_query($db, "DELETE FROM mata_kuliah WHERE kode_mk = '$kode'");
        
        if($query) {
            echo "<script>alert('Data Berhasil Dihapus'); window.location='index.php?page=mata_kuliah'</script>";
        } else {
            echo "<script>alert('Data Gagal Dihapus'); window.location='index.php?page=mata_kuliah'</script>";
        }
    }
}
?>
