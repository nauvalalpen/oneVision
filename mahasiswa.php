<?php 

    require 'koneksi.php';

    $aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

    switch ($aksi) {
        case "read":
            
            ?>

        <h1>Data Mahasiswa</h1>
        <a href="index.php?page=mahasiswa&aksi=create " class="btn btn-primary">Tambah Data</a>
        <table class="table" id="dataTable">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">Nama</th>
              <th scope="col">Email</th>
              <th scope="col">NIM</th>
              <th scope="col">Program Studi</th>
              <th scope="col">Gender</th>
              <th scope="col">Hobi</th>
              <th scope="col">Alamat</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            
            $queryMhs = mysqli_query($db, "SELECT mahasiswa.*, prodi.nama_prodi 
            FROM mahasiswa 
            LEFT JOIN prodi ON mahasiswa.prodi_id = prodi.id");

            $nomor = 1;

              while($data = mysqli_fetch_array($queryMhs)){
                
            ?>
                  <tr>
                    <th scope="row"><?= $nomor++ ?></th>
                    <td><?=$data['nama'] ?></td> 
                    <td><?=$data['email'] ?></td>
                    <td><?=$data['nim'] ?></td>
                    <td><?=$data['nama_prodi'] ?></td>
                    <td><?=$data['gender']  == 'L' ? 'Laki Laki' : 'Perempuan'?></td>
                    <td><?= $data['hobi'] ?></td>
                    <td><?=$data['alamat'] ?></td>
                    <td>
                        <a href="index.php?page=mahasiswa&aksi=update&id=<?=$data['id'] ?>" class="btn btn-warning">Edit</a>
                        <?php if($_SESSION['level'] == 'admin') { ?>
                            <a href="proses_mahasiswa.php?proses=hapus&id=<?=$data['id'] ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-danger">Hapus</a>
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

    <h1>Input data Mahasiswa</h1>
        <form action="proses_mahasiswa.php?proses=simpan" method="POST">
            <div class="mb-3">
                <label for="labelNama" class="form-label">Nama : </label>
                <input type="inputNama" class="form-control" id="nama" aria-describedby="emailHelp" name="nama" maxlength="40">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label" type="email">Email : </label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name ="email" required>
            </div>
            <div class="mb-3">
                <label for="nim" class="form-label" type="nim">NIM : </label>
                <input type="number" class="form-control" id="nim" aria-describedby="emailHelp" name ="nim">           
            </div>

            <!-- Prodi from table prodi -->
            <div class="mb-3">
                <label for="prodi" class="form-label">Program Studi:</label>
                <select class="form-control" name="prodi_id" required>
                    <option value="">Pilih Program Studi</option>
                    <?php
                    $queryProdi = mysqli_query($db, "SELECT * FROM prodi");
                    while($prodi = mysqli_fetch_array($queryProdi)) {
                        echo "<option value='".$prodi['id']."'>".$prodi['nama_prodi']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="nim" class="form-label" type="nim">Gender : </label>

                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" value="L">
                <label class="form-check-label" for="flexRadioDefault1">
                    Laki - laki
                </label>

                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="P">
                <label class="form-check-label" for="flexRadioDefault2">
                    Perempuan
                </label>
       
            </div>
            <div class="mb-3">
                <label>Hobby:</label>
                <input type="checkbox" name="hobby[]" value="badminton">Badminton
                <input type="checkbox" name="hobby[]" value="football">Football
                <input type="checkbox" name="hobby[]" value="gaming">Gaming
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label" type="nim">Alamat : </label> 
                <div class="form-floating">
                    <textarea class="form-control" name="alamat" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                    <label for="floatingTextarea2">Write your address here</label>
                </div>

            </div>

            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>

<?php
break;  
case "update":
    $id = $_GET['id'];
    $query = mysqli_query($db, "SELECT * FROM mahasiswa WHERE id='$id'");
    $data = mysqli_fetch_array($query);
    ?>
    <h1>Edit Data Mahasiswa</h1>
    <form action="proses_mahasiswa.php?proses=edit" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <div class="mb-3">
            <label for="labelNama" class="form-label">Nama : </label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama'] ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email : </label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $data['email'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="nim" class="form-label">NIM : </label>
            <input type="number" class="form-control" id="nim" name="nim" value="<?= $data['nim'] ?>">           
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
            <label for="nim" class="form-label">Gender : </label>
            <input class="form-check-input" type="radio" name="gender" value="L" <?= $data['gender'] == 'L' ? 'checked' : '' ?>>
            <label class="form-check-label">Laki - laki</label>
            <input class="form-check-input" type="radio" name="gender" value="P" <?= $data['gender'] == 'P' ? 'checked' : '' ?>>
            <label class="form-check-label">Perempuan</label>
        </div>
        <div class="mb-3">
            <label>Hobby:</label>
            <?php $hobi = explode(", ", $data['hobi']); ?>
            <input type="checkbox" name="hobby[]" value="badminton" <?= in_array('badminton', $hobi) ? 'checked' : '' ?>>Badminton
            <input type="checkbox" name="hobby[]" value="football" <?= in_array('football', $hobi) ? 'checked' : '' ?>>Football
            <input type="checkbox" name="hobby[]" value="gaming" <?= in_array('gaming', $hobi) ? 'checked' : '' ?>>Gaming
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat : </label> 
            <div class="form-floating">
                <textarea class="form-control" name="alamat" id="floatingTextarea2" style="height: 100px"><?= $data['alamat'] ?></textarea>
                <label for="floatingTextarea2">Write your address here</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Update</button>
    </form>
    <?php
break;

}

?>

<!-- DataTables -->

<script>
    // $(document).ready(function () {
    //     // Check if DataTable is already initialized, then destroy it
    //     if ($.fn.DataTable.isDataTable('#dataTable')) {
    //         $('#dataTable').DataTable().destroy(); // Destroy the existing DataTable instance
    //     }

        // Reinitialize DataTable
        // $('#dataTable').DataTable({
        //     dom: 'Blrtip', // Modified dom parameter (removed search)
        //     buttons: ['copy', 'excel', 'pdf'],
        //     pageLength: 5,
        //     lengthMenu: [5, 10, 25, 50],
        //     searching: false,
        //     language: {
        //         lengthMenu: "Tampilkan _MENU_ data per halaman",
        //         zeroRecords: "Data tidak ditemukan",
        //         info: "Menampilkan halaman _PAGE_ dari _PAGES_",
        //         infoEmpty: "Tidak ada data tersedia",
        //         infoFiltered: "(difilter dari _MAX_ total data)",
        //         paginate: {
        //             first: "Pertama",
        //             next: "Selanjutnya",
        //             previous: "Sebelumnya"
        //         }
        //     }
        // });


    // });
</script>


