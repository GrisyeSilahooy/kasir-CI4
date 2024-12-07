<?php
// Koneksi ke database
include 'koneksi.php'; // Pastikan Anda memiliki file koneksi ke database

// Ambil ID produk dari parameter URL
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Query untuk mendapatkan data produk berdasarkan ID
    $query = "SELECT * FROM produk WHERE id_produk = '$id_produk'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        echo "Produk tidak ditemukan!";
        exit;
    }
} else {
    echo "ID produk tidak diberikan!";
    exit;
}

// Proses update data jika form disubmit
if (isset($_POST['submit'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Query untuk mengupdate data produk
    $update_query = "UPDATE produk SET
                        nama_produk = '$nama_produk',
                        harga = '$harga',
                        stok = '$stok'
                     WHERE id_produk = '$id_produk'";

    if (mysqli_query($conn, $update_query)) {
        echo "Produk berhasil diupdate!";
        header("Location: daftar-produk.php"); // Redirect ke halaman daftar produk
        exit;
    } else {
        echo "Gagal mengupdate produk: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Produk</title>
</head>

<body>
    <h1>Ubah Produk</h1>
    <form action="" method="post">
        <label for="nama_produk">Nama Produk:</label><br>
        <input type="text" name="nama_produk" id="nama_produk" value="<?=$data['nama_produk']?>" required><br><br>

        <label for="harga">Harga:</label><br>
        <input type="number" name="harga" id="harga" value="<?=$data['harga']?>" required><br><br>

        <label for="stok">Stok:</label><br>
        <input type="number" name="stok" id="stok" value="<?=$data['stok']?>" required><br><br>

        <button type="submit" name="submit">Update Produk</button>
    </form>
</body>

</html>