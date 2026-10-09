<?php

session_start();

// Kalau belum login, kembali ke login
if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');
    exit;
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

    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h1>🏠 Dashboard</h1>

        <p class="subtitle">
            Selamat datang di sistem login
        </p>

        <div class="profile">

            <div class="profile-item">

                <strong>Nama:</strong>

                <?= htmlspecialchars($_SESSION['username']) ?>

            </div>

            <div class="profile-item">

                <strong>Email:</strong>

                <?= htmlspecialchars($_SESSION['email']) ?>

            </div>

            <div class="profile-item">

                <strong>User ID:</strong>

                <?= htmlspecialchars($_SESSION['user_id']) ?>

            </div>

        </div>

        <a
            href="logout.php"
            class="logout"
        >
            🚪 Logout
        </a>

    </div>

</div>

</body>

</html>