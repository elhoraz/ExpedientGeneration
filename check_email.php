<?php
require 'vendor/autoload.php';

$db = new mysqli('localhost', 'root', '', 'angkatan');
$result = $db->query("SELECT error_message FROM email_queue WHERE status = 'failed' ORDER BY id DESC LIMIT 1");
if ($row = $result->fetch_assoc()) {
    echo $row['error_message'];
} else {
    echo "No errors found.";
}
