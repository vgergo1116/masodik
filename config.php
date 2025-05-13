<?php
$host = "sqlXXX.epizy.com"; // InfinityFree-n ez a hostnév, pontos címet az adminban látod
$user = "epiz_XXXXXXX"; // saját adatbázis felhasználó
$pass = "jelszavad"; // saját jelszavad
$db = "epiz_XXXXXXX_adatok"; // saját adatbázisnév

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Sikertelen kapcsolódás: " . $conn->connect_error);
}
?>
