<?php
require 'vendor/autoload.php';

$db = new mysqli('localhost', 'root', '', 'angkatan');
$result = $db->query("SELECT email_verify_token FROM users WHERE email = 'muhammad.nurtaufiqi3@gmail.com'");
if ($row = $result->fetch_assoc()) {
    echo $row['email_verify_token'];
} else {
    echo "No token found.";
}
