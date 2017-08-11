<SCRIPT>
 var tick;
 function stop() {
   clearTimeout(tick);
   }
 function simple_reloj() {
   var ut=new Date();
   var h,m,s;
   var time="        ";
   h=ut.getHours();
   m=ut.getMinutes();
   s=ut.getSeconds();
   if(s<=9) s="0"+s;
  if(m<=9) m="0"+m;
  if(h<=9) h="0"+h;
  time+=h+":"+m+":"+s;

  document.getElementById('reloj').innerHTML=timeTo12HrFormat(time);
  tick=setTimeout("simple_reloj()",1000);    
  }
function timeTo12HrFormat(time)
{   // Take a time in 24 hour format and format it in 12 hour format
    var time_part_array = time.split(":");
    var ampm = 'AM';

    if (time_part_array[0] >= 12) {
        ampm = 'PM';
    }

    if (time_part_array[0] > 12) {
        time_part_array[0] = time_part_array[0] - 12;
    }

    formatted_time = time_part_array[0] + ':' + time_part_array[1] + ':' + time_part_array[2] + ' ' + ampm;

    return formatted_time;
}

</SCRIPT>

<div id="_usuario">
    <img src="<?php echo URLBASE; ?>public/img/default.png"/>
        <div id="_descripcion">
            <b><?php echo $_SESSION["usuario"]; ?></b>
            <br>
            <label>(<?php echo $_SESSION["nivel_usuario"]; ?>)</label>
            <br>
            <label id="fecha-hora">
                                <span class="glyphicon glyphicon-calendar"></span> <span><?php echo date("d/m/Y"); ?></span>
				<BR>
				<span class="glyphicon glyphicon-time"></span> <span id="reloj"></span>
            </label>
        </div>    

<BODY  onLoad="simple_reloj();" onUnload="stop();">
</BODY>
</div>
