<?php
session_start();
// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: home.php"); // Jika belum login, arahkan ke halaman welcome
    exit(); // Pastikan script berhenti setelah redirect
    require 'koneksi.php'; // Pastikan file koneksi Anda benar

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/8adfb2aa9f.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100 font-sans">
    <header class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <nav>
            <ul class="flex space-x-4">
                <li><a href="#" class="hover:underline">Beranda</a></li>
                <li><a href="logout.php" class="hover:underline">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <main class="p-4">
        <section class="bg-white rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Kategori</h2>
            <div class="grid grid-cols-4 gap-4">
                <a href="#" class="flex flex-col items-center p-4 border rounded hover:bg-gray-100">
                    <i class="fa-solid fa-briefcase text-2xl"></i>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border rounded hover:bg-gray-100">
                    <i class="fa-solid fa-suitcase-medical text-2xl"></i>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border rounded hover:bg-gray-100">
                    <i class="fa-solid fa-book text-2xl"></i>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border rounded hover:bg-gray-100">
                    <i class="fa-solid fa-person-running text-2xl"></i>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border rounded hover:bg-gray-100">
                    <i class="fa-solid fa-cart-shopping text-2xl"></i>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border rounded hover:bg-gray-100">
                    <i class="fa-solid fa-bed text-2xl"></i>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border rounded hover:bg-gray-100">
                    <i class="fa-solid fa-utensils text-2xl"></i>
                </a>
            </div>
        </section>

        <section class="bg-white rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Kegiatanmu</h2>
            <div class="flex gap-8">
                <!-- Kalender -->
                <div class="w-1/2">
                    <div class="flex justify-between items-center bg-blue-600 text-white p-4 rounded-t-lg">
                        <button id="prev" class="text-lg">&lt;</button>
                        <h2 id="month-year" class="text-lg font-bold"></h2>
                        <button id="next" class="text-lg">&gt;</button>
                    </div>
                    <div class="grid grid-cols-7 text-center bg-gray-200 p-2">
                        <div>Sun</div>
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                    </div>
                    <div id="calendar-dates" class="grid grid-cols-7 p-2 bg-white gap-1"></div>
                </div>

                <!-- Daftar Tugas -->
                <div class="w-1/2">
                    <h3 class="text-lg font-bold mb-4" id="selected-date">Hari ini</h3>
                    <!-- Form untuk menambahkan tugas -->
                    <div class="mb-4">
                        <button onclick="window.location.href='tambah.php'" id="add-task" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 w-full">Tambah Tugas</button>
                    </div>
                    <ul id="task-list" class="space-y-2">

                    </ul>
                </div>
            </div>
        </section>

        <script src="script.js"></script>
    </main>
</body>

</html>