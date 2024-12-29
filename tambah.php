<?php
session_start();
require 'koneksi.php';

// Cek login - jika tidak login, kembali ke home
if (!isset($_SESSION['login'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $taskName = $_POST['taskName'];
    $taskDate = $_POST['taskDate'];
    $taskTime = $_POST['taskTime'];
    $id_akun = $_SESSION['login'];  // Pastikan ID akun sudah ada di session

    // Validasi input
    if (empty($category) || empty($taskName) || empty($taskDate) || empty($taskTime)) {
        echo "<script>
            alert('Semua field harus diisi!');
            window.location.href = 'tambah.php';
        </script>";
        exit();
    }

    // Gabungkan tanggal dan waktu untuk deadline
    $deadline = $taskDate . ' ' . $taskTime;

    // Prepared statement untuk insert
    $sql = "INSERT INTO tugas (kategori, nama_tugas, deadline, status, id_akun) VALUES (?, ?, ?, 0, ?)";

    if ($stmt = mysqli_prepare($koneksi, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $category, $taskName, $deadline, $id_akun);

        // Eksekusi statement dan cek keberhasilan
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($koneksi);

            // Jika berhasil, kembali ke home.php
            echo "<script>
                    alert('Tugas berhasil ditambahkan!');
                    window.location.href = 'home.php';
                </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error: " . mysqli_error($koneksi) . "');
                </script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/8adfb2aa9f.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <header class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <nav>
            <ul class="flex space-x-4">
                <li><a href="home.php" class="hover:underline">Beranda</a></li>
                <li><a href="logout.php" class="hover:underline">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="p-6 max-w-4xl mx-auto mt-10">
        <div class="bg-white rounded-lg p-6 shadow-md">
            <h2 class="text-3xl font-semibold mb-6 text-center">Tambah Tugas Baru</h2>

            <form method="POST" action="">
                <!-- Pilih Kategori -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Pilih Kategori</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <?php
                        $categories = [
                            'kerja' => 'fa-briefcase',
                            'berobat' => 'fa-suitcase-medical',
                            'belajar' => 'fa-book',
                            'olahraga' => 'fa-person-running',
                            'shopping' => 'fa-cart-shopping',
                            'tidur' => 'fa-bed',
                            'makan' => 'fa-utensils'
                        ];
                        foreach ($categories as $key => $icon) {
                            echo "<label class='cursor-pointer'>
                                    <input type='radio' name='category' value='$key' class='hidden' required>
                                    <div class='p-4 border rounded-lg text-center hover:bg-gray-50 transition'>
                                        <i class='fa-solid $icon text-2xl mb-2'></i>
                                        <p class='text-sm'>$key</p>
                                    </div>
                                  </label>";
                        }
                        ?>
                    </div>
                </div>

                <!-- Nama Tugas -->
                <div class="mb-6">
                    <label for="taskName" class="block text-gray-700 mb-2">Nama Tugas</label>
                    <input type="text" id="taskName" name="taskName" class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Tanggal dan Waktu -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="taskDate" class="block text-gray-700 mb-2">Tanggal</label>
                        <input type="date" id="taskDate" name="taskDate" class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="taskTime" class="block text-gray-700 mb-2">Waktu</label>
                        <input type="time" id="taskTime" name="taskTime" class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end space-x-4">
                    <a href="home.php" class="px-6 py-2 bg-gray-300 rounded-md hover:bg-gray-400 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Highlight kategori yang dipilih
        document.querySelectorAll('input[name="category"]').forEach(input => {
            input.addEventListener('change', function() {
                // Reset semua div
                document.querySelectorAll('input[name="category"]').forEach(radio => {
                    radio.parentElement.querySelector('div').classList.remove('bg-blue-50', 'border-blue-500');
                });
                // Highlight yang dipilih
                if (this.checked) {
                    this.parentElement.querySelector('div').classList.add('bg-blue-50', 'border-blue-500');
                }
            });
        });

        // Set tanggal default ke hari ini
        document.getElementById('taskDate').valueAsDate = new Date();
    </script>
</body>

</html>