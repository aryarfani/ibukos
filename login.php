<?php

require 'functions.php';

if (isset($_POST["login"])) {
    $error = loginMember($_POST);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register Member</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <nav>
            <div class="title">
                <a href="index.php">IBUKOS</a>
            </div>
            <div class="links">
                <a href="register.php">Register</a>
            </div>
        </nav>
    </div>

    <div class="container form">
        <h1 class="h1"><a href="index.php">Login</a></h1>
        <?php if (isset($error)) : ?>
            <div class="error-text">Username / Password salah</div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input type="text" name="username" id="username" required></td>
                </tr>
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input type="password" name="password" id="password" required></td>
                </tr>

                <tr>
                    <td colspan="2"><button class="btn" type="submit" name="login" required>Simpan</button></td>
                </tr>

                <tr>
                    <td>Belum punya akun ? </td>
                    <td><a class="link" href="register.php">Buat dulu</a></td>
                </tr>
            </table>

        </form>
    </div>
</body>

<?php include 'footer.php' ?>

</html>