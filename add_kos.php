<?php
require 'functions.php';
if (!isset($_SESSION["login"])) {
	header("Location: login.php");
	exit();
}

//cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
	// cek apakah data berhasil ditambahkan atau tidak
	if (tambahKos($_POST) > 0) {
		echo "
			<script>
				document.location.href = 'index.php'
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'index.php'
			</script>
		";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Tambah Kos</title>
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
		<h1 class="h1">Tambah Kos</h1>

		<form action="" method="post" enctype="multipart/form-data">
			<table>
				<tr>
					<td><label for="nama">Nama kos </label></td>
					<td><input type="text" name="nama" id="nama" required></td>
				</tr>
				<tr>
					<td><label for="telp">Telp </label></td>
					<td><input type="text" name="telp" id="telp" required></td>
				</tr>
				<tr>
					<td><label for="alamat">Alamat </label></td>
					<td><input type="text" name="alamat" id="alamat" required></td>
				</tr>
				<tr>
					<td><label for="biaya">Biaya </label></td>
					<td><input type="number" name="biaya" id="biaya" required></td>
				</tr>
				<tr>
					<td><label for="deskripsi">Deskripsi </label></td>
					<td><textarea type="text" name="deskripsi" id="deskripsi" required></textarea> </td>
				</tr>
				<tr>
					<td><label for="gambar">Gambar </label></td>
					<td><input type="file" name="gambar" id="gambar" required></td>
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