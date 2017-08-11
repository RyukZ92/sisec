<html lang="es">
<head>
	<title>PHPMAILER</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<?php
require_once('class.phpmailer.php'); // Especifique la ruta del archivo class.phpmailer.php
define('GUSER', 'ryukzero92@gmail.com'); // GMail username
define('GPWD', 'Monica94.'); // GMail password

function smtpmailer($to, $from, $from_name, $subject, $body) 
	{ 
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->CharSet='UTF-8'; //Línea agregada por Miguel Ángel Salazar Castillo->UPTP "LMR".
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; 
	$mail->Username = GUSER;  
	$mail->Password = GPWD;           
	$mail->SetFrom($from, $from_name);
	$mail->Subject=$subject; //Línea modificada por Miguel Salazar. Agregado el cotejamiento "utf8_encode".
	$mail->Body = $body;
	$mail->IsHTML(true); //Línea agregada por Miguel Ángel Salazar Castillo->UPTP "LMR". FUENTE: http://blog.unijimpe.net/enviar-html-con-phpmailer/
	$mail->AddAddress($to);

	if(!$mail->Send())
		{
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
		} 
	else
		{
		$error = 'Message sent!';
		return true;
		}
	}
?>

<?php

?>
</body>
</html>
