<?php
$title = "Profil Mahasiswa - BelajarDigital";
include '../../template/header.php';
include '../../konfigurasi/koneksi.php';

// Query untuk mendapatkan data pengguna
$query = "SELECT * FROM pengguna WHERE id_pengguna = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!-- Modern Profile Styles -->
<style>
    .profile-container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #667eea;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border: 4px solid rgba(255, 255, 255, 0.2);
    }

    .profile-name {
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .profile-role {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 300;
    }

    .profile-body {
        padding: 2.5rem;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
    }

    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1.1rem;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        display: block;
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        display: block;
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .profile-container {
            margin: 1rem;
        }
        
        .profile-header {
            padding: 2rem 1.5rem;
        }
        
        .profile-name {
            font-size: 1.5rem;
        }
        
        .profile-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h1 class="profile-name"><?php echo htmlspecialchars($user['nama_lengkap']); ?></h1>
            <p class="profile-role">Mahasiswa</p>
        </div>
        
        <div class="profile-body">
            <ul class="info-list">
                <li class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="info-content">
                        <span class="info-label">Username</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                </li>
                
                <li class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                </li>
                
                <li class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div class="info-content">
                        <span class="info-label">Level</span>
                        <span class="info-value"><?php echo ucfirst(htmlspecialchars($user['level'])); ?></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php include '../../template/footer.php'; ?>
