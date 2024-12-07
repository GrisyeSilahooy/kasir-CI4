<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengguna</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 20px;
    }

    .form-container {
        max-width: 400px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .form-container label {
        font-weight: bold;
    }

    .form-container input[type="text"],
    .form-container input[type="email"],
    .form-container input[type="password"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-container button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 4px;
    }

    .form-container button:hover {
        background-color: #45a049;
    }

    .message {
        text-align: center;
        font-size: 14px;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Form Pengguna</h2>
        <form method="POST" action="tambah_pengguna.php">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>

            <button type="submit">Simpan Pengguna</button>
        </form>
    </div>

    <div class="message">
        <p>Isi data dengan lengkap dan tekan tombol "Simpan Pengguna" untuk menambahkan pengguna baru.</p>
    </div>
</body>

</html>
