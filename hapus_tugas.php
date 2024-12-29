<?php
// hapus_tugas.php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User tidak terautentikasi']);
    exit;
}

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("DELETE FROM tugas WHERE id_tugas = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
