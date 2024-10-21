<?php
        $host ='sql311.infinityfree.com';
        $username ='if0_37008929';
        $password='Diabetic0G';
        $dbname='if0_37008929_diabetico_goloso';

        
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $Codigo=isset($_REQUEST['Codigo']) ? $_REQUEST['Codigo']: null;
        $Ingredientes=isset($_REQUEST['Ingredientes']) ? $_REQUEST['Ingredientes']: null;
        $Medidas=isset($_REQUEST['Medidas']) ? $_REQUEST['Medidas']: null;
        $Precios=isset($_REQUEST['Precios']) ? $_REQUEST['Precios']: null;

        $hostPDO="mysql:host=$host;dbname=$dbname;";
        $tuPDO=new PDO($hostPDO,$username,$password);

        $insertar=$tuPDO->prepare('INSERT INTO ingredientes(Codigo,Ingredientes, Medidas, Precios)
        VALUES(:Codigo,:Ingredientes,:Medidas,:Precios)');
        
        $insertar->execute(array(
                            'Codigo'=>$Codigo,
                            'Ingredientes'=>$Ingredientes,
                            'Medidas'=>$Medidas,
                            'Precios'=>$Precios
                            )
        );

        header('Location:consulBDingre.php');

    }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="website icon" type="png" href="img\logodiabe.png">
    <title>Crear</title>
</head>
<body><style>
header .main-nav {
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header .main-nav .logo {
    height: 300px; 
    width: 300px;
}

header .main-nav nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}

header .main-nav nav ul li {
    margin: 0 1rem;
}

header .main-nav nav ul li a {
    color: #000;
    text-decoration: none;
    font-size: 1rem;
}

header .main-nav nav ul li.icons a {
    margin: 0 0.5rem;
}

header .main-nav nav ul li.icons img {
    width: 20px;
    height: 20px;
}

.main-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.icons-container {
    display: flex;
    gap: 10px;
}

.icons-container a {
    color: #000;
    font-size: 20px;
}
</style>
<h1>Crear</h1>
<header>
    <div class="main-nav">
        <nav>
               <a href="consulBDingre.php"><i class="fa-solid fa-house"></i></a>       
        </nav>
    </div>
</header>
    <form action = " " method="post">
            <label for ="Codigo">Codigo<label>
            <input id="Codigo" type="text" name="Codigo">
            <label for ="Ingredientes">Ingredientes</label>
            <input id="Ingredientes" type="text" name="Ingredientes">
            <label for ="Medidas">Medidas<label>
            <input id="Medidas" type="text" name="Medidas">
            <label for ="Precios">Precios<label>
            <input id="Precios" type="text" name="Precios">
            <input type="submit" value="Guardar">
    </form>
</body>
</html>