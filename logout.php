<?php
// Mulai session
session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Redirect pengguna ke halaman login atau beranda setelah logout
header("Location: login.php"); // Ganti dengan halaman tujuan setelah logout
exit();
?>
