<?php
echo "PHP Timezone: " . date_default_timezone_get() . "\n";
echo "Config Timezone: " . config('App')->appTimezone . "\n";
echo "Date(): " . date('Y-m-d H:i:s') . "\n";
