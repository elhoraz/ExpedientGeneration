<?php
require 'vendor/autoload.php';
use Minishlink\WebPush\Vapid;
$keys = Vapid::createVapidKeys();
echo "VAPID_PUBLIC_KEY=" . $keys['publicKey'] . PHP_EOL;
echo "VAPID_PRIVATE_KEY=" . $keys['privateKey'] . PHP_EOL;
