<?php
    $host = 'sql311.infinityfree.com';
    $username = 'if0_37008929';
    $password = 'Diabetic0G';
    $dbname = 'if0_37008929_diabetico_goloso';

    $hostPDO = "mysql:host=$host;dbname=$dbname;";
    $tuPDO = new PDO($hostPDO, $username, $password);
    $consulta = $tuPDO->prepare('SELECT * FROM medidas;');

    $consulta->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD Consulta Medidas</title>
    <link rel="website icon" type="png" href="img\logodiabe.png">
    
</head>
<body>
<style>
    body{
       margin:0;
    }
/* Estilos para los encabezados */
h1{
   text-align: center;
   background-color:#71e5f5 ;
   margin: 0;
   font-size: 394%;
}
table {
    border-collapse: collapse;
    width: 100%;
}
table td {
    border: 1px solid #8eefe5;
    text-align: center;
    padding: 1.3rem;
}
.button {
    border-radius: .5rem;
    color: white;
    background-color: #bd9439;
    padding: 1rem;
    text-decoration: none;
}
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
    <h1>BD MEDIDAS</h1>
    <header>
    <div class="main-nav">
        <nav>
               <a href="iniciooficial.php"><i class="fa-solid fa-house"></i></a>       
        </nav>
    </div>
</header>
    <br>
    <p><a class="button" href="crear_medidas.php">Crear</a></p>

    <table>
        <tr>
            <th>CODIGO</th>
            <th>PORCIONES</th>
            <th>INGREDIENTES</th>
            <th>CANTIDADES</th>
            <th colspan="2">ACCIONES</th>
        </tr>
        <?php foreach($consulta as $key => $valor): ?>
            <tr>
                <td><?= $valor['Codigo']; ?></td>
                <td><?= $valor['Porciones']; ?></td>
                <td><?= $valor['Ingredientes']; ?></td>
                <td><?= $valor['Cantidades']; ?></td>
                <td><a class="button" href="actualizar_medidas.php?Codigo=<?= $valor['Codigo'] ?>">Actualizar</a></td>
                <td><a class="button" href="borrar_medidas.php?Codigo=<?= $valor['Codigo'] ?>">Borrar</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
