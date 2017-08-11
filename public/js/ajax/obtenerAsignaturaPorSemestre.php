<meta charset='utf8'/>
<?php
session_start();
require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
$objCrudGenerica = new CrudGenerica();

$id = $_REQUEST['id'];

$tabla = 'asignatura';
$datos = array('codigo', 'nombre');
$condicion =  "id_semestre = '$id'";
$orden = " nombre ASC";
//$_asginatura = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

$_asignatura = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

//echo count($_SESSION["id_horario"] . " = id_horario";
if (count($_SESSION["id_horario"]) > 0) {
    
    for ($i=0 ;$i<count($_SESSION["id_horario"]); $i++) {
        if ($_SESSION["id_horario"][$i] != null){
            //print_r($_asignatura);
            $tab = 'horario_asignatura h JOIN horas_asignatura ha ON (h.id = ha.id_horario_asignatura)';
            $dat = array("h.codigo_asignatura AS asignatura");
            $cond = "id_horario_asignatura = '" . $_SESSION["id_horario"][$i] . "'";     
            $_horario = $objCrudGenerica->consultar($tab, $dat, $cond, null);
            for ($k=0; $k<count($_horario); $k++) {
                for ($j=0; $j<count($_asignatura); $j++) {
                    if($_horario[$k]["asignatura"] == $_asignatura[$j]["codigo"])  {
                        $condicion .= " AND codigo != '" . $_horario[$k]["asignatura"] . "'";
                    }
                }
            }
        }        
    }
}
$_recordAcademico = $objCrudGenerica->consultar("record_academico", null, "dni_estudiante = '" . $_SESSION["dni"] . "' AND nota > 9", null);
for ($i=0 ;$i<count($_recordAcademico); $i++) {
    for ($j=0; $j<count($_asignatura); $j++) {
        if($_recordAcademico[$i]["codigo_asignatura"] == $_asignatura[$j]["codigo"])  {
            $condicion .= " AND codigo != '" . $_asignatura[$j]["codigo"] . "'";
        }
    }
}

$orden = " nombre ASC";
$_asignatura = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

if (count($_asignatura) > 0) {  
    $html .= '<option value="0">Seleccione</option>';
    for ($i = 0; $i < COUNT($_asignatura); $i++) {
    $html .= '<option value="' . $_asignatura[$i]['codigo']. '">' . $_asignatura[$i]['nombre'] . '</option>';
        }
} else {
    $html .= '<option value="0">Sin registros</option>';
}
echo $html;
?>