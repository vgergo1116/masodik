

<?php
$host = "sqlXXX.epizy.com";   // A pontos SQL host (pl. sql212.epizy.com)
$user = "epiz_12345678";      // Saját InfinityFree felhasználónév
$pass = "sajatjelszo";        // Az adatbázishoz tartozó jelszó
$db = "epiz_12345678_adatok"; // Az adatbázis neve

$connect = new mysqli($host, $user, $pass, $db);

if ($connect->connect_error) {
    die("Sikertelen kapcsolódás: " . $connect->connect_error);
}
?>
