<?php
$db = new mysqli('localhost', 'root', '', 'angkatan');
$db->query("UPDATE users SET email_verify_token = NULL, email_verified_at = NOW() WHERE email = 'muhammad.nurtaufiqi3@gmail.com'");
echo "Updated " . $db->affected_rows . " rows. User verified!";
