<?php 
    require 'koneksi.php';
    $aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

    switch ($aksi) {
        case "read":
?>
        <h1>Data Mata Kuliah</h1>
        <a href="index.php?page=mata_kuliah&aksi=create" class="btn btn-primary">Tambah Data</a>
        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Kode Mata Kuliah</th>
                    <th scope="col">Nama Mata Kuliah</th>
                    <th scope="col">SKS</th>
                    <th scope="col">Dosen Pengajar</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = mysqli_query($db, "SELECT mata_kuliah.*, dosen.nama_dosen 
                                          FROM mata_kuliah 
                                          LEFT JOIN dosen ON mata_kuliah.dosen_nip = dosen.nip");
                $nomor = 1;
                while ($data = mysqli_fetch_array($query)) {
                ?>
                    <tr>
                        <th scope="row"><?= $nomor++ ?></th>
                        <td><?=$data['kode_mk'] ?></td>
                        <td><?=$data['nama_mk'] ?></td>
                        <td><?=$data['sks'] ?></td>
                        <td><?=$data['nama_dosen'] ?></td>
                        <td><?=$data['semester'] ?></td>
                        <td>
                            <a href="index.php?page=mata_kuliah&aksi=update&kode=<?=$data['kode_mk'] ?>" class="btn btn-warning">Edit</a>
                            <?php if($_SESSION['level'] == 'admin') { ?>
                                <a href="prosesMata_kuliah.php?proses=hapus&kode=<?=$data['kode_mk'] ?>" 
                                   onclick="return confirm('Apakah anda yakin menghapus data ini ?')" 
                                   class="btn btn-danger">Hapus</a>
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
    <h1>Input Data Mata Kuliah</h1>
    <form action="prosesMata_kuliah.php?proses=simpan" method="POST">
        <div class="mb-3">
            <label class="form-label">Kode Mata Kuliah:</label>
            <input type="text" class="form-control" name="kode_mk" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Mata Kuliah:</label>
            <input type="text" class="form-control" name="nama_mk" required>
        </div>
        <div class="mb-3">
            <label class="form-label">SKS:</label>
            <input type="number" class="form-control" name="sks" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dosen Pengajar:</label>
            <select class="form-control" name="dosen_nip" required>
                <option value="">Pilih Dosen</option>
                <?php
                $queryDosen = mysqli_query($db, "SELECT * FROM dosen");
                while($dosen = mysqli_fetch_array($queryDosen)) {
                    echo "<option value='".$dosen['nip']."'>".$dosen['nama_dosen']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Semester:</label>
            <input type="number" class="form-control" name="semester" required>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </form>

<?php
break;
case "update":
    $kode = $_GET['kode'];
    $query = mysqli_query($db, "SELECT * FROM mata_kuliah WHERE kode_mk='$kode'");
    $data = mysqli_fetch_array($query);
?>
    <h1>Edit Data Mata Kuliah</h1>
    <form action="prosesMata_kuliah.php?proses=edit" method="POST">
        <div class="mb-3">
            <label class="form-label">Kode Mata Kuliah:</label>
            <input type="text" class="form-control" name="kode_mk" value="<?= $data['kode_mk'] ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Mata Kuliah:</label>
            <input type="text" class="form-control" name="nama_mk" value="<?= $data['nama_mk'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">SKS:</label>
            <input type="number" class="form-control" name="sks" value="<?= $data['sks'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dosen Pengajar:</label>
            <select class="form-control" name="dosen_nip" required>
                <option value="">Pilih Dosen</option>
                <?php
                $queryDosen = mysqli_query($db, "SELECT * FROM dosen");
                while($dosen = mysqli_fetch_array($queryDosen)) {
                    $selected = $dosen['nip'] == $data['dosen_nip'] ? 'selected' : '';
                    echo "<option value='".$dosen['nip']."' $selected>".$dosen['nama_dosen']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Semester:</label>
            <input type="number" class="form-control" name="semester" value="<?= $data['semester'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Update</button>
    </form>
<?php
break;
}
?>

<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf'],
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
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
</script>
