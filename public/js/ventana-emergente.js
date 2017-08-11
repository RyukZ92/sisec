/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){ // ejecuta el script jquery cuando el documento ha terminado de cargarse -->
	$(".boton").click(function() { // al pulsar (.click) el boton 1 (#b2) -->
		$("#dialogo").dialog({ // muestra la ventana  -->
			width: '70%',  // ancho de la ventana -->
			height: 500, // altura de la ventana -->
			show: "scale", // animación de la ventana al aparecer -->
			hide: "scale", // animación al cerrar la ventana -->
			resizable: "true", // fija o redimensionable si ponemos este valor a "true" -->
			position: "center",// posicion de la ventana en la pantalla (left, top, right...) -->
			modal: "true" // si esta en true bloquea el contenido de la web mientras la ventana esta activa (muy elegante) -->
		});
	});
        $(".boton_estudiante").click(function() { // al pulsar (.click) el boton 1 (#b2) -->
		$("#dialogo_estudiante").dialog({ // muestra la ventana  -->
			width: '40%',  // ancho de la ventana -->
			height: 400, // altura de la ventana -->
			show: "scale", // animación de la ventana al aparecer -->
			hide: "scale", // animación al cerrar la ventana -->
			resizable: "true", // fija o redimensionable si ponemos este valor a "true" -->
			position: "center",// posicion de la ventana en la pantalla (left, top, right...) -->
			modal: "true" // si esta en true bloquea el contenido de la web mientras la ventana esta activa (muy elegante) -->
		});
	});        
});

function mostrarVentanaEmergenteDocente(str) {
  if (str=="") {
    document.getElementById("dialogo").innerHTML="";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("dialogo").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET", obtenerUrlAbsoluta() + "controllers/consultarHorarioDocenteController.php?dni="+str,true);
  xmlhttp.send();
}


function mostrarVentanaEmergenteEstudiante(str) {
    //alert("OK");
  if (str=="") {
    document.getElementById("dialogo_estudiante").innerHTML="";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("dialogo_estudiante").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET", obtenerUrlAbsoluta() + "controllers/consultarDetalleEstudianteController.php?dni="+str,true);
  xmlhttp.send();
}

function obtenerUrlAbsoluta() {
    return document.getElementById('URLBASE').value;
}
//document.write(obtenerUrlAbsoluta());
