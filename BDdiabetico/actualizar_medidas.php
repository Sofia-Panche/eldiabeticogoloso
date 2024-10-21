<?php
        $host ='sql311.infinityfree.com';
        $username ='if0_37008929';
        $password='Diabetic0G';
        $dbname='if0_37008929_diabetico_goloso';

$Codigo = isset($_REQUEST['Codigo']) ? $_REQUEST['Codigo'] : null;
$Porciones = isset($_REQUEST['Porciones']) ? $_REQUEST['Porciones'] : null;
$Ingredientes = isset($_REQUEST['Ingredientes']) ? $_REQUEST['Ingredientes'] : null;
$Cantidades = isset($_REQUEST['Cantidades']) ? $_REQUEST['Cantidades'] : null;

try {
    $hostPDO = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $tuPDO = new PDO($hostPDO, $username, $password);
    $tuPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $actualizar = $tuPDO->prepare('UPDATE medidas SET Porciones = :Porciones, Ingredientes = :Ingredientes, Cantidades = :Cantidades WHERE Codigo = :Codigo');
        $actualizar->execute([
            'Codigo' => $Codigo,
            'Porciones' => $Porciones,
            'Ingredientes' => $Ingredientes,
            'Cantidades' => $Cantidades
        ]);
        header('Location: consulBDmedidas.php');
        exit();
    } else {
        $consulta = $tuPDO->prepare('SELECT * FROM medidas WHERE Codigo = :Codigo');
        $consulta->execute(['Codigo' => $Codigo]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="website icon" type="png" href="img\logodiabe.png">
    <title>Actualizar Medidas</title>
</head>
<body>
    <h1>Actualizar Medidas</h1>
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
    <header>
    <div class="main-nav">
        <nav>
               <a href="iniciooficial.php"><i class="fa-solid fa-house"></i></a>       
        </nav>
    </div>
</header>
    <form method="post">
        <label for="Codigo">Codigo</label>
        <input id="Codigo" type="text" name="Codigo" value="<?= htmlspecialchars($resultado['Codigo']) ?>" readonly>
        <label for="Porciones">Porciones</label>
        <input id="Porciones" type="text" name="Porciones" value="<?= htmlspecialchars($resultado['Porciones']) ?>">
        <label for="Ingredientes">Ingredientes</label>
        <input id="Ingredientes" type="text" name="Ingredientes" value="<?= htmlspecialchars($resultado['Ingredientes']) ?>">
        <label for="Cantidades">Cantidades</label>
        <input id="Cantidades" type="text" name="Cantidades" value="<?= htmlspecialchars($resultado['Cantidades']) ?>">
        <input type="submit" value="Guardar">
    </form>
</body>
</html>