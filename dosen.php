<?php

require 'koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

switch ($aksi) {
    case "read":

?>

        <h1>Data Dosen</h1>
        <a href="index.php?page=dosen&aksi=create " class="btn btn-danger">Tambah Data</a>
        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <style>
                    tbody tr {
                        background-color: yellow;
                    }
                    tbody td {
                        background-color: orange;
                    }
<<<<<<< HEAD

=======
>>>>>>> 7c70ee53d3b7f3ae581e29575863c609a3f85e12
                </style>
                    <th scope="col">No</th>
                    <th scope="col">NIP</th>
                    <th scope="col">Nama Dosen</th>
                    <th scope="col">Prodi</th>
                    <th scope="col">Foto</th>
<<<<<<< HEAD
                    <th scope="col">Action</th>
=======
                    <th scope="col">Aksi</th>
>>>>>>> 7c70ee53d3b7f3ae581e29575863c609a3f85e12
                </tr>
            </thead>
            <tbody>
                <?php

                $queryMhs = mysqli_query($db, "SELECT dosen.*, prodi.nama_prodi 
            FROM dosen 
            LEFT JOIN prodi ON dosen.prodi_id = prodi.id ORDER BY id ASC");

                $nomor = 1;

                while ($data = mysqli_fetch_array($queryMhs)) {

                ?>
                    <tr>
                        <th scope="row"><?= $nomor++ ?></th>
                        <td><?= $data['nip'] ?></td>
                        <td><?= $data['nama_dosen'] ?></td>
                        <td><?= $data['nama_prodi'] ?></td>
                        <td><img src="<?= $data['foto'] ?>" alt="Foto" width="100"></td>
                        <td>
                            <a href="index.php?page=dosen&aksi=update&id=<?= $data['nip'] ?>" class="btn btn-warning">Edit</a>
                            <?php if ($_SESSION['level'] == 'admin') { ?>
<<<<<<< HEAD
                                <a href="proses_dosen.php?proses=hapus&id=<?= $data['nip'] ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-danger">Delete</a>
=======
                                <a href="proses_dosen.php?proses=hapus&id=<?= $data['nip'] ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-danger">Hapus</a>
>>>>>>> 7c70ee53d3b7f3ae581e29575863c609a3f85e12
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php
        break;
    case "create":
    ?>

        <h1>Input data Dosen</h1>
        <form action="proses_dosen.php?proses=simpan" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="nip" class="form-label">NIP : </label>
                <input type="text" class="form-control" id="nip" name="nip" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Dosen : </label>
                <input type="text" class="form-control" id="nama" name="nama_dosen" required>
            </div>

            <!-- Prodi from table prodi -->
            <div class="mb-3">
                <label for="prodi" class="form-label">Program Studi:</label>
                <select class="form-control" name="prodi_id" required>
                    <option value="">Pilih Program Studi</option>
                    <?php
                    $queryProdi = mysqli_query($db, "SELECT * FROM prodi");
                    while ($prodi = mysqli_fetch_array($queryProdi)) {
                        echo "<option value='" . $prodi['id'] . "'>" . $prodi['nama_prodi'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto: </label>
                <input type="file" class="form-control" id="foto" name="foto" required>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>

    <?php
        break;
    case "update":
        $nip = $_GET['id'];
        $query = mysqli_query($db, "SELECT * FROM dosen WHERE nip='$nip'");
        $data = mysqli_fetch_array($query);
    ?>
        <h1>Edit Data Dosen</h1>
        <form action="proses_dosen.php?proses=edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="old_nip" value="<?= $data['nip'] ?>">
            <div class="mb-3">
                <label for="nip" class="form-label">NIP : </label>
                <input type="text" class="form-control" id="nip" name="nip" value="<?= $data['nip'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Dosen : </label>
                <input type="text" class="form-control" id="nama" name="nama_dosen" value="<?= $data['nama_dosen'] ?>" required>
            </div>


            <div class="mb-3">
                <label for="prodi" class="form-label">Program Studi:</label>
                <select class="form-control" name="prodi_id" required>
                    <option value="">Pilih Program Studi</option>
                    <?php
                    $queryProdi = mysqli_query($db, "SELECT * FROM prodi");
                    while ($prodi = mysqli_fetch_array($queryProdi)) {
                        $selected = $prodi['id'] == $data['prodi_id'] ? 'selected' : '';
                        echo "<option value='" . $prodi['id'] . "' $selected>" . $prodi['nama_prodi'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto: </label>
                <input type="file" class="form-control" id="foto" name="foto">
            </div>
            <input type="hidden" name="existing_foto" value="<?= $data['foto'] ?>">




            <button type="submit" class="btn btn-primary" name="submit">Update</button>
        </form>
<?php
        break;
}
?>

<!-- DataTables -->
<script>
    $(document).ready(function() {
        // Check if DataTable is already initialized, then destroy it
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy(); // Destroy the existing DataTable instance
        }

        // Reinitialize DataTable
        $('#dataTable').DataTable({
            dom: 'Bfrtip', // Include buttons
            buttons: ['copy', 'excel', 'pdf'],
            pageLength: 5, // Default number of rows
            lengthMenu: [5, 10, 25, 50], // Dropdown options
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan MENU data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan halaman PAGE dari PAGES",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari MAX total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>