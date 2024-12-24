<?php
session_start();
if (isset($_POST['login'])) {
    include("koneksi.php");

    // Ambil data dari form dengan sanitasi
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $error = "";

    // Validasi server-side
    if (empty($email) || empty($password)) {
        $error = "Email dan password tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        // Periksa apakah email ada di database
        $query_user = mysqli_query($koneksi, "SELECT * FROM akun WHERE email='$email'");
        $akun = mysqli_fetch_assoc($query_user);

        if ($akun) {
            // Verifikasi password
            if (password_verify($password, $akun['password'])) {
                // Jika login berhasil
                $_SESSION['login'] = $akun['id'];
                header("Location: home.php");
                exit;
            } else {
                // Password salah
                $error = "Email atau password salah.";
            }
        } else {
            // Email tidak ditemukan
            $error = "Email tidak terdaftar.";
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
        <h1 class="text-2xl font-bold text-blue-600 mb-4">Login Ke Akun Anda</h1>
        <?php if (!empty($error)): ?>
        <p class="text-red-500"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="border p-2 rounded w-full mb-2" required>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="border p-2 rounded w-full mb-2" required>
            <br>
            <input type="submit" value="Login" name="login" class="bg-blue-600 text-white px-4 py-2 rounded">
        </form>
    </div>
</body>

</html>