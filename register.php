<?php

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil data dari form
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi
    if (empty($nama) || empty($email) || empty($password)) {

        $error = "Semua field wajib diisi!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid!";

    } elseif (strlen($password) < 6) {

        $error = "Password minimal 6 karakter!";

    } else {

        $file = 'users.json';

        // Membaca users.json
        if (file_exists($file)) {

            $json = file_get_contents($file);
            $users = json_decode($json, true);

            if (!is_array($users)) {
                $users = [];
            }

        } else {

            $users = [];
        }

        // Mengecek email apakah sudah terdaftar
        foreach ($users as $user) {

            if (
                isset($user['email']) &&
                strtolower($user['email']) === strtolower($email)
            ) {

                $error = "Email sudah terdaftar!";
                break;
            }
        }

        // Kalau tidak ada error, simpan user baru
        if (empty($error)) {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $newUser = [
                'id' => count($users) + 1,
                'nama' => $nama,
                'email' => $email,
                'password' => $hashedPassword,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $users[] = $newUser;

            file_put_contents(
                $file,
                json_encode(
                    $users,
                    JSON_PRETTY_PRINT
                )
            );

            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Registrasi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h2>📝 Registrasi</h2>

        <p class="subtitle">
            Buat akun baru
        </p>

        <?php if (!empty($error)): ?>

            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <?php if (!empty($success)): ?>

            <div class="message success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label for="nama">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    required
                >

            </div>

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="contoh@email.com"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 6 karakter"
                    required
                >

            </div>

            <button type="submit">
                Daftar
            </button>

        </form>

        <div class="link">

            Sudah punya akun?

            <a href="login.php">
                Login
            </a>

        </div>

    </div>

</div>

</body>

</html>