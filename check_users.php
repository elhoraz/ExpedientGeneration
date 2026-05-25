<?php
$conn = new mysqli('localhost', 'root', '', 'angkatan');
$desc = $conn->query("DESCRIBE users");
while ($row = $desc->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
