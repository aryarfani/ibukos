<?php
require 'functions.php';
$dataKos = query("SELECT * FROM kos");


// ! when user clicked open update
// add logout there

?>
<!DOCTYPE html>
<html>

<head>
    <title>Ibukos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
</head>


<body>

    <div class="container">
        <nav>
            <div class="title">
                <a href="index.php">IBUKOS</a>
            </div>
            <div class="links">
                <a href="add_kos.php">Tambah Kos</a>
                <?php if (isset($_SESSION["username"])) : ?>
                    <a href="profile.php"><?= $_SESSION["username"] ?></a>
                <?php else : ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>

        <?php foreach ($dataKos as $row) : ?>

            <div class="content">
                <div class="flex-item-1">
                    <img src="images/<?= $row["gambar"]; ?>">
                </div>
                <div class="flex-item-2">
                    <div>
                        <span class="nama"> <?= $row["nama"]; ?></span>
                        <!-- Check if user is logged and the logged user is the uploader -->
                        <?php if (isset($_SESSION["id"]) && $_SESSION["id"] == $row["member_id"]) : ?>
                            <a class="link" href='update_kos.php?id=<?= $row["id"] ?>'>Edit</a>
                        <?php endif; ?>
                    </div>
                    <div class=""><?= $row["deskripsi"]; ?></div>
                    <div class="link">
                        <i class="fas fa-phone-alt"></i>
                        <a class="link" href="https://wa.me/<?= sanitizeTelp($row["telp"]); ?>">
                            <?= $row["telp"]; ?>
                        </a>
                    </div>
                    <div class=""><i class="fas fa-map-marker-alt"></i> <?= $row["alamat"]; ?></div>
                    <div class=""><i class="fas fa-tags"></i> Rp. <?= $row["biaya"]; ?> / bulan</div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</body>

<?php include 'footer.php' ?>

</html>