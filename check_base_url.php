<?php
// Load CodeIgniter framework environment
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require FCPATH . 'system/bootstrap.php';

echo "Base URL: " . base_url('/login') . "\n";
