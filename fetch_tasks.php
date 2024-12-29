<?php
include('koneksi.php');
session_start();

if (!isset($_SESSION['login'])) {
    echo json_encode([]);
    exit();
}

$id_akun = $_SESSION['login'];

$sql = "SELECT id_tugas, nama_tugas, deadline, status FROM tugas WHERE id_akun = '$id_akun'";
$result = $koneksi->query($sql);

$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Menambahkan id_tugas ke dalam array tugas
        $tasks[$row['deadline']][] = [
            'id_tugas' => $row['id_tugas'],  // Menambahkan id_tugas
            'nama_tugas' => $row['nama_tugas'],
            'status' => $row['status']
        ];
    }
}

echo json_encode($tasks);
