<?php

require '../functions.php';

function index()
{
    global $conn;
    $query = mysqli_query($conn, "SELECT * FROM kos");
    while ($row = mysqli_fetch_array($query)) {
        $item[] = array(
            "nama" => $row['nama'],
            "telp" => $row['telp'],
            "biaya" => $row['biaya'],
            "deskripsi" => $row['deskripsi'],
            "alamat" => $row['alamat'],
            "gambar" => $row['gambar'],
            "created_at" => $row['created_at'],
        );
    }

    echo json_encode($item);
}
index();
