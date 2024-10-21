<?php
    $host = 'sql311.infinityfree.com';
    $username = 'if0_37008929';
    $password = 'Diabetic0G';
    $dbname = 'if0_37008929_diabetico_goloso';

    $conexion = new mysqli($host, $username, $password);

    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conexion->query($sql) === TRUE) {
        echo "Base de datos '$dbname' creada o ya existe.<br>";
    } else {
        die("Error al crear la base de datos: " . $conexion->error);
    }

    $conexion->select_db($dbname);

    $sql = "CREATE TABLE IF NOT EXISTS usuarios_db (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        contraseña VARCHAR(255) NOT NULL,
        numero VARCHAR(20) NOT NULL,
        codigo_verificacion VARCHAR(6) NOT NULL
    )";

    if ($conexion->query($sql) === TRUE) {
        echo "Tabla 'usuarios_db' creada o ya existe.";
    } else {
        die("Error al crear la tabla: " . $conexion->error);
    }

    $conexion->close();
?>
