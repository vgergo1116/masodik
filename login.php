<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
    <style>
        body {
            background-color: #eef2f7;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h2 {
            margin-bottom: 10px;
        }

        .info {
            font-size: 14px;
            margin-bottom: 20px;
            color: #555;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input[type="submit"] {
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            font-weight: bold;
            margin-bottom: 15px;
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Bejelentkezés</h2>
    <div class="info">Neved: <strong>Veréb Gergő</strong>, Neptun: <strong>GQY892</strong></div>

    <?php
    $message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"] ?? "";
        $inputPassword = $_POST["password"] ?? "";

        $key = [5, -14, 31, -9, 3];
        $passwords = [];

        $lines = file("password.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $decoded = "";
            for ($i = 0; $i < strlen($line); $i++) {
                $decoded .= chr(ord($line[$i]) - $key[$i % 5]);
            }

            $parts = explode('*', $decoded);
            if (count($parts) === 2) {
                $email = trim($parts[0]);
                $password = trim($parts[1]);
                $passwords[$email] = $password;
            }
        }

        if (!array_key_exists($username, $passwords)) {
            $message = "❌ Hibás felhasználónév!";
        } elseif ($passwords[$username] !== $inputPassword) {
            $message = "❌ Hibás jelszó!";
            echo "<script>setTimeout(function() { window.location.href = 'https://police.hu'; }, 3000);</script>";
        } else {
            $color = htmlspecialchars($inputPassword);
            echo "<div class='message success'>✅ Sikeres bejelentkezés! A kedvenc színed: <b>$color</b></div>";
            exit;
        }
    }
    ?>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="username">Felhasználónév (email):</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Jelszó:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Belépés">
    </form>
</div>
</body>
</html>



