<?php
// Pastikan session dan koneksi database sudah diatur
session_start();
include 'koneksi.php'; // Ganti sesuai nama file koneksi Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dengan validasi dasar
    $nama_pelanggan = htmlspecialchars(trim($_POST['nama_pelanggan']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telepon = htmlspecialchars(trim($_POST['telepon']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));

    // Validasi data
    if (empty($nama_pelanggan) || empty($email) || empty($telepon) || empty($alamat)) {
        $_SESSION['error'] = "Semua kolom wajib diisi!";
        header("Location: tambah_pelanggan.php");
        exit;
    }

    // Validasi tambahan untuk telepon (hanya angka)
    if (!ctype_digit($telepon)) {
        $_SESSION['error'] = "Kolom telepon hanya boleh berisi angka!";
        header("Location: tambah_pelanggan.php");
        exit;
    }

    // Simpan data ke database
    $query = "INSERT INTO pelanggan (nama_pelanggan, email, telepon, alamat) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssss", $nama_pelanggan, $email, $telepon, $alamat);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Data pelanggan berhasil ditambahkan.";
            header("Location: tambah_pelanggan.php");
            exit;
        } else {
            // Jika gagal, catat kesalahan
            error_log("SQL Error: " . $stmt->error);
            $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.";
        }

        $stmt->close();
    } else {
        // Jika query gagal dipersiapkan
        error_log("SQL Prepare Error: " . $conn->error);
        $_SESSION['error'] = "Terjadi kesalahan sistem. Silakan coba lagi.";
    }

    header("Location: tambah_pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    .message {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
    }

    .error {
        background-color: #f8d7da;
        color: #842029;
    }

    .success {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    </style>
</head>

<body>
    <h1>Tambah Data Pelanggan</h1>
    <?php
if (isset($_SESSION['error'])) {
    echo "<div class='message error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo "<div class='message success'>" . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}
?>
    <form action="" method="POST">
        <label for="nama_pelanggan">Nama Pelanggan:</label><br>
        <input type="text" id="nama_pelanggan" name="nama_pelanggan" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="telepon">Telepon:</label><br>
        <input type="text" id="telepon" name="telepon" required><br><br>

        <label for="alamat">Alamat:</label><br>
        <textarea id="alamat" name="alamat" required></textarea><br><br>

        <button type="submit">Tambah</button>
    </form>
    <br>
    <a href="daftar-pelanggan.php">Kembali ke Daftar Pelanggan</a>
</body>

</html>