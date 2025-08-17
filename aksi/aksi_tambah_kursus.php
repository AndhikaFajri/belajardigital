<?php
// Mulai session untuk pesan error/success
session_start();

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../konfigurasi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug: Log semua data POST
    error_log("POST Data: " . print_r($_POST, true));
    
    // Bersihkan dan validasi input
    $nama_kursus = isset($_POST['nama_kursus']) ? trim($_POST['nama_kursus']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    $kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
    $harga = isset($_POST['harga']) ? (int)$_POST['harga'] : 0;
    $durasi = isset($_POST['durasi']) ? (int)$_POST['durasi'] : 0;
    
    // Validasi input
    $errors = [];
    
    if (empty($nama_kursus)) {
        $errors[] = "Nama kursus tidak boleh kosong!";
    }
    
    if (empty($deskripsi)) {
        $errors[] = "Deskripsi tidak boleh kosong!";
    }
    
    if (empty($kategori)) {
        $errors[] = "Kategori tidak boleh kosong!";
    }
    
    // Jika ada error, kembali ke form
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        error_log("Validation Errors: " . print_r($errors, true));
        header("Location: ../halaman/admin/kursus/tambah_kursus.php");
        exit();
    }
    
    // Upload gambar jika ada
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $max_size = 2 * 1024 * 1024; // 2MB
            if ($_FILES['gambar']['size'] <= $max_size) {
                $new_filename = uniqid('course_') . '.' . $ext;
                $upload_path = '../aset/gambar/courses/' . $new_filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    $gambar = 'aset/gambar/courses/' . $new_filename;
                } else {
                    $_SESSION['error'] = "Gagal upload gambar!";
                    header("Location: ../halaman/admin/kursus/tambah_kursus.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Ukuran gambar terlalu besar! Maksimal 2MB";
                header("Location: ../halaman/admin/kursus/tambah_kursus.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Format gambar tidak valid! Gunakan: JPG, JPEG, PNG, GIF";
            header("Location: ../halaman/admin/kursus/tambah_kursus.php");
            exit();
        }
    }
    
    // Set status default
    $status = 'Aktif';
    
    // Ambil ID pengguna dari session
    if (isset($_SESSION['user_id'])) {
        $id_pengguna = $_SESSION['user_id'];
    } else {
        // Cari admin pertama sebagai fallback
        $query_admin = "SELECT id_pengguna FROM pengguna WHERE level = 'admin' LIMIT 1";
        $result_admin = $conn->query($query_admin);
        if ($result_admin && $result_admin->num_rows > 0) {
            $admin = $result_admin->fetch_assoc();
            $id_pengguna = $admin['id_pengguna'];
        } else {
            $id_pengguna = 1; // ID default
        }
    }
    
    // Debug: Log data yang akan disimpan
    error_log("Data to insert: " . print_r([
        'nama_kursus' => $nama_kursus,
        'deskripsi' => $deskripsi,
        'kategori' => $kategori,
        'harga' => $harga,
        'durasi' => $durasi,
        'status' => $status,
        'gambar' => $gambar,
        'id_pengguna' => $id_pengguna
    ], true));
    
    // Insert ke database dengan prepared statement
    $query = "INSERT INTO kursus (nama_kursus, deskripsi, kategori, harga, durasi, status, gambar, id_pengguna) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $_SESSION['error'] = "Error prepare statement: " . $conn->error;
        error_log("MySQL Error: " . $conn->error);
        header("Location: ../halaman/admin/kursus/tambah_kursus.php");
        exit();
    }
    
    $stmt->bind_param("sssdiissi", 
        $nama_kursus, 
        $deskripsi, 
        $kategori, 
        $harga, 
        $durasi, 
        $status, 
        $gambar, 
        $id_pengguna
    );
    
    if ($stmt->execute()) {
        $insert_id = $conn->insert_id;
        $_SESSION['success'] = "Kursus berhasil ditambahkan! ID: " . $insert_id;
        error_log("Successfully inserted kursus with ID: " . $insert_id);
        header("Location: ../halaman/admin/manage_courses.php");
    } else {
        $_SESSION['error'] = "Gagal menambahkan kursus: " . $stmt->error;
        error_log("Insert Error: " . $stmt->error);
        header("Location: ../halaman/admin/kursus/tambah_kursus.php");
    }
    
    $stmt->close();
    exit();
} else {
    $_SESSION['error'] = "Metode request tidak valid!";
    header("Location: ../halaman/admin/kursus/tambah_kursus.php");
    exit();
}
?>
