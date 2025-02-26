<?php 
    require 'koneksi.php';

    if(isset($_GET['proses'])){
        $proses = $_GET['proses'];

        if(strlen($nama) > 40) {
            echo "<script>alert('TOOLONGCHARCTER'); window.location='index.php?page=mahasiswa&aksi=create'</script>";
            exit();
        }
        
        
        if($proses == 'simpan'){
            if(isset($_POST['submit'])){
                $nama = $_POST['nama']; 
                
                // Check nama length and replace if too long
                $nama = strlen($nama) > 40 ? "TOO LONG CHARACTER" : $nama;
                
                $email = $_POST['email'];
                $nim = $_POST['nim'];
                $gender = $_POST['gender'];
                $hobi = implode(", ", $_POST['hobby']);
                $alamat = $_POST['alamat'];
                $prodi_id = $_POST['prodi_id'];
        
        
                //check NIM sudah ada atau belum 
                // Modify the nama value if it exceeds 40 characters


                $cekNIM = mysqli_query($db, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
                if(mysqli_num_rows($cekNIM) > 0){
                    echo "<script>alert('Data gagal disimpan! NIM sudah terdaftar'); window.location='index.php?page=mahasiswa&aksi=create'</script>";
                    exit();
                }

                $query = mysqli_query($db, "INSERT INTO mahasiswa (nama, email, nim, gender, hobi, alamat, prodi_id) VALUES ('$nama', '$email', '$nim', '$gender', '$hobi', '$alamat', '$prodi_id')");
        
                if($query){
                    echo "<script>alert('Data Berhasil Disimpan'); window.location='index.php?page=mahasiswa'</script>";
                }else{
                    echo "<script>alert('Data Gagal Disimpan'); window.location='index.php?page=mahasiswa&aksi=create' </script>";
                }
            }
        }
        elseif ($proses == 'edit') {
            if (isset($_POST['submit'])) {
                $id = $_POST['id'];
                $nama = $_POST['nama'];

                // Check nama length and replace if too long
                $nama = strlen($nama) > 40 ? "TOO LONG CHARACTER" : $nama;
                
                $email = $_POST['email'];
                $nim = $_POST['nim'];
                $gender = $_POST['gender'];
                $hobi = implode(", ", $_POST['hobby']);
                $alamat = $_POST['alamat'];
                $prodi_id = $_POST['prodi_id'];

                // Get current NIM for this ID
                $getCurrentNIM = mysqli_query($db, "SELECT nim FROM mahasiswa WHERE id = '$id'");
                $currentData = mysqli_fetch_assoc($getCurrentNIM);
                
                // Only check for duplicate NIM if the NIM is being changed
                if ($currentData['nim'] != $nim) {
                    $cekNIM = mysqli_query($db, "SELECT * FROM mahasiswa WHERE nim = '$nim' AND id != '$id'");
                    if (mysqli_num_rows($cekNIM) > 0) {
                        echo "<script>alert('Data gagal diupdate! NIM sudah terdaftar oleh mahasiswa lain'); window.location='index.php?page=mahasiswa&aksi=update&id=$id'</script>";
                        exit();
                    }
                }

                // Update the record
                $query = mysqli_query($db, "UPDATE mahasiswa SET 
                    nama = '$nama',
                    email = '$email',
                    nim = '$nim',
                    gender = '$gender',
                    hobi = '$hobi',
                    alamat = '$alamat',
                    prodi_id = '$prodi_id'
                    WHERE id = '$id'");

                if ($query) {
                    echo "<script>alert('Data Berhasil Diupdate'); window.location='index.php?page=mahasiswa'</script>";
                } else {
                    echo "<script>alert('Data Gagal Diupdate'); window.location='index.php?page=mahasiswa&aksi=update&id=$id'</script>";
                }
            }
        }
        elseif($proses == 'hapus'){
            session_start();
            if($_SESSION['level'] != 'admin') {
                echo "<script>alert('Anda tidak memiliki izin untuk menghapus data.'); window.location='index.php?page=mahasiswa'</script>";
                exit; // Stop further execution
            }
            $id = $_GET['id'];
        
            $query = mysqli_query($db, "DELETE FROM mahasiswa WHERE id = '$id'");
            
            if($query){
                echo "<script>alert('  Berhasil Dihapus'); window.location='index.php?page=mahasiswa'</script>";
            }else{
                echo "<script>alert('Data Gagal Dihapus'); window.location='index.php?page=mahasiswa' </script>";
            }
        }
    }
?>