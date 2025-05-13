<?php
include("config.php");

$username = $_POST['username'];
$input_password = $_POST['password'];

// 1. Titkosított fájl beolvasása
$lines = file("password.txt"); // Feltételezzük, hogy a fájl feltöltve van
$key = [5, -14, 31, -9, 3];
$decoded = [];

// 2. Minden sor dekódolása
foreach ($lines as $line) {
    $line = rtrim($line, "\n"); // EOL levágás
    $decoded_line = '';
    $k = 0;

    for ($i = 0; $i < strlen($line); $i++) {
        $char_code = ord($line[$i]);
        $decoded_char = chr($char_code - $key[$k]);
        $decoded_line .= $decoded_char;
        $k = ($k + 1) % count($key);
    }

    // Felhasználónév és jelszó szétválasztása
    if (strpos($decoded_line, '*') !== false) {
        list($user, $pass) = explode('*', $decoded_line);
        $decoded[$user] = $pass;
    }
}

// 3. Felhasználónév ellenőrzése
if (!isset($decoded[$username])) {
    echo "Nincs ilyen felhasználó!";
    exit;
}

// 4. Jelszó ellenőrzése
if ($decoded[$username] !== $input_password) {
    echo "Hibás jelszó! Átirányítás 3 másodperc múlva...";
    header("refresh:3;url=https://police.hu");
    exit;
}

// 5. Lekérdezés az adatbázisból – kedvenc szín
include("config.php");
$stmt = $conn->prepare("SELECT Titkos FROM tabla WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($color);

if ($stmt->fetch()) {
    echo "<body style='background-color:$color;'>";
    echo "<h1>Sikeres bejelentkezés!</h1>";
    echo "<p>Kedvenc színed: <strong>$color</strong></p>";
    echo "</body>";
} else {
    echo "Nem található szín az adatbázisban.";
}
$stmt->close();
$conn->close();
?>


