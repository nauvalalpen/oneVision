<?php 
    require 'koneksi.php';

    if(isset($_GET['proses'])){
        $proses = $_GET['proses'];
        
        if($proses == 'simpan'){
            if (isset($_POST['submit'])) {
                $nama_prodi = trim($_POST['nama_prodi']); 
                $jenjang = $_POST['jenjang'];
                $keterangan = trim($_POST['keterangan']);
            
                if (strlen($nama_prodi) < 2) {
                    echo "<script>alert('Nama Prodi harus memiliki minimal 2 karakter!'); window.location='index.php?page=prodi&aksi=create';</script>";
                    exit();
                }
            
                if (!preg_match("/^[a-zA-Z0-9\-']+$/", $nama_prodi)) {
                    echo "<script>alert('Nama Prodi hanya boleh berisi huruf, angka, tanda hubung (-), atau apostrof (\')!'); window.location='index.php?page=prodi&aksi=create';</script>";
                    exit();
                }
            
                if (strlen($keterangan) > 40) {
                    echo "<script>alert('Keterangan tidak boleh lebih dari 40 karakter!'); window.location='index.php?page=prodi&aksi=create';</script>";
                    exit();
                }
            
            
            
                // //check NIM sudah ada atau belum
                // $cekNIM = mysqli_query($db, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
                // if(mysqli_num_rows($cekNIM) > 0){
                //     echo "<script>alert('Data gagal disimpan! NIM sudah terdaftar'); window.location='index.php?page=mahasiswa&aksi=create'</script>";
                //     exit();
                // }

                $query = mysqli_query($db, "INSERT INTO prodi (nama_prodi, jenjang, keterangan) VALUES ('$nama_prodi', '$jenjang', '$keterangan')");
        
                if($query){
                    echo "<script>alert('Data Berhasil Disimpan'); window.location='index.php?page=prodi'</script>";
                }else{
                    echo "<script>alert('Data Gagal Disimpan'); window.location='index.php?page=prodi&aksi=create' </script>";
                }
            }
        }
        elseif ($proses == 'edit') {
            if (isset($_POST['submit'])) {
                $id = $_POST['id'];
                $nama_prodi = trim($_POST['nama_prodi']);
                $jenjang = $_POST['jenjang'];
                $keterangan = trim($_POST['keterangan']);
            
                if (strlen($nama_prodi) < 2) {
                    echo "<script>alert('Nama Prodi harus memiliki minimal 2 karakter!'); window.location='index.php?page=prodi&aksi=update&id=$id';</script>";
                    exit();
                }
            
                if (!preg_match("/^[a-zA-Z0-9\-']+$/", $nama_prodi)) {
                    echo "<script>alert('Nama Prodi hanya boleh berisi huruf, angka, tanda hubung (-), atau apostrof (\')!'); window.location='index.php?page=prodi&aksi=update&id=$id';</script>";
                    exit();
                }
            
                if (strlen($keterangan) < 40) {
                    echo "<script>alert('Keterangan tidak boleh lebih dari 40 karakter!'); window.location='index.php?page=prodi&aksi=update&id=$id';</script>";
                    exit();
                }
            
            
            
                
                $query = mysqli_query($db, "UPDATE prodi SET 
                                           nama_prodi='$nama_prodi', 
                                           jenjang='$jenjang', 
                                           keterangan='$keterangan' 
                                           WHERE id='$id'");

                if ($query) {
                    echo "<script>alert('Data Berhasil Diupdate'); window.location='index.php?page=prodi'</script>";
                } else {
                    echo "<script>alert('Data Gagal Diupdate'); window.location='index.php?page=prodi&aksi=update&id=$id'</script>";
                }
            }
        }
        elseif($proses == 'hapus'){
            session_start();
            if($_SESSION['level'] != 'admin') {
                echo "<script>alert('Anda tidak memiliki izin untuk menghapus data.'); window.location='index.php?page=prodi'</script>";
                exit; // Stop further execution
            }
            $id = $_GET['id'];
    
            $query = mysqli_query($db, "DELETE FROM prodi WHERE id='$id'");
            
            if ($query) {
                header('Location: index.php?page=prodi');
            }
        }
    }
?>