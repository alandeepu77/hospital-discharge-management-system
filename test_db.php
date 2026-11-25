<?php

echo "PHP Version: " . PHP_VERSION . "<br>";

echo "PHP File: " . PHP_BINARY . "<br>";

echo "Loaded php.ini: " . php_ini_loaded_file() . "<br>";

echo "PDO Drivers: ";

print_r(PDO::getAvailableDrivers());

?>