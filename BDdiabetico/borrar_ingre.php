<?php 
    $host ='sql311.infinityfree.com';
    $username ='if0_37008929';
    $password='Diabetic0G';
    $dbname='if0_37008929_diabetico_goloso';

    $hostPDO="mysql:host=$host;dbname=$dbname;";
    $tuPDO=new PDO($hostPDO,$username,$password);

    $codigo=isset($_REQUEST['Codigo'])? $_REQUEST['Codigo']: null;

    $eliminar = $tuPDO->prepare('DELETE FROM ingredientes WHERE Codigo = :Codigo');

    $eliminar->execute([
        'Codigo'=>$codigo
    ]);

    header('Location:consulBDingre.php');
?>
