<?php
// init session
session_start();

// connect to db
$conn = mysqli_connect("localhost", "root", "", "ibukos");

function query($query)
{
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

//* ----------------------- FUNCTIONS FOR KOS ------------------------

function tambahKos($data)
{

	global $conn;
	// to protect XSS
	$nama = htmlspecialchars($data["nama"]);
	$telp = htmlspecialchars($data["telp"]);
	$biaya = htmlspecialchars($data["biaya"]);
	$deskripsi = htmlspecialchars($data["deskripsi"]);
	$alamat = htmlspecialchars($data["alamat"]);

	// upload gambar
	$gambar = uploadGambar();
	if (!$gambar) {
		return false;
	}

	// get current member_id
	$memberId = $_SESSION["id"];

	// get current time
	$time = date("Y-m-d");
	// query insert data
	$query = "INSERT INTO kos VALUES('','$memberId', '$nama', '$telp', '$biaya', '$deskripsi', '$alamat', '$gambar', '$time')";
	mysqli_query($conn, $query) or trigger_error(mysqli_error($conn), E_USER_ERROR);

	return mysqli_affected_rows($conn);
}

function uploadGambar()
{
	$namaFile = $_FILES['gambar']['name'];
	$error = $_FILES['gambar']['error'];
	$tmpName = $_FILES['gambar']['tmp_name'];

	// check if image exists
	if ($error === 4) {
		echo "<script>
		alert('pilih gambar terlebih dahulu!');</script>";
		return false;
	}

	// generate new unique name
	$ekstensiGambar = explode('.', $namaFile);
	$ekstensiGambar = strtolower(end($ekstensiGambar));
	$namaFileBaru = uniqid();
	$namaFileBaru .= '.';
	$namaFileBaru .= $ekstensiGambar;

	move_uploaded_file($tmpName, 'images/' . $namaFileBaru);

	return $namaFileBaru;
}

function getKosById($id)
{
	return query("SELECT * FROM kos WHERE id = $id")[0];
}

function updateKos($data)
{

	global $conn;
	// to protect XSS
	$nama = htmlspecialchars($data["nama"]);
	$telp = htmlspecialchars($data["telp"]);
	$biaya = htmlspecialchars($data["biaya"]);
	$deskripsi = htmlspecialchars($data["deskripsi"]);
	$alamat = htmlspecialchars($data["alamat"]);
	$id = $data["id"];

	$query = null;

	// check if user add new image
	if ($_FILES['gambar']['name'] != '') {
		// upload gambar
		$gambar = uploadGambar();
		if (!$gambar) {
			return false;
		}

		$query = "UPDATE kos SET nama = '$nama', telp = '$telp',
	biaya = '$biaya', deskripsi =  '$deskripsi', alamat = '$alamat',
	gambar = '$gambar' WHERE id = $id";
	} else {
		$query = "UPDATE kos SET nama = '$nama', telp = '$telp',
	biaya = '$biaya', deskripsi =  '$deskripsi', alamat = '$alamat'
	WHERE id = $id";
	}

	mysqli_query($conn, $query) or trigger_error(mysqli_error($conn), E_USER_ERROR);

	return mysqli_affected_rows($conn);
}

function deleteKos($id)
{
	global $conn;
	mysqli_query($conn, "DELETE FROM kos WHERE id = $id");
	return mysqli_affected_rows($conn);
}

//* ----------------------- FUNCTIONS FOR MEMBER ------------------------

function registerMember($data)
{
	global $conn;
	// to protect SQL-Injection
	$username = mysqli_real_escape_string($conn, $data["username"]);
	$telp = mysqli_real_escape_string($conn, $data["telp"]);
	$password = mysqli_real_escape_string($conn, $data["password"]);
	$password2 = mysqli_real_escape_string($conn, $data["password2"]);

	// check if username exists
	$result = mysqli_query($conn, "SELECT username FROM member WHERE username = '$username' ");
	if (mysqli_fetch_assoc($result)) {
		echo "<script>
				alert('Username sudah dipakai!');
		</script>";
		return false;
	}

	// confirm password
	if ($password !== $password2) {
		echo "<script>
				alert('Konfirmasi password salah!');
		</script>";
		return false;
	}

	// create otp random 4 digit
	$otp = rand(1000, 9999);

	// encrypt password
	$password = password_hash($password, PASSWORD_DEFAULT);

	// insert to db
	mysqli_query($conn, "INSERT INTO member VALUES('', '$username', '$password', '$telp', '$otp' ) ");

	sendOtpMessage(sanitizeTelp($telp), $otp);

	return mysqli_affected_rows($conn);
}

function sanitizeTelp($phone)
{
	// delete whitespace and plus
	$telp = str_replace(['+', ' '], '', $phone);
	// if number starts with 0
	if ($telp[0] == '0') {
		// replace 0 with 62
		$telp = '62' . substr($telp, 1);
	}

	return $telp;
}

function sendOtpMessage($telp, $kode)
{
	$userkey = '90efe8a10d75';
	$passkey = '83a6f9812419e9ab0457448d';
	$telepon = $telp;
	$message = "
Selamat bergabung dengan IBUKOS
kode verifikasi (OTP) $kode
	";
	$url = 'https://console.zenziva.net/wareguler/api/sendWA/';
	$curlHandle = curl_init();
	curl_setopt($curlHandle, CURLOPT_URL, $url);
	curl_setopt($curlHandle, CURLOPT_HEADER, 0);
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
	curl_setopt($curlHandle, CURLOPT_POST, 1);
	curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
		'userkey' => $userkey,
		'passkey' => $passkey,
		'to' => $telepon,
		'message' => $message
	));
	curl_exec($curlHandle);
	curl_close($curlHandle);
}

//* function to verify otp from member after logged in
// will set otp to 0 after member succed to confirm otp
function verifyOtp($input_kode)
{
	global $conn;
	$id = $_SESSION["id"];

	$member = query("SELECT * FROM member WHERE id = $id")[0];

	if ($input_kode == $member["otp"]) {
		// delete otp from DB
		mysqli_query(
			$conn,
			"UPDATE member SET otp = '0' WHERE id = $id"
		);
		$_SESSION["username"] = $member["username"];
		$_SESSION["login"] = true;
		header("Location: index.php");
		die();
	}

	return false;
}

function loginMember()
{
	global $conn;

	$username = $_POST["username"];
	$password = $_POST["password"];

	$result = mysqli_query($conn, "SELECT * FROM member WHERE username = '$username' ");
	// chech if usernmae exists
	if (mysqli_num_rows($result) === 1) {
		// cek password
		$row = mysqli_fetch_assoc($result);
		if (password_verify($password, $row["password"])) {
			// set session
			$_SESSION["id"] = $row["id"];

			// if otp is not 0 means its not confirmed
			// throw to confirm otp
			if ($row["otp"] != "0") {
				header("Location: confirm_otp.php");
			} else {
				$_SESSION["username"] = $row["username"];
				$_SESSION["login"] = true;
				header("Location: index.php");
			}
			die();
		}
	}
	return false;
}

function getLoggedMember()
{
	$id = $_SESSION["id"];
	return query("SELECT * FROM member WHERE id = $id")[0];
}

function updateMember($data)
{
	global $conn;
	// to protect SQL-Injection
	$telp = mysqli_real_escape_string($conn, $data["telp"]);
	$password = mysqli_real_escape_string($conn, $data["password"]);
	$password2 = mysqli_real_escape_string($conn, $data["password2"]);

	// confirm password
	if ($password !== $password2) {
		echo "<script>
				alert('Konfirmasi password salah!');
		</script>";
		return false;
	}

	// encrypt password
	$password = password_hash($password, PASSWORD_DEFAULT);

	$id = $_SESSION["id"];

	// insert to db
	mysqli_query(
		$conn,
		"UPDATE member SET password = '$password',telp = '$telp' WHERE id = $id"
	);

	return mysqli_affected_rows($conn);
}

// function ubah($data)
// {
// 	global $conn;
// 	// ambil data dari tiap elemen dalam form
// 	$id = $data["id"];
// 	$nama = htmlspecialchars($data["nama"]);
// 	$jenis = htmlspecialchars($data["jenis"]);
// 	$umur = htmlspecialchars($data["umur"]);
// 	$harga = htmlspecialchars($data["harga"]);
// 	$gambarLama = htmlspecialchars($data["gambarLama"]);

// 	// cek apakah user pilih gambar baru atau tidak
// 	if ($_FILES['gambar']['error'] === 4) {
// 		$gambar = $gambarLama;
// 	} else {
// 		$gambar = upload();
// 	}

// 	// query insert data
// 	$query = "UPDATE isikucing SET 
// 				nama = '$nama',
// 				jenis = '$jenis',
// 				umur = '$umur',
// 				harga = '$harga',
// 				gambar = '$gambar'
// 				WHERE id = $id;
// 			 ";
// 	mysqli_query($conn, $query);

// 	return mysqli_affected_rows($conn);
// }

// function cari($keyword)
// {
// 	$query = "SELECT * FROM isikucing WHERE 
// 	nama LIKE '%$keyword%' -- OR 
// 	-- jenis LIKE '%$keyword%' OR
// 	-- umur LIKE '%$keyword%' OR
// 	-- harga LIKE '%$keyword%' OR
// 	";
// 	return query($query);
// }