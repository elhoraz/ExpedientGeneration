<?php
// Mocking CI4 Environment is hard in CLI. I will just curl the endpoint.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/nexus/calculate");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
echo "Result:\n" . $output;
