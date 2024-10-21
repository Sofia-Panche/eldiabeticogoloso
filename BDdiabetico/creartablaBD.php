<?php
incluide('BD_diabetico/crearBD.php');
include_once('BD_diabetico/crearBD.php');
    try{
        $con= new PDO("mysql:host=$host;dbname=$BD",$username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="CREATE TABLE tortas(Codigo INT(5)PRIMARY KEY, 
                                        Porciones VARCHAR(5) NOT NULL, 
                                        Sabores_tortas VARCHAR(100) NOT NULL, 
                                        Sabores_relleno VARCHAR(100) NOT NULL,
                                        Sabores_crema VARCHAR(100) NOT NULL,
                                        Precios VARCHAR(100) NOT NULL
                                        )";
        $con->exec($sql);
        echo" TABLA CREADA";
    }catch(PDOException $e){
        echo $sql. "<br>". $e->getMessage();
    }
    $con= null;
?>

<?php
//BD ingredientes
incluide('BD_diabetico/crearBD.php');
include_once('BD_diabetico/crearBD.php');
    
    try{
        $con= new PDO("mysql:host=$host;dbname=$BD",$username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="CREATE TABLE ingredientes(Codigo INT(5) PRIMARY KEY, 
                                        Ingredientes VARCHAR(100) NOT NULL, 
                                        Medidas VARCHAR(100) NOT NULL, 
                                        Precios VARCHAR(100) NOT NULL
                                        )";
        $con->exec($sql);
        echo" TABLA CREADA";
    }catch(PDOException $e){
        echo $sql. "<br>". $e->getMessage();
    }
    $con= null;
?>

<?php
//BD medidas
incluide('BD_diabetico/crearBD.php');
include_once('BD_diabetico/crearBD.php');
    
    try{
        $con= new PDO("mysql:host=$host;dbname=$BD",$username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="CREATE TABLE medidas(Codigo INT(5) PRIMARY KEY, 
                                        Porciones VARCHAR(5) NOT NULL, 
                                        Ingredientes VARCHAR(100) NOT NULL, 
                                        Cantidades VARCHAR(100) NOT NULL
                                        )";
        $con->exec($sql);
        echo" TABLA CREADA";
    }catch(PDOException $e){
        echo $sql. "<br>". $e->getMessage();
    }
    $con= null;
?>


<?php
//BD administradores
incluide('BD_diabetico/crearBD.php');
include_once('BD_diabetico/crearBD.php');

    try{
        $con= new PDO("mysql:host=$host;dbname=$BD",$username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="CREATE TABLE `administradores` (
        `nombre completo` varchar(50) NOT NULL,
        `dni` varchar(12) PRIMARY KEY,
        `cargo` varchar(50) NOT NULL,
        `sede` varchar(50) NOT NULL
        )";

        
    }