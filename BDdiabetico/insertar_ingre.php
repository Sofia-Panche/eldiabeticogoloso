<?php
    $host = 'sql311.infinityfree.com';
    $username = 'if0_37008929';
    $password = 'Diabetic0G';
    $dbname = 'if0_37008929_diabetico_goloso';

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conexion->beginTransaction();

        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('1', 'Harina(Santillana)', '4.5Kg', '160.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('2', 'Aceite', '3L', '28.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('3', 'Huevos A', 'Cubeta completa', '14.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('4', 'Polvo de hornear', '20g', '2.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('5', 'Agua o jugo', '', '')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('6', 'Esencias de sabores (grande)', '510ml', '10.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('7', 'Esencias de sabores (pequeña)', '130ml', '6.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('8', 'Colorantes (liquidos)', '7ml', '3.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('9', 'Colorantes (gel)', '60ml', '16.500')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('10', 'Jalea frutos rojos', '220g', '5.000')");
        $conexion->exec("INSERT INTO ingredientes (Codigo, Ingredientes, Medidas, Precios)
            VALUES ('11', 'Jalea frutos amarillos', '220g', '5.000')");

        $conexion->commit();
        echo "Registros insertados";
    } catch (PDOException $e) {
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
    $conexion = null;
?>
