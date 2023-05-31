<?php

$host="localhost";
$kullanici="root";
$parola="";
$vt="368Proje";

$baglanti = mysqli_connect($host, $kullanici, $parola, $vt);
mysqli_set_charset($baglanti, "UTF8");

// Bağlantı hatası kontrolü
if (!$baglanti) {
    die("Veritabanına bağlantı sağlanamadı: " . mysqli_connect_error());
}
?>