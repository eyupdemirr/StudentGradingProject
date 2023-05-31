<?php
session_start();

if (isset($_SESSION["isim"])) {
    include("baglanti.php");

    // Kullanicilar tablosundaki verileri çeker ve rollerine göre sıralar
    $kullanicilarSql = "SELECT * FROM kullanicilar ORDER BY FIELD(roles, 'admin', 'ogretmen', 'ogrenci')";
    $kullanicilarResult = mysqli_query($baglanti, $kullanicilarSql);

    // Ogretmenler tablosundaki verileri çeker ve adlarına göre sıralar
    $ogretmenlerSql = "SELECT * FROM ogretmenler ORDER BY isim";
    $ogretmenlerResult = mysqli_query($baglanti, $ogretmenlerSql);

    echo "<h3>" . $_SESSION["isim"] . " Hoşgeldin </h3>";
    echo "<h4>Kullanıcılar:</h4>";

    // Kullanicilar tablosunu tablo halinde gösterir
    if (mysqli_num_rows($kullanicilarResult) > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>İsim</th><th>Soyisim</th><th>Rol</th><th></th></tr></thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($kullanicilarResult)) {
            echo "<tr><td>" . $row['isim'] . "</td><td>" . $row['soyad'] . "</td><td>" . $row['roles'] . "</td><td><a href='kullaniciKaldir.php?ogrenciNumarasi=" . $row['ogrenciNumarasi'] . "' class='btn btn-danger'>Kullanıcıyı Kaldır</a></td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Kayıtlı kullanıcı bulunamadı.";
    }

    echo "<h4>Öğretmenler:</h4>";

    // Ogretmenler tablosunu tablo halinde gösterir
    if (mysqli_num_rows($ogretmenlerResult) > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>İsim</th><th>Soyisim</th><th>Branş</th></tr></thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($ogretmenlerResult)) {
            echo "<tr><td>" . $row['isim'] . "</td><td>" . $row['soyad'] . "</td><td>" . $row['ders_adi'] . "</td><td><a href='kullaniciKaldir.php?id=" . $row['id'] . "' class='btn btn-danger'>Kullanıcıyı Kaldır</a></td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Kayıtlı öğretmen bulunamadı.";
    }

    // Veritabanı bağlantısını kapat
    mysqli_close($baglanti);

} else {
    echo "Bu Sayfayı Görüntüleme Yetkiniz Yoktur!";
    mysqli_close($baglanti);
}
?>


<!doctype html>
<html lang="tr-TR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Giriş Sayfası</title>
  </head>
  <body>
    <div class="container p-5">
        <div class="card p-5">
            <?php
                    echo "<a href='kayitAdmin.php' class='btn btn-primary float-right'>Admin Ekle</a>";
                    echo "<a href='kayitOgretmen.php' class='btn btn-primary float-right mt-1'>Öğretmen Ekle</a>";
                    echo "<a href='dersEkle.php' class='btn btn-primary float-right mt-1'>Ders Ekle</a>";
                    echo "<a href='cikis.php' class='btn btn-warning float-right mt-1'>Çıkış Yap</a>";
            ?>
        </div>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
