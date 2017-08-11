<meta charset='utf8'/>
<?php
require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
$objCrudGenerica = new CrudGenerica();

$tabla = "periodo_academico";
$datos = array("periodo_academico", "fecha_desde", "fecha_hasta", "estatus");
$condicion = "estatus = '1'";
$orden = null;
$_periodoAcademico = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);
$periodoAcademico = $_periodoAcademico[0]["periodo_academico"];

$id = $_REQUEST['id'];

$tabla = 'seccion';
$datos = array("codigo", "cupos");
$orden = "codigo ASC";
$condicion = "id_semestre = '$id'";
$_seccion = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

if (COUNT($_seccion) > 0) {            
    for ($i = 0; $i < COUNT($_seccion); $i++) {
        $totalInscritos = $objCrudGenerica->contarFilas("inscripcion", "periodo_academico = '$periodoAcademico' AND codigo_seccion = '" . $_seccion[$i]['codigo'] . "'");
        $disabled = ($totalInscritos == $_seccion[$i]['cupos']) ? "disabled" : "";
        $html .= '<option '. $disabled .' value="' . $_seccion[$i]['codigo'] . '">' . $_seccion[$i]['codigo'] . ' - Cupos: ' . $totalInscritos . '/' . $_seccion[$i]['cupos'] .'</option>';
        
    }
} else {
    $html .= '<option value="0">Sin registros</option>';
}
echo $html;
/*
SELECT codigo, cupos, (SELECT COUNT(*) FROM inscripcion WHERE periodo_academico = '2015-I') as inscritos
FROM seccion
WHERE id_semestre = '1'
AND codigo_seccion = seccion.codigo;

SELECT codigo, cupos
FROM seccion

 */
?>