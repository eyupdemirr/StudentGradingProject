<?php
session_start();

if (isset($_POST["dersAdi"])) {
    include("baglanti.php");

    // Seçilen dersin adını al
    $dersAdi = $_POST["dersAdi"];

    // Dersin ders_adi'na göre ders_id'sini veritabanından çeker
    $sql = "SELECT ders_id FROM dersler WHERE ders_adi = '$dersAdi'";
    $result = mysqli_query($baglanti, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $dersId = $row["ders_id"];

        // Öğrenci bilgilerini veritabanından alır
        $isim = $_SESSION["isim"];
        $soyad = $_SESSION["soyad"];

        // Öğrenci numarasını veritabanından alır
        $ogrenciNumarasi = "";
        $ogrenciSql = "SELECT ogrenciNumarasi FROM kullanicilar WHERE isim = '$isim' AND soyad = '$soyad'";
        $ogrenciResult = mysqli_query($baglanti, $ogrenciSql);
        if (mysqli_num_rows($ogrenciResult) > 0) {
            $ogrenciRow = mysqli_fetch_assoc($ogrenciResult);
            $ogrenciNumarasi = $ogrenciRow["ogrenciNumarasi"];
        }

        // Dersi veritabanına ekler
        $dersEkleSql = "INSERT INTO ogrencidersleri (isim, soyad, ogrenciNumarasi, ders_id) VALUES ('$isim', '$soyad', '$ogrenciNumarasi', '$dersId')";
        if (mysqli_query($baglanti, $dersEkleSql)) {
            echo "Ders başarıyla eklendi.";
            header("Location: profileOgrenci.php");
            exit();
        } else {
            echo "Ders eklenirken bir hata oluştu: " . mysqli_error($baglanti);
        }
    } else {
        echo "Seçilen ders bulunamadı.";
    }

    mysqli_close($baglanti);
} else {
    echo "Geçersiz istek.";
}
?>
