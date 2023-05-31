<?php
include("baglanti.php");

$name_err = "";
$surname_err = "";
$password_err = "";
$brans_err = "";

if (isset($_POST["kaydet"])) {
    // İsim Kontrolleri
    if (empty($_POST["name"])) {
        $name_err = "İsim Alanı Boş Bırakılamaz";
    } elseif (strlen($_POST["name"]) < 3) {
        $name_err = "İsim En Az 3 Karakterden Oluşmalıdır";
    } else {
        $name = $_POST["name"];
    }

    // Soyisim Kontrolleri
    if (empty($_POST["surname"])) {
        $surname_err = "Soyisim Alanı Boş Bırakılamaz";
    } elseif (strlen($_POST["surname"]) < 3) {
        $surname_err = "Soyisim En Az 3 Karakterden Oluşmalıdır";
    } else {
        $surname = $_POST["surname"];
    }

    // Parola Kontrolleri
    if (empty($_POST["password"])) {
        $password_err = "Parola Alanı Boş Bırakılamaz";
    } elseif (strlen($_POST["password"]) < 3) {
        $password_err = "Parola En Az 3 Karakterden Oluşmalıdır";
    } else {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }

    // Branş Kontrolleri
    if (empty($_POST["brans"])) {
        $brans_err = "Parola Alanı Boş Bırakılamaz";
    } elseif (strlen($_POST["brans"]) > 12) {
        $brans_err = "Branşınız En Fazla 12 Karakterden Oluşmalıdır";
    } else {
        $brans = $_POST["brans"];
    }

    if (isset($name) && isset($surname) && isset($password)) {
        $ekle = "INSERT INTO ogretmenler (isim, soyad, sifre, roles, ders_adi) VALUES ('$name','$surname','$password', 'ogretmen', '$brans')";
        $calistirekle = mysqli_query($baglanti, $ekle);

        if ($calistirekle) {
            echo '<div class="alert alert-success" role="alert"> 
                Kayıt Eklendi 
            </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert"> 
                Kayıt Eklenemedi!
            </div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert"> 
            İlgili Kısımları Lütfen Doğru Şekilde Doldurun!
        </div>';
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

    <title>Kayıt Sayfası</title>
  </head>
  <body>
    <div class="container p-5">
        <div class="card p-5">
            <form action="kayitOgretmen.php" method="POST">
                <h2><b> Öğretmen Kayıt Ekranı </b></h2>
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
                <div class="form-group">
                    <label for="exampleInputPassword1">Öğretmen Branşı</label>
                    <select class="form-control <?php if(!empty($brans_err)){ echo "is-invalid"; } ?>" name="brans">
                        <option value="">Bir branş seçin</option>
                        <?php
                        include("baglanti.php");
                        // Dersleri alır
                        $sql = "SELECT ders_adi FROM dersler";
                        $result = mysqli_query($baglanti, $sql);
                        // Dersleri döngüyle görüntüler
                        while ($row = mysqli_fetch_assoc($result)) {
                            $ders_adi = $row['ders_adi'];
                            echo "<option value='$ders_adi'>$ders_adi</option>";
                        }
                        mysqli_close($baglanti);
                        ?>
                    </select>
                    <div class="invalid-feedback"> <?php echo $brans_err; ?> </div>
                </div>


                <button type="submit" class="btn btn-primary" name="kaydet">Kayıt Et</button>
                <button type="reset" class="btn btn-primary" name="sıfırla">Sıfırla</button>
            </form>
            <br>
            <p>Zaten bir hesabınız var mı? <a href="loginOgrenci.php">Giriş Yap</a></p>
        </div>
    </div>


    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html> 