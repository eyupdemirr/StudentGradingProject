<?php
session_start();

if (isset($_SESSION["isim"])) {
    include("baglanti.php");

    if (isset($_GET["ogrenciNumarasi"])) {
        $ogrenciNumarasi = $_GET["ogrenciNumarasi"];

        // Kullanıcıyı kaldırma işlemi (kullanicilar tablosu)
        $kullanicilarSql = "DELETE FROM kullanicilar WHERE ogrenciNumarasi = '$ogrenciNumarasi'";
        if (mysqli_query($baglanti, $kullanicilarSql)) {
            echo '<div class="alert alert-success" role="alert">Kayıt Eklendi</div>';
            // Kullanıcı kaldırıldıktan sonra profileAdmin.php sayfasına yönlendirme yapar
            header("Location: profileAdmin.php");
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Kayıt Eklenemedi!</div>' . mysqli_error($baglanti);
        }
    } elseif (isset($_GET["id"])) {
        $id = $_GET["id"];

        // Kullanıcıyı kaldırma işlemi (ogretmenler tablosu)
        $ogretmenlerSql = "DELETE FROM ogretmenler WHERE id = '$id'";
        if (mysqli_query($baglanti, $ogretmenlerSql)) {
            echo '<div class="alert alert-success" role="alert">Kayıt Eklendi</div>';
            header("Location: profileAdmin.php");
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Kayıt Eklenemedi!</div>' . mysqli_error($baglanti);
        }
    } else {
        echo "Geçersiz kullanıcı numarası veya ID.";
    }

    mysqli_close($baglanti);

} else {
    echo "Bu Sayfayı Görüntüleme Yetkiniz Yoktur!";
}
?>

