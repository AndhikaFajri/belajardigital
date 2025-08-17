<?php
$title = "Laporan Pendaftaran - BelajarDigital";
include '../../template/header.php';
include '../../konfigurasi/koneksi.php';

// Query untuk laporan dengan join untuk mendapatkan nama pengguna dan kursus
$query = "SELECT 
    p.id_pendaftaran,
    u.nama_lengkap as nama_pengguna,
    k.nama_kursus,
    p.tanggal_daftar,
    p.status
FROM pendaftaran p
JOIN pengguna u ON p.id_pengguna = u.id_pengguna
JOIN kursus k ON p.id_kursus = k.id_kursus
ORDER BY p.id_pendaftaran DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h1 class="text-center">Laporan Pendaftaran</h1>
    
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
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">Nama Pengguna</th>
                        <th class="text-nowrap">Nama Kursus</th>
                        <th class="text-nowrap">Tanggal Daftar</th>
                        <th class="text-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="text-nowrap"><?= $row['id_pendaftaran']; ?></td>
                        <td class="text-nowrap">
                            <strong><?= $row['nama_pengguna']; ?></strong>
                        </td>
                        <td class="text-nowrap">
                            <span class="badge bg-info"><?= $row['nama_kursus']; ?></span>
                        </td>
                        <td class="text-nowrap"><?= date('d/m/Y H:i', strtotime($row['tanggal_daftar'])); ?></td>
                        <td class="text-nowrap">
                            <span class="badge bg-<?= $row['status'] == 'Aktif' ? 'success' : ($row['status'] == 'Selesai' ? 'primary' : 'warning'); ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Belum ada pendaftaran yang dilakukan.</div>
    <?php endif; ?>
</div>

<style>
/* Responsive table improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
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
    
    .badge {
        display: block;
        margin-bottom: 0.25rem;
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
