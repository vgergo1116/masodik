<?php
// Adatbáziskapcsolat (állítsd be a saját hostodat és adataidat)
$servername = "sqlXXX.infinityfree.com"; // pl.: sql312.infinityfree.com
$username = "epiz_XXXXXX";
$password = "jelszavad";
$dbname = "epiz_XXXXXX_adatbazis";

// Dekódoló függvény
function decodeFile($filename) {
    $key = [5, -14, 31, -9, 3];
    $decodedLines = [];

    $content = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($content as $line) {
        $decoded = '';
        for ($i = 0; $i < strlen($line); $i++) {
            $shift = $key[$i % count($key)];
            $decoded .= chr(ord($line[$i]) - $shift);
        }
        $decodedLines[] = $decoded;
    }
    return $decodedLines;
}

// Hibakezelés + irányítás
function redirectWithMessage($msg) {
    echo "<h2>$msg</h2>";
    header("refresh:3;url=login.php");
    exit();
}

// Belépési logika
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_user = $_POST["username"];

    // Dekódoljuk a jelszavakat
    $lines = decodeFile("password.txt");
    $found = false;
    foreach ($lines as $line) {
        list($user, $pass) = explode("*", $line);
        if (trim($user) == $input_user) {
            $found = true;
            $decoded_pass = trim($pass);
            break;
        }
    }

    if (!$found) {
        redirectWithMessage("❌ Nincs ilyen felhasználó.");
    }

    // Adatbázis ellenőrzés
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Adatbázis hiba: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT Titkos FROM adatok WHERE Username = ? AND Titkos = ?");
    $stmt->bind_param("ss", $input_user, $decoded_pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Helyes belépés, háttérszín beállítása
        $row = $result->fetch_assoc();
        $color = htmlspecialchars($row["Titkos"]);
        echo "<body style='background-color:$color;'><h1>Sikeres bejelentkezés! Kedvenc színed: $color</h1></body>";
    } else {
        redirectWithMessage("❌ Hibás jelszó.");
    }

    $stmt->close();
    $conn->close();
} else {
    // Kezdőlap form
    echo <<<HTML
    <h2>Bejelentkezés – Neved: NÉV, Neptun: ABC123</h2>
    <form method="post" action="login.php">
        <label for="username">Felhasználónév (email):</label><br>
        <input type="text" name="username" required><br><br>
        <button type="submit">Belépés</button>
    </form>
HTML;
}
?>
