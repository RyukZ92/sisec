<?php
require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
$objCrudGenerica = new CrudGenerica();
$keyword = '%'.$_POST['keyword'].'%';

$tabla = 'docente d JOIN datos_personales dp ON dp.dni = d.dni';
$datos = array("dp.dni", "nombre", "apellido");
$condicion = "CAST(dp.dni AS TEXT) LIKE '$keyword'";
$orden = "dp.dni ASC LIMIT 10";
$_dniDocente = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

$tabla = 'secretariado s JOIN datos_personales dp ON dp.dni = s.dni';
$datos = array("dp.dni", "nombre", "apellido");
$condicion = "CAST(dp.dni AS TEXT) LIKE '$keyword'";
$orden = "dp.dni ASC LIMIT 10";
$_dniSecretariado = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

for($i=0; $i<count($_dniDocente);$i++){
    $nombre = explode(" ", $_dniDocente[$i]['nombre']);
    $apellido = explode(" ", $_dniDocente[$i]['apellido']);
    $usuario = $nombre[0] . " " . $apellido[0];   
    $dni = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $_dniDocente[$i]['dni']);
    echo '<a href="javascript:void()" style="text-decoration:none; color:#000;"><li onclick="set_item(\''.str_replace("'", "\'", $_dniDocente[$i]['dni']).'\')">' . $dni . ' ' . $usuario . '</li></a>';
}

for($i=0; $i<count($_dniSecretariado);$i++){
    $nombre = explode(" ", $_dniSecretariado[$i]['nombre']);
    $apellido = explode(" ", $_dniSecretariado[$i]['apellido']);
    $usuario = $nombre[0] . " " . $apellido[0]; 
    $dni = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $_dniSecretariado[$i]['dni']);
    echo '<a href="javascript:void()" style="text-decoration:none; color:#000;"><li onclick="set_item(\''.str_replace("'", "\'", $_dniSecretariado[$i]['dni']).'\')">' . $dni . ' ' . $usuario . '</li></a>';
}
?>