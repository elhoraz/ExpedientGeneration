<?php
echo "Current File Dir: " . __DIR__ . "<br>";
echo "PHP Version: " . phpversion() . "<br>";

$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    echo ".env exists!<br>";
    $lines = file($envFile);
    foreach ($lines as $line) {
        if (strpos($line, 'app.baseURL') !== false) {
            echo "Line in .env: " . htmlspecialchars($line) . "<br>";
        }
    }
} else {
    echo ".env DOES NOT EXIST!<br>";
}
