<?php

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi
    if (empty($email) || empty($password)) {

        $error = "Email dan password wajib diisi!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid!";

    } else {

        $file = 'users.json';

        // Membaca users.json
        if (file_exists($file)) {

            $json = file_get_contents($file);
            $users = json_decode($json, true);

        } else {

            $users = [];
        }

        if (!is_array($users)) {
            $users = [];
        }

        $foundUser = null;

        // Mencari user berdasarkan email
        foreach ($users as $user) {

            if (
                isset($user['email']) &&
                strtolower($user['email']) === strtolower($email)
            ) {

                $foundUser = $user;
                break;
            }
        }

        // Mengecek password
        if (
            $foundUser !== null &&
            isset($foundUser['password']) &&
            password_verify(
                $password,
                $foundUser['password']
            )
        ) {

            // Membuat session
            $_SESSION['user_id'] = $foundUser['id'];
            $_SESSION['username'] = $foundUser['nama'];
            $_SESSION['email'] = $foundUser['email'];

            // Masuk ke dashboard
            header('Location: dashboard.php');
            exit;

        } else {

            $error = "Email atau password salah!";
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

    <title>Login</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h2>🔐 Login</h2>

        <p class="subtitle">
            Masuk ke akun kamu
        </p>

        <?php if (!empty($error)): ?>

            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Masukkan email"
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
                    placeholder="Masukkan password"
                    required
                >

            </div>

            <button type="submit">
                Login
            </button>

        </form>

        <div class="link">

            Belum punya akun?

            <a href="register.php">
                Registrasi
            </a>

        </div>

    </div>

</div>

</body>

</html>