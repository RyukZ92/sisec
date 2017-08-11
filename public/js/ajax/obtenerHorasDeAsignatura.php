<?php
require '../../../db/dbPdo.php';
require '../../../librarys/crudGenerica/crudGenerica.php';
require '../../../librarys/AsistenteBasico/AsistenteBasico.php';
$objCrudGenerica = new CrudGenerica();
$id = $_POST["id"];

$tabla = 'horas_asignatura';
$datos = array("dia", "hora_inicio", "hora_salida", "aula");
$condicion = "id_horario_asignatura = '$id'";
$orden = "dia ASC";
$_horas = $objCrudGenerica->consultar($tabla, $datos, $condicion, $orden);
    $html .= "<table class='pure-table pure-table-bordered'>
                <tr>
                    <thead>
                        <th>Día</th>
                        <th>Inicio</th>
                        <th>Salida</th>
                        <th>Aula</th>
                    </thead>
                </tr>
            ";
for($i=0; $i<count($_horas);$i++){
    $html .= "<tr>
                <tbody>
                    <td>" . AsistenteBasico::obtenerDiaDeLaSemana($_horas[$i]["dia"]) . "</td>
                    <td>" . AsistenteBasico::convertirHoraA12H($_horas[$i]["hora_inicio"]) . "</td>
                    <td>" . AsistenteBasico::convertirHoraA12H($_horas[$i]["hora_salida"]) . "</td>
                    <td>" . $_horas[$i]["aula"] . "</td>
                </tbody>
             </tr>";
}
$html .= "</table>";
echo $html;
?>