<meta charset='utf8'/>
<?php
session_start();
require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
$objCrudGenerica = new CrudGenerica();

$id = $_REQUEST['id'];

$tabla = 'municipio';
$datos = null;
$condicion =  "id_municipio = '$id'";
$orden = " municipio ASC";
//$_asginatura = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

$_ciudad = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

$html .= '<option value="0">Seleccione</option>';
if (count($_ciudad) > 0) {            
    for ($i = 0; $i < COUNT($_ciudad); $i++) {
    $html .= '<option value="' . $_ciudad[$i]['id']. '">' . $_ciudad[$i]['municipio'] . '</option>';
        }
} else {
    $html .= '<option value="0">Sin registros</option>';
}
echo $html;
?>