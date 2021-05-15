<?php

require 'functions.php';

if (isset($_POST["submit"])) {
    $error = verifyOtp($_POST["kode"]);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Konfirmasi OTP</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <nav>
            <div class="title">
                <a href="index.php">IBUKOS</a>
            </div>
        </nav>
    </div>

    <div class="container form">
        <h1 class="h1"><a href="index.php">Masukkan Kode Verifikasi</a></h1>
        <?php if (isset($error)) : ?>
            <div class="error-text">Kode Verifikasi Salah</div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="kode">Kode Verifikasi</label></td>
                    <td><input type="text" name="kode" id="kode" required></td>
                </tr>
                <tr>
                    <td colspan="2"><button class="btn" type="submit" name="submit" required>Konfirmasi</button></td>
                </tr>

            </table>

        </form>
    </div>
</body>

<?php include 'footer.php' ?>

</html>