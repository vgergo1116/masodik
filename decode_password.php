
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

<?php
$filename = "password.txt";
$key = [5, -14, 31, -9, 3];
$decodedLines = [];

$lines = file($filename, FILE_IGNORE_NEW_LINES);

foreach ($lines as $line) {
    $decrypted = '';
    $k = 0;
    for ($i = 0; $i < strlen($line); $i++) {
        $char = $line[$i];
        $ascii = ord($char);

        // A titkosító algoritmus kihagyja az EOL karaktereket, de a fájlban ezek már nem szerepelnek
        $offset = $key[$k % count($key)];
        $decrypted .= chr($ascii - $offset);
        $k++;
    }
    $decodedLines[] = $decrypted;
}

// Kiírás teszteléshez
echo "<h2>Dekódolt adatok:</h2><pre>";
foreach ($decodedLines as $row) {
    echo htmlspecialchars($row) . "\n";
}
echo "</pre>";
?>
