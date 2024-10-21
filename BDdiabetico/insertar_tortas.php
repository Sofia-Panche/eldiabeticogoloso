<?php
    $host = 'sql311.infinityfree.com';
    $username = 'if0_37008929';
    $password = 'Diabetic0G';
    $dbname = 'if0_37008929_diabetico_goloso';

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conexion->beginTransaction();

        $conexion->exec("INSERT INTO medidas (Codigo, Porciones, Ingredientes, Cantidades)
            VALUES ('1', '5', 'Harina\nHuevos A\nAceite\nPolvo de hornear\nAgua o jugo', '250gr\n75gr\n75gr\n4gr\n62.5gr')");
        $conexion->exec("INSERT INTO medidas (Codigo, Porciones, Ingredientes, Cantidades)
            VALUES ('2', '10', 'Harina\nHuevos A\nAceite\nPolvo de hornear\nAgua o jugo', '500gr\n150gr\n150gr\n4gr\n125gr')");
        $conexion->exec("INSERT INTO medidas (Codigo, Porciones, Ingredientes, Cantidades)
            VALUES ('3', '15', 'Harina\nHuevos A\nAceite\nPolvo de hornear\nAgua o jugo', '750gr\n225gr\n225gr\n4gr\n187.5gr')");
        $conexion->exec("INSERT INTO medidas (Codigo, Porciones, Ingredientes, Cantidades)
            VALUES ('4', '20', 'Harina\nHuevos A\nAceite\nPolvo de hornear\nAgua o jugo', '1000gr\n300gr\n300gr\n4gr\n250gr')");

        $conexion->commit();
        echo "Registros insertados";
    } catch (PDOException $e) {
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
    $conexion = null;
?>
