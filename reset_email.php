<?php
require 'vendor/autoload.php';

$db = new mysqli('localhost', 'root', '', 'angkatan');
$db->query("UPDATE email_queue SET status = 'pending' WHERE status = 'failed'");
echo "Updated " . $db->affected_rows . " rows.";
