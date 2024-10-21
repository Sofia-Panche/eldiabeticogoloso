<?php
    $host = 'sql311.infinityfree.com';
    $username = 'if0_37008929';
    $password = 'Diabetic0G';
    $dbname = 'if0_37008929_diabetico_goloso';

    $conexion = mysqli_connect($host, $username, $password);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    echo "Conexión exitosa a MySQL<br>";

    $sql_crear_bd = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (mysqli_query($conexion, $sql_crear_bd)) {
        echo "Base de datos '$dbname' creada o ya existe<br>";
    } else {
        die("Error al crear la base de datos: " . mysqli_error($conexion));
    }

    mysqli_select_db($conexion, $dbname);

    $sql_crear_tabla = "CREATE TABLE IF NOT EXISTS productos (
        id INT(11) NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(255) NOT NULL,
        descripcion TEXT NOT NULL,
        imagen_url VARCHAR(255) NOT NULL,
        precio_5 DECIMAL(10,2) NOT NULL,
        precio_10 DECIMAL(10,2) NOT NULL,
        precio_15 DECIMAL(10,2) NOT NULL,
        precio_20 DECIMAL(10,2) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    if (mysqli_query($conexion, $sql_crear_tabla)) {
        echo "Tabla 'productos' creada o ya existe<br>";
    } else {
        die("Error al crear la tabla: " . mysqli_error($conexion));
    }

    $sql_insertar_datos = "INSERT INTO productos (nombre, descripcion, imagen_url, precio_5, precio_10, precio_15, precio_20) VALUES
    ('Pastel de Maracuya', 'Maracuyá con amapola', 'torta1.jpg', 48.00, 78.00, 110.00, 145.00),
    ('Pastel de Naranja', 'Naranja con amapola', 'torta2.jpg', 48.00, 78.00, 110.00, 145.00),
    ('Pastel de Chocolate', 'Chocolate', 'torta3.jpg', 48.00, 78.00, 110.00, 145.00),
    ('Pastel de Frutos rojos', 'Frutos rojos', 'torta4.jpg', 48.00, 78.00, 110.00, 145.00),
    ('Pastel de Zanahoria con arandanos', 'Zanahoria con arándanos', 'torta5.jpg', 48.00, 78.00, 110.00, 145.00),
    ('Pastel de Maracuya', 'Maracuyá con amapola y relleno de Arequipe ', 'torta6.jpg', 48.00, 78.00, 110.00, 145.00),
    ('Pastel de Chocolate', 'Chocolate y relleno y Jalea frutos amarillos', 'torta7.jpg', 48.00, 78.00, 110.00, 145.00);";

    if (mysqli_query($conexion, $sql_insertar_datos)) {
        echo "Datos de ejemplo insertados en la tabla 'productos'<br>";
    } else {
        die("Error al insertar datos: " . mysqli_error($conexion));
    }

    $sql_select = "SELECT * FROM productos";
    $resultado = mysqli_query($conexion, $sql_select);

    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "ID: " . $fila['id'] . " - Nombre: " . $fila['nombre'] . " - Descripción: " . $fila['descripcion'] . "<br>";
        }
    } else {
        die("Error al realizar la consulta: " . mysqli_error($conexion));
    }

    mysqli_close($conexion);
?>
