<?php
include('koneksi.php');
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$id_akun = $_SESSION['login'];

// Ambil data dari request GET
$taskId = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;
$status = isset($_GET['status']) ? intval($_GET['status']) : 0;

// Validasi input
if ($taskId > 0 && ($status === 0 || $status === 1)) {
    // Menyiapkan query untuk update status tugas
    $sql = "UPDATE tugas SET status = ? WHERE id_tugas = ? AND id_akun = ?";
    
    if ($stmt = $koneksi->prepare($sql)) {
        // Bind parameter untuk query
        $stmt->bind_param("iii", $status, $taskId, $id_akun);

        // Eksekusi query
        if ($stmt->execute()) {
            // Kirim response JSON jika berhasil
            echo json_encode(['success' => true]);
        } else {
            // Kirim response JSON jika gagal
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status']);
        }

        // Tutup statement
        $stmt->close();
    } else {
        // Kirim response jika query tidak bisa dipersiapkan
        echo json_encode(['success' => false, 'message' => 'Gagal mempersiapkan query']);
    }
} else {
    // Kirim response JSON jika input tidak valid
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
}

$koneksi->close();
?>
