<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php
// Kapcsolódás az adatbázishoz
$conn = new mysqli("localhost", "root", "", "adatok");
if ($conn->connect_error) {
    die("Sikertelen kapcsolódás: " . $conn->connect_error);
}

// Beolvasás POST-ból
$username = $_POST['username'];
$password = $_POST['password'];

// Titkosított fájl beolvasása
$lines = file('password.txt', FILE_IGNORE_NEW_LINES);
$decoded = [];
$key = [5, -14, 31, -9, 3];

// Soronként dekódolás
foreach ($lines as $line) {
    $decrypted = "";
    for ($i = 0, $k = 0; $i < strlen($line); $i++) {
        $c = $line[$i];
        $ascii = ord($c);
        if ($ascii == 10) { // EOL - nem titkosított
            $decrypted .= $c;
        } else {
            $offset = $key[$k % count($key)];
            $decrypted .= chr($ascii - $offset);
            $k++;
        }
    }
    // Szétválasztjuk a felhasználónevet és jelszót
    list($user, $pass) = explode("*", $decrypted);
    $decoded[$user] = $pass;
}

// Ellenőrzés
if (!isset($decoded[$username])) {
    echo "Nincs ilyen felhasználó!";
    exit;
}

if ($decoded[$username] !== $password) {
    echo "Hibás jelszó! Átirányítás...";
    header("refresh:3;url=https://www.police.hu");
    exit;
}

// Sikeres bejelentkezés, lekérdezzük a színt
$stmt = $conn->prepare("SELECT Titkos FROM tabla WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($szin);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Üdvözlet</title>
    <style>
        body {
            background-color: <?= htmlspecialchars($szin) ?>;
            color: white;
            text-align: center;
            font-size: 2em;
            padding-top: 100px;
        }
    </style>
</head>
<body>
    <p>Sikeres bejelentkezés, <?= htmlspecialchars($username) ?>!</p>
    <p>A kedvenc színed: <?= htmlspecialchars($szin) ?></p>
</body>
</html>

