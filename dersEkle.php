<?php

include("baglanti.php");

$ders_err = "";

if (isset($_POST["kaydet"])) {
    // İsim Kontrolleri
    if (empty($_POST["ders"])) {
        $ders_err = "Ders İsmi Boş Bırakılamaz";
    } 
    elseif (strlen($_POST["ders"]) < 3) {
        $ders_err = "Ders İsmi En Az 3 Karakterden Oluşmalıdır";
    } 
    elseif (strlen($_POST["ders"]) > 25) {
        $ders_err = "Ders İsmi En Fazla 25 Karakterden Oluşmalıdır";
    } 
    else {
        $ders = $_POST["ders"];
    }

    if (isset($ders)) {
        $ekle = "INSERT INTO dersler (ders_adi) VALUES ('$ders')";
        $calistirekle = mysqli_query($baglanti, $ekle);

        if ($calistirekle) {
            echo '<div class="alert alert-success" role="alert"> 
                Ders Eklendi 
            </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert"> 
                Ders Eklenemedi!
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
            <form action="dersEkle.php" method="POST">
                <h2><b> Ders Ekleme Ekranı </b></h2>
                <div class="form-group">
                    <label for="exampleInputEmail1">Ders İsmi</label>
                    <input type="text" class="form-control <?php if(!empty($ders_err)){ echo "is-invalid"; } ?> " id="exampleInputEmail1" name="ders" placeholder="Eklenecek Dersin İsmini Giriniz">
                    <div class="invalid-feedback"> <?php echo $ders_err; ?> </div>
                </div>
                <button type="submit" class="btn btn-primary" name="kaydet">Kayıt Et</button>
                <button type="reset" class="btn btn-primary" name="sıfırla">Sıfırla</button>
            </form>
            <br>
            <a href="profileAdmin.php"><button class="btn btn-primary">Admin Paneline Geri Dön</button></a>
        </div>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html> 