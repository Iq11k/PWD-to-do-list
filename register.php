<?php
session_start();
if (isset($_POST['register'])) {
    include("koneksi.php");

    // Ambil data dari form dengan sanitasi
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $username = trim($_POST['username']);
    $error = "";

    // Validasi server-side
    if (empty($email) || empty($password) || empty($username)) {
        $error = "Semua field wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 8) {
        $error = "Password harus memiliki minimal 8 karakter.";
    } else {
        // Periksa apakah email sudah terdaftar
        $query_user = mysqli_query($koneksi, "SELECT * FROM akun WHERE email='$email'");
        $akun = mysqli_fetch_array($query_user);

        if ($akun) {
            $error = "Maaf: Sudah ada akun dengan email $email.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan user baru ke database
            $sql = "INSERT INTO akun(username, password, email) VALUES ('$username', '$hashed_password', '$email')";
            if (mysqli_query($koneksi, $sql)) {
                // Jika berhasil, arahkan ke index.php
                header("Location: login.php");
                exit;
            } else {
                $error = "Gagal mendaftar. Coba lagi nanti.";
            }
        }
    }
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
    <div class="flex items-center justify-center bg-blue-600 w-1/3">
        <img src="images/icon_login.png" alt="Logo login" class="w-2/3">
    </div>

    <!-- Bagian Kanan -->
    <div class="flex flex-col justify-center items-start w-2/3 p-10">
        <h1 class="text-2xl font-bold text-blue-600 mb-4">Daftarkan Akun Anda</h1>
        <?php if (!empty($error)): ?>
        <p class="text-red-500"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="border p-2 rounded w-full mb-2">
            <br>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="border p-2 rounded w-full mb-2">
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="border p-2 rounded w-full mb-2">
            <br>
            <input type="submit" value="Register" name="register" class="bg-blue-600 text-white px-4 py-2 rounded">
        </form>
    </div>
</body>

</html>