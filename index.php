<?php 

require 'koneksi.php';

session_start();
if(isset($_SESSION['login']) == false){
    header("Location:login.php");
    exit();
}

$userId = $_SESSION['nama_lengkap']; // Assuming you store user_id in session
$query = mysqli_query($db, "SELECT nama_lengkap FROM users WHERE id = '$userId'");
$user = mysqli_fetch_assoc($query);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Akademik</title>
    <!-- Include only these -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-body-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Siakad</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="index.php?page=mahasiswa">Mahasiswa</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="index.php?page=dosen">Dosen</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="index.php?page=prodi">Prodi</a>
                </li>
                <?php if($_SESSION['level'] == 'admin'){ ?>
                <li class="nav-item">
                <a class="nav-link" href="index.php?page=mata_kuliah">Mata Kuliah</a>
                </li>
                <?php } ?>
                <?php if($_SESSION['level'] == 'admin'){ ?>
                <li class="nav-item">
                <a class="nav-link" href="index.php?page=user">User</a>
                </li>
                <?php } ?>
                <li class="nav-item dropdown">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $_SESSION['nama_lengkap']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>

            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            </div>
        </div>
    </nav>
    <div class="container my-4">

        <?php 
        
            $page = isset($_GET['page']) ? $_GET['page'] : 'home'; 
            if($page == "home") include 'home.php';
            if($page == "mahasiswa") include 'mahasiswa.php';
            if($page == "dosen") include 'dosen.php';
            if($page == "prodi") include 'prodi.php';
            if($page == "user") include 'user.php';
            if($page == "mata_kuliah") include 'mata_kuliah.php';

        ?>    

    </div>

    <div class="text-center py-3 bg-dark text-light">
        <!-- footer -->
         
        Sistem Informasi Akademik &copy; 2024
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function () {
        // Check if DataTable is already initialized, then destroy it
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy(); // Destroy existing instance
        }

        // Initialize DataTable
        $('#dataTable').DataTable({
            dom: 'Blrtip', // Modified dom parameter (removed search)
            buttons: ['copy', 'excel', 'pdf'],
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            searching: false,
            language: {
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>


  </body>
</html>