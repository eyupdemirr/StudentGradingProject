<?php
session_start();

include("baglanti.php");

$name_err = "";
$surname_err = "";
$password_err = "";

// Giriş işlemini kontrol etmek için fonksiyon oluşturur
function girisYap($name, $password)
{
    global $baglanti;

    // Kullanıcının giriş bilgilerini veritabanında kontrol eder (SQL Injectiona karşı koruma şablonu)
    $query = "SELECT * FROM kullanicilar WHERE isim = ? AND sifre = ?";
    $stmt = mysqli_prepare($baglanti, $query);
    mysqli_stmt_bind_param($stmt, "ss", $name, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $kullanici = mysqli_fetch_assoc($result);

        // Kullanıcının rolünü oturumda saklar
        $_SESSION['roles'] = $kullanici['roles'];

        // Kullanıcının rolüne göre ilgili sayfaya yönlendirme yapar
        if ($kullanici['roles'] == 'admin') {
            header("Location: admin_sayfasi.php");
            exit();
        } elseif ($kullanici['roles'] == 'ogretmen') {
            header("Location: ogretmen_sayfasi.php");
            exit();
        } elseif ($kullanici['roles'] == 'ogrenci') {
            header("Location: ogrenci_sayfasi.php");
            exit();
        }
    } else {
        echo '<div class="alert alert-danger" role="alert"> 
            Geçersiz kullanıcı adı veya şifre.
            </div>';
    }
}

// Kullanıcı girişi işlemini gerçekleştirir
if (isset($_POST['giris'])) {
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

        $secim = "SELECT * FROM kullanicilar WHERE isim = ?";
        $stmt = mysqli_prepare($baglanti, $secim);
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $calistir = mysqli_stmt_get_result($stmt);
        $kayitsayisi = mysqli_num_rows($calistir);

        if ($kayitsayisi > 0) {
            $ilgilikayit = mysqli_fetch_assoc($calistir);
            $hashsifre = $ilgilikayit["sifre"];

            if (password_verify($password, $hashsifre)) {
                $_SESSION["isim"] = $ilgilikayit["isim"];
                $_SESSION["soyad"] = $ilgilikayit["soyad"];
                $_SESSION["sifre"] = $ilgilikayit["sifre"];
                girisYap($name, $password);
            } else {
                echo '<div class="alert alert-danger" role="alert"> 
                Parola Yanlış
                </div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert"> 
            İsim veya Soyisim Yanlış
            </div>';
        }
        mysqli_stmt_close($stmt);
    }
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
            <form action="rol.php" method="POST">
                <h2><b>Öğrenci Giriş Ekranı</b></h2>
                <div class="form-group">
                    <label for="exampleInputEmail1">İsim</label>
                    <input type="text" class="form-control <?php if(!empty($name_err)){ echo "is-invalid"; } ?> " id="name" name="name" placeholder="İsminizi Giriniz">
                    <div class="invalid-feedback"> <?php echo $name_err; ?> </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Soyisim</label>
                    <input type="text" class="form-control <?php if(!empty($surname_err)){ echo "is-invalid"; } ?>" id="surname" name="surname" placeholder="Soyisminizi Giriniz">
                    <div class="invalid-feedback"> <?php echo $surname_err; ?> </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Şifre</label>
                    <input type="password" class="form-control <?php if(!empty($password_err)){ echo "is-invalid"; } ?>" id="password" name="password" placeholder="Şifrenizi Giriniz">
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

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

