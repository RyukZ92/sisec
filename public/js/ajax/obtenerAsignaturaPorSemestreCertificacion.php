<meta charset='utf8'/>
       <link rel="stylesheet" href="../../../public/bootstrap-3.3.4-dist/css/bootstrap.css">
        <link rel="stylesheet" href="../../../public/bootstrap-3.3.4-dist/css/bootstrap-theme.min.css">  
        <link rel="stylesheet" href="../../../public/pure-release-0.6.0/pure.css">
        <script src="../../../public/js/jquery-min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../../public/css/jquery-ui.css">
        <script src="../../../public/js/jquery-ui.min.js"></script>
        <script src="../../../public/js/jquery.ui.datepicker-es.js.js"></script>
        <script src="../../../public/js/scripts"></script>
        <script>

        });
        
        </script>

		
		<?php
require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
$objCrudGenerica = new CrudGenerica();

$id = $_REQUEST['id'];

$tabla = 'asignatura';
$datos = array('codigo', 'nombre');
$condicion =  "id_semestre = '$id'";
$orden = "nombre ASC";
$_asignatura = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);
         
$html .= '<br><table class="pure-table pure-table-horizontal">   
            <thead>
                <tr>
                    <th>Selección</th>
                    <th>Asignatura</th>
                    <th>Notas</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>';
if (count($_asignatura) > 0) {           
    for ($i = 0; $i < COUNT($_asignatura); $i++) {
        $nota = "nota_" . $_asignatura[$i]['codigo'];
        $fecha = "fecha_" . $_asignatura[$i]['codigo'];
        $checkbox = "checkbox_" . $_asignatura[$i]['codigo'];
        $nombre_asignatura = "nombre_asignatura_" . $_asignatura[$i]['codigo'];
    $html .= "<tr>"
                . "<td align='center'><input onClick='return desactivarNotaFecha(this);' type='checkbox' name='$checkbox' value='" . $_asignatura[$i]['codigo'] . "' ></td>"
                . "<td>" . $_asignatura[$i]['nombre'] . "<input type='hidden' name='$nombre_asignatura' value='" . $_asignatura[$i]['nombre'] . "'></td>"
                . "<td><input class='solo-numero-entero' type='text' name='$nota' id='$nota'  placeholder='Nota'  style='width:55px;' maxlength='2' disabled required></td>"
                . "<td><input class='fecha' type='text' placeholder='dd/mm/aaaa' name='$fecha'  id='$fecha' maxlength='10' style='width:110px;' disabled required>"
                . "</td>"
                . "</tr>";
        }
} else {
    $html .= '<td value="0">Sin registros</td>';
}

$html .= '<tr align="center">                       
                <td  colspan="4" align="center">
                    <input type="submit" class="btn btn-success" name="cargar_notas" value="Cargar Notas">
                </td>
            </tr>
          </tbody>
        </table> 
        <input type="hidden" name="semestre" value="' . $id . '">';
echo $html;

?>
