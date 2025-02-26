<?php
if($_SESSION['level'] != 'admin'){
    header("Location: index.php");
    exit();   
}
?>

<div class="container mt-5">
    <h2>Data User</h2>
    <a href="index.php?page=user&aksi=tambah" class="btn btn-primary mb-3">Tambah User</a>
    
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Lengkap</th>
                <th scope="col">Email</th>
                <th scope="col">Level</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $queryUser = mysqli_query($db, "SELECT * FROM users");
            $nomor = 1;
            while($data = mysqli_fetch_array($queryUser)){
            ?>
            <tr>
                <th scope="row"><?= $nomor++ ?></th>
                <td><?=$data['nama_lengkap'] ?></td>
                <td><?=$data['email'] ?></td>
                <td><?=$data['level'] ?></td>
                <td>
                    <a href="index.php?page=user&aksi=update&id=<?=$data['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="proses_user.php?proses=hapus&id=<?=$data['id'] ?>" onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php if(isset($_GET['aksi']) && $_GET['aksi'] == 'tambah'){ ?>
    <div class="container mt-5">
        <h2>Tambah User</h2>
        <form action="proses_user.php" method="POST">
            <input type="hidden" name="proses" value="tambah">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Level</label>
                <select class="form-control" name="level" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
<?php } ?>

<?php 
if(isset($_GET['aksi']) && $_GET['aksi'] == 'update'){
    $id = $_GET['id'];
    $query = mysqli_query($db, "SELECT * FROM users WHERE id='$id'");
    $data = mysqli_fetch_array($query);
?>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form action="proses_user.php" method="POST">
            <input type="hidden" name="proses" value="update">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?=$data['email']?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" value="<?=$data['nama_lengkap']?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Level</label>
                <select class="form-control" name="level" required>
                    <option value="admin" <?=$data['level']=='admin'?'selected':''?>>Admin</option>
                    <option value="user" <?=$data['level']=='user'?'selected':''?>>User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
<?php } ?>

<!-- DataTables -->
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
</script>
