<?php
$db = new PDO('mysql:host=localhost;dbname=angkatan', 'root', '');
$stmt = $db->query('SELECT COUNT(*) FROM celestial_cards');
echo 'COUNT: ' . $stmt->fetchColumn() . PHP_EOL;

$stmt2 = $db->query('SELECT * FROM celestial_cards LIMIT 1');
print_r($stmt2->fetch(PDO::FETCH_ASSOC));
