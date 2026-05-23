<?php
require 'vendor/autoload.php';
$db = new mysqli('localhost', 'root', '', 'angkatan');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$result = $db->query("SELECT id, email, role, is_active, email_verified_at, password_hash FROM users WHERE email='admin@expedient.com'");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "User not found";
}
$db->close();
