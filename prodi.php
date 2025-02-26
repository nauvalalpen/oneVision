<?php 

    require 'koneksi.php';

    $aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

    switch ($aksi) {
        case "read":
            
            ?>

        <h1>Data Prodi</h1>
        <a href="index.php?page=prodi&aksi=create " class="btn btn-primary">Tambah Data</a>
        <table class="table" id="dataTable">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">Nama Prodi</th>
              <th scope="col">Jenjang</th>
              <th scope="col">Keterangan</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            
            $queryMhs = mysqli_query($db, "SELECT * FROM prodi");
            $nomor = 1;

              while($data = mysqli_fetch_array($queryMhs)){
                
            ?>
                  <tr>
                    <th scope="row"><?= $nomor++ ?></th>
                    <td><?=$data['nama_prodi'] ?></td> 
                    <td><?=$data['jenjang'] ?></td>
                    <td><?= $data['keterangan'] ?></td>
                    <td>
                        <a href="index.php?page=prodi&aksi=update&id=<?=$data['id'] ?>" class="btn btn-warning">Edit</a>
                        <?php if($_SESSION['level'] == 'admin') { ?>
                            <a href="proses_prodi.php?proses=hapus&id=<?=$data['id'] ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-danger">Hapus</a>
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

    <h1>Input data Prodi</h1>
        <form action="proses_prodi.php?proses=simpan" method="POST">
            <div class="mb-3">
                <label for="labelNama" class="form-label">Nama Prodi (Minimal two character) : </label>
                <input type="inputNama" class="form-control" id="nama" aria-describedby="emailHelp" name ="nama_prodi" required>
            </div>
            <div class="mb-3">
            <label for="jenjang" class="form-label">Pilih Jenjang :</label>
                <select name="jenjang" id="jenjang" class="form-select">
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4l">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label" type="email">Keterangan (It must have 40 or fewer characters)  : </label>
                <input type="input" class="form-control" id="nama" aria-describedby="keterangan" name ="keterangan" required>
            </div>


            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>

<?php
break;  
case "update":
    $id = $_GET['id'];
    $query = mysqli_query($db, "SELECT * FROM prodi WHERE id='$id'");
    $data = mysqli_fetch_array($query);
    ?>
    <h1>Edit Data Mahasiswa</h1>
    <form action="proses_prodi.php?proses=edit" method="POST">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <div class="mb-3">
            <label for="labelNama" class="form-label">Nama Prodi : (Minimal two character and other symbol (-, ') not allowed) </label>
            <input type="text" class="form-control" id="nama" name="nama_prodi" value="<?= $data['nama_prodi'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="jenjang" class="form-label">Pilih Jenjang :</label>
            <select name="jenjang" id="jenjang" class="form-select">
                <option value="D2" <?= $data['jenjang'] == 'D2' ? 'selected' : '' ?>>D2</option>
                <option value="D3" <?= $data['jenjang'] == 'D3' ? 'selected' : '' ?>>D3</option>
                <option value="D4" <?= $data['jenjang'] == 'D4' ? 'selected' : '' ?>>D4</option>
                <option value="S1" <?= $data['jenjang'] == 'S1' ? 'selected' : '' ?>>S1</option>
                <option value="S2" <?= $data['jenjang'] == 'S2' ? 'selected' : '' ?>>S2</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan : </label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $data['keterangan'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Update</button>
    </form>

    <?php
break;

}
?>


<!-- Datatables -->
<script>
    $(document).ready(function () {
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
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });


    document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        let namaProdi = document.getElementById("nama").value.trim();
        let keterangan = document.getElementById("keterangan").value.trim();
        let namaProdiPattern = /^[a-zA-Z0-9\-']+$/; // Alphanumeric, hyphen, and apostrophe allowed

        if (namaProdi.length < 2) {
            alert("Nama Prodi harus memiliki minimal 2 karakter!");
            event.preventDefault(); // Prevent form submission
        }

        if (!namaProdiPattern.test(namaProdi)) {
            alert("Nama Prodi hanya boleh berisi huruf, angka, tanda hubung (-), atau apostrof (').");
            event.preventDefault(); // Prevent form submission
        }

        if (keterangan.length > 40) {
            alert("Keterangan tidak boleh lebih dari 40 karakter!");
            event.preventDefault(); // Prevent form submission
        }
    });
});



</script>