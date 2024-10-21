<?php

$host = 'sql311.infinityfree.com';
$username = 'if0_37008929';
$password = 'Diabetic0G';
$dbname = 'if0_37008929_diabetico_goloso';

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$con = null;

?>