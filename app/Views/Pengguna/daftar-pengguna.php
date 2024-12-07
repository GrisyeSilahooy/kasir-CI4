<?=$this->extend('dashboard');?>
<?=$this->section('konten');?>

<h4 class="card-title"><?=$judulHalaman;?></h4>
<?=session()->getFlashdata('pesan');?>

<p>
    <a href="<?=site_url('/tambah-pengguna');?>" class="btn btn-sm btn-primary">
        <i class="mdi mdi-plus-circle-outline"></i> Tambah Pengguna
    </a>
</p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Email (Username)</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($listPengguna) && count($listPengguna) > 0): ?>
            <?php $no = 1;?>
            <?php foreach ($listPengguna as $row): ?>
            <tr>
                <td><?=$no++;?></td>
                <td><?=$row['nama'];?></td>
                <td><?=$row['email'];?></td>
                <td><?=ucfirst($row['level']);?></td>
                <td class="text-center">
                    <!-- Tombol Edit -->
                    <a href="<?=site_url('/edit-pengguna/' . md5($row['email']));?>" class="btn btn-sm btn-warning"
                        title="Edit Pengguna">
                        <i class="mdi mdi-pencil-outline"></i>
                    </a>
                    <!-- Tombol Hapus -->
                    <a href="<?=site_url('/hapus-pengguna/' . md5($row['email']));?>" class="btn btn-sm btn-danger"
                        title="Hapus Pengguna"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                        <i class="mdi mdi-delete-outline"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach;?>
            <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Tidak ada data pengguna.</td>
            </tr>
            <?php endif;?>
        </tbody>
    </table>
</div>

<?=$this->endSection();?>