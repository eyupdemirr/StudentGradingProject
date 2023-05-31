<?php

<?php
session_start();

if (isset($_SESSION["isim"])) {
    include("baglanti.php");

    // Öğretmenin sahip olduğu dersi veritabanından çeker
    $ogretmen_id = $_SESSION["ogretmen_id"];
    $sql = "SELECT * FROM dersler WHERE id = (SELECT ders_id FROM kullanicilar WHERE id = $ogretmen_id)";
    $result = mysqli_query($baglanti, $sql);
    $row = mysqli_fetch_assoc($result);
    $ders_adi = $row['ders_adi'];

    echo "<h3>" . $_SESSION["isim"] . " Hoşgeldin </h3>";
    echo "<h4>Sahip Olduğun Ders:</h4>";
    echo "<p>$ders_adi</p>";

    mysqli_close($baglanti);

    echo "<br><br> <a href='cikis.php' style='color:red; background-color:yellow; border:1px solid gray; padding:5px 5px;'>Çıkış Yap</a>";
} else {
    echo "Bu Sayfayı Görüntüleme Yetkiniz Yoktur!";
}
?>


?>