<?php
$title = "Manajemen Kursus - BelajarDigital";
include '../../template/header.php';
include '../../konfigurasi/koneksi.php';

// Query untuk mendapatkan semua kursus
$query = "SELECT * FROM kursus ORDER BY id_kursus DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h1 class="text-center">Manajemen Kursus</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="kursus/tambah_kursus.php" class="btn btn-primary mb-3">Tambah Kursus</a>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">Nama Kursus</th>
                        <th class="text-nowrap d-none d-md-table-cell">Deskripsi</th>
                        <th class="text-nowrap">Kategori</th>
                        <th class="text-nowrap">Harga</th>
                        <th class="text-nowrap d-none d-lg-table-cell">Durasi</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="text-nowrap"><?= $row['id_kursus']; ?></td>
                        <td class="text-nowrap">
                            <strong><?= $row['nama_kursus']; ?></strong>
                            <div class="d-md-none text-muted small">
                                <?= substr($row['deskripsi'], 0, 30); ?>...
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                <?= $row['deskripsi']; ?>
                            </div>
                        </td>
                        <td class="text-nowrap">
                            <span class="badge bg-info"><?= ucfirst($row['kategori']); ?></span>
                        </td>
                        <td class="text-nowrap">
                            <strong>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></strong>
                        </td>
                        <td class="text-nowrap d-none d-lg-table-cell"><?= $row['durasi']; ?> jam</td>
                        <td class="text-nowrap">
                            <span class="badge bg-<?= $row['status'] == 'Aktif' ? 'success' : 'secondary'; ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <div class="btn-group-vertical btn-group-sm" role="group">
                                <a href="kursus/edit_kursus.php?id=<?= $row['id_kursus']; ?>" class="btn btn-warning btn-sm mb-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="kursus/hapus_kursus.php?id=<?= $row['id_kursus']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Belum ada kursus yang ditambahkan.</div>
    <?php endif; ?>
</div>

<style>
/* Responsive table improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-vertical .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-group-vertical {
        width: 100%;
    }
    
    .btn-group-vertical .btn {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
}

/* Improve table appearance */
.table th {
    white-space: nowrap;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

/* Add hover effect */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

/* Responsive badges */
@media (max-width: 576px) {
    .badge {
        display: block;
        margin-bottom: 0.25rem;
    }
}
</style>

<?php include '../../template/footer.php'; ?>
