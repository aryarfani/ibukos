<?php
require 'functions.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}
$kos = getKosById($_GET['id']);
//cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
    // cek apakah data berhasil ditambahkan atau tidak
    updateKos($_POST);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Kos</title>
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
        <div style="display: flex; justify-content: space-between;">
            <h1>Update Kos</h1>
            <a style="background: red; width: fit-content; height: fit-content;" class="btn" href='delete_kos.php?id=<?= $kos["id"] ?>'>Hapus</a>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <table>
                <input type="hidden" name="id" value="<?= $kos["id"] ?>">
                <tr>
                    <td><label for="nama">Nama kos </label></td>
                    <td><input type="text" name="nama" id="nama" required value="<?= $kos["nama"] ?>"></td>
                </tr>
                <tr>
                    <td><label for="telp">Telp </label></td>
                    <td><input type="text" name="telp" id="telp" required value="<?= $kos["telp"] ?>"></td>
                </tr>
                <tr>
                    <td><label for="alamat">Alamat </label></td>
                    <td><input type="text" name="alamat" id="alamat" required value="<?= $kos["alamat"] ?>"></td>
                </tr>
                <tr>
                    <td><label for="biaya">Biaya </label></td>
                    <td><input type="number" name="biaya" id="biaya" required value="<?= $kos["biaya"] ?>"></td>
                </tr>
                <tr>
                    <td><label for="deskripsi">Deskripsi </label></td>
                    <td><textarea type="text" name="deskripsi" id="deskripsi" required><?= $kos["deskripsi"] ?></textarea> </td>
                </tr>
                <tr>
                    <td colspan="2" style="color: red;">
                        <p>Masukkan gambar hanya jika anda ingin menggantinya</p>
                    </td>
                </tr>
                <tr>

                    <td><label for="gambar">Gambar </label></td>
                    <td><input type="file" name="gambar" id="gambar"></td>
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