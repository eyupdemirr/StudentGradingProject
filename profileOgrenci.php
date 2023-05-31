<?php
session_start();

if (isset($_SESSION["isim"])) {
    include("baglanti.php");

    // Öğrenci bilgilerini veritabanından alır
    $isim = $_SESSION["isim"];
    $soyad = $_SESSION["soyad"];

    $ogrenciNumarasi = "";
    $ogrenciSql = "SELECT ogrenciNumarasi FROM kullanicilar WHERE isim = ? AND soyad = ?";
    $ogrenciStatement = mysqli_prepare($baglanti, $ogrenciSql);
    mysqli_stmt_bind_param($ogrenciStatement, "ss", $isim, $soyad);
    mysqli_stmt_execute($ogrenciStatement);
    $ogrenciResult = mysqli_stmt_get_result($ogrenciStatement);

    if (mysqli_num_rows($ogrenciResult) > 0) {
        $ogrenciRow = mysqli_fetch_assoc($ogrenciResult);
        $ogrenciNumarasi = $ogrenciRow["ogrenciNumarasi"];
    }

    // Ders notlarını veritabanından çeker
    $sql = "SELECT dersler.ders_adi, ogrencidersleri.ders_notu FROM ogrencidersleri INNER JOIN dersler ON ogrencidersleri.ders_id = dersler.ders_id INNER JOIN kullanicilar ON ogrencidersleri.ogrenciNumarasi = kullanicilar.ogrenciNumarasi WHERE kullanicilar.isim = ? AND kullanicilar.soyad = ?";
    $statement = mysqli_prepare($baglanti, $sql);
    mysqli_stmt_bind_param($statement, "ss", $isim, $soyad);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    echo "<h3>" . $_SESSION["isim"] . " Hoşgeldin </h3>";
    echo "<h4>Ders Notları:</h4>";

    // Ders notlarını tablo halinde gösterir
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-dark table-striped'>";
        echo "<thead><tr><th>Ders Adı</th><th>Ders Notu</th></tr></thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['ders_adi'] . "</td><td>" . $row['ders_notu'] . "</td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Kayıtlı ders notu bulunamadı.";
    }
    // Veritabanı bağlantısını kapatır
    mysqli_close($baglanti);

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">

    <title>Giriş Sayfası</title>
</head>
<body>
<div class="container p-5">
    <div class="card p-5">

        <div style='padding: 5px;'>
            <h4>Ders Ekle</h4>
            <form method='POST' action='ders_ekle.php'>
                <div class='form-group'>
                    <label for='dersAdi'>Ders Adı:</label>
                    <select class='form-control' id='dersAdi' name='dersAdi' required>
                        <?php
                        include("baglanti.php");

                        // Ders adlarını veritabanından çeker
                        $sql = "SELECT ders_adi FROM dersler";
                        $result = mysqli_query($baglanti, $sql);

                        // Ders adlarını seçenek olarak ekler
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['ders_adi'] . "'>" . $row['ders_adi'] . "</option>";
                        }

                        mysqli_close($baglanti);
                        ?>
                    </select>
                </div>
                <button type='submit' class='btn btn-primary'>Dersi Ekle</button>
                <a href='cikis.php' class='btn btn-warning'>Çıkış Yap</a>
            </form>
        </div>
    </div>
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
</body>
</html>
