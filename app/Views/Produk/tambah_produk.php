<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sembako';

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];

    // Validasi gambar
    $gambar = '';
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['error'] == 0) {
        $gambar = 'uploads/' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
    }

    // Validasi input
    if (!empty($nama_produk) && !empty($harga) && !empty($stok)) {
        $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok, deskripsi, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $nama_produk, $harga, $stok, $deskripsi, $gambar);
        if ($stmt->execute()) {
            echo "Produk berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan produk: " . $stmt->error;
        }
    } else {
        echo "Harap isi semua data yang diperlukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>

<body>
    <h1>Tambah Produk Sembako</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="nama_produk">Nama Produk:</label><br>
        <input type="text" name="nama_produk" id="nama_produk" required><br><br>

        <label for="harga">Harga:</label><br>
        <input type="number" name="harga" id="harga" required><br><br>

        <label for="stok">Stok:</label><br>
        <input type="number" name="stok" id="stok" required><br><br>

        <label for="deskripsi">Deskripsi:</label><br>
        <textarea name="deskripsi" id="deskripsi" rows="5"></textarea><br><br>

        <label for="gambar">Gambar Produk:</label><br>
        <input type="file" name="gambar" id="gambar" accept="image/*"><br><br>

        <button type="submit">Tambah Produk</button>
    </form>
</body>

</html>
