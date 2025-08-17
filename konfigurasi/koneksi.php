<?php
$host = 'localhost';
$username = 'andhikaf';
$password = 'password_cpanel_anda';
$database = 'andhikaf_belajardigital';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Error koneksi database: " . $e->getMessage());
}

// Fungsi untuk membersihkan input
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Fungsi untuk debug query
function debug_query($stmt) {
    if (!$stmt->execute()) {
        die("Query Error: " . $stmt->error);
    }
    return $stmt;
}
?>