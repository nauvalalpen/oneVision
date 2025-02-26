<?php 
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  var_dump($_FILES);
  
  require 'koneksi.php';

    if(isset($_GET['proses'])){
        $proses = $_GET['proses'];
            
// At the beginning of the 'simpan' process
        if ($proses == 'simpan') {
            if (isset($_POST['submit'])) {
                $nip = $_POST['nip'];
                $nama_dosen = $_POST['nama_dosen'];
                $prodi_id = $_POST['prodi_id'];

                // Create uploads directory if it doesn't exist
                $upload_dir = "uploads/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // File upload handling with error checking
                if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                    $foto_name = $_FILES['foto']['name'];
                    $foto_tmp = $_FILES['foto']['tmp_name'];
                    $foto_folder = $upload_dir . $foto_name;

                    // Check if NIP already exists
                    $cekNIP = mysqli_query($db, "SELECT * FROM dosen WHERE nip = '$nip'");
                    if (mysqli_num_rows($cekNIP) > 0) {
                        echo "<script>alert('Data gagal disimpan! NIP sudah terdaftar'); window.location='index.php?page=dosen&aksi=create'</script>";
                        exit();
                        }

                    // Move the uploaded file with error checking
                    if (move_uploaded_file($foto_tmp, $foto_folder)) {
                        $query = mysqli_query($db, "INSERT INTO dosen (nip, nama_dosen, prodi_id, foto) 
                                                VALUES ('$nip', '$nama_dosen', '$prodi_id', '$foto_folder')");

                        if ($query) {
                            echo "<script>alert('Data Berhasil Disimpan'); window.location='index.php?page=dosen'</script>";
                        } else {
                            echo "<script>alert('Data Gagal Disimpan'); window.location='index.php?page=dosen&aksi=create'</script>";
                        }
                    } else {
                        echo "<script>alert('Upload foto gagal: " . error_get_last()['message'] . "'); window.location='index.php?page=dosen&aksi=create'</script>";
                    }
                } else {
                    echo "<script>alert('File tidak ditemukan atau error upload: " . $_FILES['foto']['error'] . "'); window.location='index.php?page=dosen&aksi=create'</script>";
                }
            }
        }

        
        elseif ($proses == 'edit') {
            if (isset($_POST['submit'])) {
                $old_nip = $_POST['old_nip'];
                $nip = $_POST['nip'];
                $nama_dosen = $_POST['nama_dosen'];
                $prodi_id = $_POST['prodi_id'];
        
                // File upload handling
                if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                    $foto_name = $_FILES['foto']['name'];
                    $foto_tmp = $_FILES['foto']['tmp_name'];
                    $foto_folder = "uploads/" . $foto_name;
                    
                    // Delete old photo if exists
                    if(!empty($_POST['existing_foto']) && file_exists($_POST['existing_foto'])) {
                        unlink($_POST['existing_foto']);
                    }
                    
                    // Move new photo
                    if(move_uploaded_file($foto_tmp, $foto_folder)) {
                        $foto_path = $foto_folder;
                    } else {
                        echo "<script>alert('Gagal mengunggah foto baru'); window.location='index.php?page=dosen&aksi=update&id=$old_nip'</script>";
                        exit();
                    }
                } else {
                    $foto_path = $_POST['existing_foto'];
                }
        
                $query = mysqli_query($db, "UPDATE dosen SET 
                    nip = '$nip',
                    nama_dosen = '$nama_dosen',
                    prodi_id = '$prodi_id',
                    foto = '$foto_path'
                    WHERE nip = '$old_nip'");
        
                if ($query) {
                    echo "<script>alert('Data Berhasil Diupdate'); window.location='index.php?page=dosen'</script>";
                } else {
                    echo "<script>alert('Data Gagal Diupdate'); window.location='index.php?page=dosen&aksi=update&id=$old_nip'</script>";
                }
            }
        }
        
        
        elseif($proses == 'hapus'){
            session_start();
            if($_SESSION['level'] != 'admin') {
                echo "<script>alert('Anda tidak memiliki izin untuk menghapus data.'); window.location='index.php?page=dosen'</script>";
                exit; // Stop further execution
            }
            $nip = $_GET['id'];
        
            $query = mysqli_query($db, "DELETE FROM dosen WHERE nip = '$nip'");
            
            if($query){
                echo "<script>alert('Data Berhasil Dihapus'); window.location='index.php?page=dosen'</script>";
            }else{
                echo "<script>alert('Data Gagal Dihapus'); window.location='index.php?page=dosen'</script>";
            }
        }
    }
?>
