<?php
    session_start();
    if (isset($_SESSION['login'])){
        header("Location: home.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen">
    <!-- Bagian Kiri -->
    <div class="flex items-center justify-center bg-[#3b83c5] w-1/3">
        <img src="images/icon_welcome.png" alt="Logo Welcome" class="w-2/3">
    </div>

    <!-- Bagian Kanan -->
    <div class="flex flex-col justify-center items-start w-2/3 p-10">
        <h1 class="text-2xl font-bold text-blue-600 mb-4">Hi, Selamat Datang</h1>
        <p class="text-gray-600 mb-6 w-1/2">
            Atur pekerjaan dengan mudah dan terpercaya! Buat daftar tugas, kelola jadwal,
            notifikasi tepat waktu, dan informasi terbaru untuk meningkatkan produktivitas Anda.
        </p>
        <div class="flex flex-col gap-4 w-full max-w-sm">
            <a class="bg-blue-500 text-white py-2 px-4 rounded-md text-lg hover:bg-blue-600 text-center"
                href="login.php">Login</a>
            <a class="bg-blue-500 text-white py-2 px-4 rounded-md text-lg hover:bg-blue-600 text-center"
                href="register.php">Daftar</a>
        </div>
    </div>
</body>

</html>