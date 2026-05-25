<?php
$conn = new mysqli('localhost', 'root', '', 'angkatan');
$res = $conn->query("SHOW TABLES LIKE 'syndicate'");
if ($res->num_rows > 0) {
    echo "Syndicate table exists.\n";
    $desc = $conn->query("DESCRIBE syndicate");
    while ($row = $desc->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Syndicate table DOES NOT EXIST.\n";
}
