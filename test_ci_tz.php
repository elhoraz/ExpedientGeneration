<?php
define('FCPATH', __DIR__ . '/public/');
$_SERVER['DOCUMENT_ROOT'] = FCPATH;
$_SERVER['SCRIPT_NAME'] = '/index.php';
require 'public/index.php';
