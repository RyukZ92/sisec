<meta charset='utf8'/>
<?php
//$periodoAcademico = '2015-I';

require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
require '../../../librarys/AsistenteBasico/AsistenteBasico.php';
$objCrudGenerica = new CrudGenerica();
$html .="<table style='margin:0 auto;' cellspacing='0'; class='pure-table-horizontal'>";
//$html .="<thead>";
    $html .="<tr>";
        $html .="<th><label>#</label></th>";
        $html .="<th><label>Día</label></th>";
        $html .="<th><label>Inicio</label></th>";
        $html .="<th><label>Salida</label></th>";
        //$html .="<th><label>Sección</label></th>";
        $html .="<th><label>Aula</label></th>";
        $html .="<th><label>Cupos</label></th>";
    $html .="</tr>";
//$html .="</thead>"; 
$html .="<tbody>";
$codigo = $_REQUEST['id'];
$rowspan = null;
$tabla = 'horario_asignatura';
$datos = array("id", "cupos", "codigo_seccion", "codigo_asignatura");
$orden = null;
$_periodo = $objCrudGenerica->consultar("periodo_academico", array("periodo_academico"), "estatus ='1'", null);
$condicion = "codigo_asignatura = '$codigo' ANd periodo_academico ='" . $_periodo[0]["periodo_academico"] . "'";
$_asignatura = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);
for($i=0; $i<count($_asignatura); $i++) {
    
    $tabla = 'horario_asignatura h JOIN horas_asignatura a ON (h.id = a.id_horario_asignatura)';
    $datos = array("dia", "hora_inicio", "hora_salida", "aula", "id_horario_asignatura");
    $orden = null;
    $condicion = "id_horario_asignatura = '" . $_asignatura[$i]["id"] . "'";
    $_horario = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);

    $rowspan = true;
    for($j=0;$j<count($_horario);$j++) {
        $id = $_asignatura[$i]['id'];
        $codigo_seccion = $_asignatura[$i]['codigo_seccion'];
        $codigo_asignatura = $_asignatura[$i]['codigo_asignatura'];
        $hora_inicio = AsistenteBasico::convertirHoraA12H($_horario[$j]['hora_inicio']);
        $hora_salida = AsistenteBasico::convertirHoraA12H($_horario[$j]['hora_salida']);
        $aula = $_horario[$j]['aula'];
        $dia = AsistenteBasico::obtenerDiaDeLaSemana($_horario[$j]['dia']);
        $cupos = $_asignatura[$i]['cupos'];
        
        $tabla  = "horas_asignatura haa JOIN horario_asignatura ha ON haa.id_horario_asignatura = ha.id"
                . " JOIN inscripcion_asignatura ia ON ha.id = ia.id_horario_asignatura"
                . " JOIN inscripcion i ON ia.id_inscripcion = i.id";
        $condicion = "haa.id_horario_asignatura = '$id'";
        $totalInscritos = $objCrudGenerica->contarFilas($tabla, $condicion);
        $disabled = ($totalInscritos >= $cupos) ? "disabled" : "";
        $html .= "<input type='hidden' value='$codigo_seccion' name='codigo_seccion'>";
        $html .= "<input type='hidden' value='$codigo_asignatura' name='codigo_asignatura'>";
       
        $html .= "<tr style='color:$color;'>";
            $p=$i+1;
            if ($rowspan != null) {
                $html .= "<td rowspan = '" . count($_horario) . "'><input type='radio' $disabled name='id_horario' value='" . $_asignatura[$i]["id"] . "' required></td>";                
            }      
                            
            $html .= "<td>$dia</td>";
            $html .= "<td>$hora_inicio</td>";
            $html .= "<td>$hora_salida</td>";
            $html .= "<td>$aula</td>";
            if ($rowspan != null) {
                $html .= "<td rowspan = '" . count($_horario) . "'>$totalInscritos/$cupos</td>";
            }
            $rowspan = null;
       $html .="</tr>";           
    }
    $html .="</tbody>";    
}
$html .="</table>";
echo $html;
?>