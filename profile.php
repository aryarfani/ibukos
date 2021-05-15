<?php
require 'functions.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

$data = getLoggedMember();
//cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
    // cek apakah data berhasil ditambahkan atau tidak
    if (updateMember($_POST) > 0) {
        echo "
			<script>
				document.location.href = 'index.php'
			</script>
		";
    } else {
        echo "
			<script>
				alert('Registrasi Gagal !!!');
				document.location.href = 'index.php'
			</script>
		";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Info</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <nav>
            <div class="title">
                <a href="index.php">IBUKOS</a>
            </div>
            <div>
                <a style="background: red;" class="btn" href="logout.php">Keluar</a>
            </div>
        </nav>
    </div>

    <div class="container form">

        <h1 class="h1"><a href="index.php">Update Info</a></h1>

        <form action="" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input type="text" name="username" id="username" value="<?= $data["username"] ?>" readonly></td>
                </tr>
                <tr>
                    <td><label for="telp">No. Handphone</label></td>
                    <td><input type="text" name="telp" id="telp" required value="<?= $data["telp"] ?>"></td>
                </tr>
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input type="password" name="password" id="password" required></td>
                </tr>
                <tr>
                    <td><label for="password2">Konfirmasi Password</label></td>
                    <td><input type="password" name="password2" id="password2" required></td>
                </tr>
                <tr>
                    <td colspan="2"><button class="btn" type="submit" name="submit" required>Simpan</button></td>
                </tr>
            </table>

        </form>
    </div>
</body>

<?php include 'footer.php' ?>

</html>