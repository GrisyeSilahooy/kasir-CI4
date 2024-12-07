<?php
// Konfigurasi koneksi ke database
$host = 'localhost'; // Ganti dengan hostname server database Anda
$db_name = 'nama_database'; // Ganti dengan nama database Anda
$db_user = 'root'; // Ganti dengan username database Anda
$db_pass = ''; // Ganti dengan password database Anda

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Periksa koneksi
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua kolom wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        // Periksa apakah email sudah terdaftar
        $stmt = $conn->prepare('SELECT id FROM pengguna WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Email sudah terdaftar!';
        } else {
            // Hash password dan simpan ke database
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare('INSERT INTO pengguna (nama, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $nama, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = 'Pengguna berhasil ditambahkan!';
            } else {
                $error = 'Terjadi kesalahan saat menambahkan pengguna!';
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
</head>

<body>
    <h1>Tambah Pengguna</h1>

    <?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif (!empty($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
    <?php endif;?>

    <form method="POST" action="">
        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama"><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>

        <button type="submit">Tambah Pengguna</button>
    </form>
</body>

</html>