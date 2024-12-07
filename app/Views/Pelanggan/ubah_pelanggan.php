<?php
// Pastikan session dan koneksi database sudah diatur
session_start();
include 'koneksi.php'; // Sesuaikan dengan nama file koneksi Anda

// Validasi ID pelanggan di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID pelanggan tidak valid!";
    header("Location: daftar-pelanggan.php");
    exit;
}

$id_pelanggan = intval($_GET['id']);

// Ambil data pelanggan berdasarkan ID
$query = "SELECT * FROM pelanggan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Data pelanggan tidak ditemukan!";
    header("Location: daftar-pelanggan.php");
    exit;
}

$pelanggan = $result->fetch_assoc();

// Proses pembaruan data jika form dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelanggan = htmlspecialchars(trim($_POST['nama_pelanggan']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telepon = htmlspecialchars(trim($_POST['telepon']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));

    // Validasi data
    if (empty($nama_pelanggan) || empty($email) || empty($telepon) || empty($alamat)) {
        $_SESSION['error'] = "Semua kolom wajib diisi!";
        header("Location: ubah_pelanggan.php?id=$id_pelanggan");
        exit;
    }

    if (!ctype_digit($telepon)) {
        $_SESSION['error'] = "Kolom telepon hanya boleh berisi angka!";
        header("Location: ubah_pelanggan.php?id=$id_pelanggan");
        exit;
    }

    // Update data pelanggan
    $query = "UPDATE pelanggan SET nama_pelanggan = ?, email = ?, telepon = ?, alamat = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssssi", $nama_pelanggan, $email, $telepon, $alamat, $id_pelanggan);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Data pelanggan berhasil diperbarui.";
            header("Location: daftar-pelanggan.php");
            exit;
        } else {
            error_log("SQL Error: " . $stmt->error);
            $_SESSION['error'] = "Terjadi kesalahan saat memperbarui data.";
        }

        $stmt->close();
    } else {
        error_log("SQL Prepare Error: " . $conn->error);
        $_SESSION['error'] = "Terjadi kesalahan sistem. Silakan coba lagi.";
    }

    header("Location: ubah_pelanggan.php?id=$id_pelanggan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Pelanggan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        max-width: 600px;
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

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    input,
    textarea,
    button {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    a {
        text-decoration: none;
        color: #007bff;
    }

    a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <h1>Ubah Data Pelanggan</h1>
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
        <label for="nama_pelanggan">Nama Pelanggan:</label>
        <input type="text" id="nama_pelanggan" name="nama_pelanggan"
            value="<?=htmlspecialchars($pelanggan['nama_pelanggan'])?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?=htmlspecialchars($pelanggan['email'])?>" required>

        <label for="telepon">Telepon:</label>
        <input type="text" id="telepon" name="telepon" value="<?=htmlspecialchars($pelanggan['telepon'])?>" required>

        <label for="alamat">Alamat:</label>
        <textarea id="alamat" name="alamat" required><?=htmlspecialchars($pelanggan['alamat'])?></textarea>

        <button type="submit">Simpan Perubahan</button>
    </form>
    <br>
    <a href="daftar-pelanggan.php">Kembali ke Daftar Pelanggan</a>
</body>

</html>