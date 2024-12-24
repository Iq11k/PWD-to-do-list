<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "pwd_bener";


$koneksi = mysqli_connect($host, $user, $password, $db) or die("<b>Gagal Konek: </b>".mysqli_error($koneksi));

?>