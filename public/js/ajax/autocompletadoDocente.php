<?php
require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
$objCrudGenerica = new CrudGenerica();
$keyword = '%'.$_POST['keyword'].'%';
$tabla = 'docente d JOIN datos_personales dp ON dp.dni = d.dni';
$datos = array("dp.dni", "nombre", "apellido");
$condicion = "CAST(dp.dni AS TEXT) LIKE '$keyword' AND eliminado != '1'";
$orden = "dp.dni ASC LIMIT 10";
$_dni = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);
for($i=0; $i<count($_dni);$i++){
    $nombre = explode(" ", $_dni[$i]['nombre']);
    $apellido = explode(" ", $_dni[$i]['apellido']);
    $docente = $nombre[0] . " " . $apellido[0];
    $dni = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $_dni[$i]['dni']);
    echo '<a href="javascript:void()" style="text-decoration:none; color:#000;"><li onclick="set_item(\''.str_replace("'", "\'", $_dni[$i]['dni']).'\')">' . $dni . ' ' . $docente . '</li></a>';
}
?>