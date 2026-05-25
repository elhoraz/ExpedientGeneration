<?php
$conn = new mysqli('localhost', 'root', '', 'angkatan');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT source FROM divine_verses");
$sources = [];
while ($row = $result->fetch_assoc()) {
    $sources[] = $row['source'];
}
echo json_encode($sources);
