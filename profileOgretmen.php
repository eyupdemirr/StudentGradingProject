<?php
session_start();

if (isset($_SESSION["isim"])) {
    include("baglanti.php");

    // Öğretmenin adını alır
    $ogretmenAdi = $_SESSION["isim"];

    // Öğretmenin sahip olduğu dersi alır
    $dersSql = "SELECT ders_adi FROM ogretmenler WHERE isim = ?";
    $dersStatement = mysqli_prepare($baglanti, $dersSql);
    mysqli_stmt_bind_param($dersStatement, "s", $ogretmenAdi);
    mysqli_stmt_execute($dersStatement);
    $dersResult = mysqli_stmt_get_result($dersStatement);

    if (mysqli_num_rows($dersResult) > 0) {
        $dersRow = mysqli_fetch_assoc($dersResult);
        $dersAdi = $dersRow["ders_adi"];

        echo "<h3>" . $_SESSION["isim"] . " Hoşgeldin </h3>";
        echo "<h4>Ders Notları: " . $dersAdi . "</h4>"; // Ders adını ekler

        // Öğretmenin sahip olduğu dersi alan öğrencileri çeker
        $ogrenciSql = "SELECT kullanicilar.ogrenciNumarasi, kullanicilar.isim, kullanicilar.soyad, ogrencidersleri.ders_notu FROM kullanicilar INNER JOIN ogrencidersleri ON kullanicilar.ogrenciNumarasi = ogrencidersleri.ogrenciNumarasi INNER JOIN dersler ON ogrencidersleri.ders_id = dersler.ders_id WHERE dersler.ders_adi = ? AND kullanicilar.roles = 'ogrenci'";
        $ogrenciStatement = mysqli_prepare($baglanti, $ogrenciSql);
        mysqli_stmt_bind_param($ogrenciStatement, "s", $dersAdi);
        mysqli_stmt_execute($ogrenciStatement);
        $ogrenciResult = mysqli_stmt_get_result($ogrenciStatement);

        // Ders notlarını tablo halinde göster ve notları güncelleme formunu oluşturur
        if (mysqli_num_rows($ogrenciResult) > 0) {
            echo "<form method='POST'>";
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Öğrenci Numarası</th><th>Ad</th><th>Soyad</th><th>Notlar</th></tr></thead>";
            echo "<tbody>";
            while ($ogrenciRow = mysqli_fetch_assoc($ogrenciResult)) {
                echo "<tr><td>" . $ogrenciRow['ogrenciNumarasi'] . "</td><td>" . $ogrenciRow['isim'] . "</td><td>" . $ogrenciRow['soyad'] . "</td><td><input type='text' name='ders_notu[" . $ogrenciRow['ogrenciNumarasi'] . "]' value='" . $ogrenciRow['ders_notu'] . "'></td></tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<input class='btn btn-primary ml-2' type='submit' name='notver' value='Notları Kaydet'>";
            echo "</form>";
        } else {
            echo "Dersi alan öğrenci bulunamadı.";
        }

        // Notları güncelleme formu gönderildiğinde
        if (isset($_POST["notver"])) {
            $ders_notlari = $_POST["ders_notu"];

            // Veritabanında notları günceller
            foreach ($ders_notlari as $ogrenci_numarasi => $ders_notu) {
                // Ders notunu veritabanında güncelleme işlemini gerçekleştirir
                $updateSql = "UPDATE ogrencidersleri SET ders_notu = ? WHERE ogrenciNumarasi = ? AND ders_id = (SELECT ders_id FROM dersler WHERE ders_adi = ?)";
                $updateStatement = mysqli_prepare($baglanti, $updateSql);
                mysqli_stmt_bind_param($updateStatement, "sss", $ders_notu, $ogrenci_numarasi, $dersAdi);
                mysqli_stmt_execute($updateStatement);
            }

            echo "<div class='alert alert-success' role='alert'>Notlar başarıyla kaydedildi.</div>";
			header("Location: profileOgretmen.php");
			exit();
        }

    } else {
        echo "Ders bulunamadı.";
    }

    // Veritabanı bağlantısını kapatır
    mysqli_close($baglanti);

    echo "<a href='cikis.php' class='btn btn-warning ml-2'>Çıkış Yap</a>";
} else {
    echo "Bu Sayfayı Görüntüleme Yetkiniz Yoktur!";
}
?>







<!doctype html>
<html lang="tr-TR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Öğretmen Paneli</title>
  </head>
  <body>


    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>