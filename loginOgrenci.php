<?php 
include("baglanti.php");

$name_err = "";
$surname_err = "";
$password_err = "";

if (isset($_POST["giris"])) {
    // İsim Kontrolleri
    if (empty($_POST["name"])) {
        $name_err = "İsim Alanı Boş Bırakılamaz";
    } else {
        $name = $_POST["name"];
    }

    // Soyisim Kontrolleri
    if (empty($_POST["surname"])) {
        $surname_err = "Soyisim Alanı Boş Bırakılamaz";
    } else {
        $surname = $_POST["surname"];
    }

    // Parola Kontrolleri
    if (empty($_POST["password"])) {
        $password_err = "Parola Alanı Boş Bırakılamaz";
    } else {
        $password = $_POST["password"];
    }

    if (isset($name) && isset($surname) && isset($password)) {
        $secim = "SELECT * FROM kullanicilar WHERE isim = '$name'";
        $calistir = mysqli_query($baglanti, $secim);
        $kayitsayisi = mysqli_num_rows($calistir);
    
        if ($kayitsayisi > 0) {
            $ilgilikayit = mysqli_fetch_assoc($calistir);
            $hashsifre = $ilgilikayit["sifre"];
            $rol = $ilgilikayit["roles"]; // Kullanıcının rolünü kontrol et
    
            if (password_verify($password, $hashsifre)) {
                session_start();
                $_SESSION["isim"] = $ilgilikayit["isim"];
                $_SESSION["soyad"] = $ilgilikayit["soyad"];
                $_SESSION["sifre"] = $ilgilikayit["sifre"];
    
                if ($rol == "ogrenci") {
                    header("location: profileOgrenci.php");
                    exit;
                } elseif ($rol == "ogretmen") {
                    header("location: loginOgretmen.php");
                    exit;
                } elseif ($rol == "admin") {
                    header("location: profileOgrenci.php");
                    exit;
                } else {
                    echo '<div class="alert alert-danger" role="alert"> Parola Yanlış </div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert"> İsim veya Soyisim Yanlış </div>';
            }
        } else {
            // İlgili kayıt kullanicilar tablosunda bulunamadı, ogretmenler tablosunu kontrol eder
            $ogretmenSecim = "SELECT * FROM ogretmenler WHERE isim = '$name'";
            $ogretmenCalistir = mysqli_query($baglanti, $ogretmenSecim);
            $ogretmenKayitSayisi = mysqli_num_rows($ogretmenCalistir);
    
            if ($ogretmenKayitSayisi > 0) {
                $ilgiliOgretmen = mysqli_fetch_assoc($ogretmenCalistir);
                $hashsifre = $ilgiliOgretmen["sifre"];
                $rol = $ilgiliOgretmen["roles"]; // Öğretmenin rolünü kontrol eder
    
                if (password_verify($password, $hashsifre)) {
                    session_start();
                    $_SESSION["isim"] = $ilgiliOgretmen["isim"];
                    $_SESSION["soyad"] = $ilgiliOgretmen["soyad"];
                    $_SESSION["sifre"] = $ilgiliOgretmen["sifre"];
    
                    if ($rol == "ogretmen") {
                        header("location: loginOgretmen.php");
                        exit;
                    } elseif ($rol == "admin") {
                        header("location: profileOgrenci.php");
                        exit;
                    } else {
                        echo '<div class="alert alert-danger" role="alert"> Parola Yanlış </div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert"> İsim veya Soyisim Yanlış </div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert"> İsim veya Soyisim Yanlış </div>';
            }
        }
    
        mysqli_close($baglanti);
    }
    
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
            <form action="loginOgrenci.php" method="POST">
                <h2><b>Öğrenci Giriş Ekranı</b></h2>
                <div class="form-group">
                    <label for="exampleInputEmail1">İsim</label>
                    <input type="text" class="form-control <?php if(!empty($name_err)){ echo "is-invalid"; } ?> " id="exampleInputEmail1" name="name" placeholder="İsminizi Giriniz">
                    <div class="invalid-feedback"> <?php echo $name_err; ?> </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Soyisim</label>
                    <input type="text" class="form-control <?php if(!empty($surname_err)){ echo "is-invalid"; } ?>" id="exampleInputEmail1" name="surname" placeholder="Soyisminizi Giriniz">
                    <div class="invalid-feedback"> <?php echo $surname_err; ?> </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Şifre</label>
                    <input type="password" class="form-control <?php if(!empty($password_err)){ echo "is-invalid"; } ?>" id="exampleInputPassword1" name="password" placeholder="Şifrenizi Giriniz">
                    <div class="invalid-feedback"> <?php echo $password_err; ?> </div>
                </div>
                <button type="submit" class="btn btn-primary" name="giris">Giriş Yap</button>
                <button type="reset" class="btn btn-primary" name="sıfırla">Sıfırla</button>
                <a href="loginAdmin.php" class="btn btn-primary float-right">Admin Giriş</a>
                <a href="loginOgretmen.php" class="btn btn-primary float-right mr-1">Öğretmen Giriş</a>
            </form>
            <br>
            <p>Henüz Kayıt Olmadınız mı? <a href="kayitOgrenci.php">Kayit Ol</a></p>
        </div>
    </div>

    <!-- jQuery , Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
