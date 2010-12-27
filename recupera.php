<?php
include 'includes/header.php';

@$usuario = new Usuario();
@$usuario->_checkSession();

//if ($_SESSION['logged']) {
//	session_defaults();
//	header("Location: includes/seccion.php?s=ingreso");
//}


//Inicializar indicador de errores
$error=false;

//Inicializo la libreria para el CAPTCHA
require_once('includes/recaptchalib.php');

//Si se envio el form reviso el CAPTCHA
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
$captcha['var']='0';
if (!$resp->is_valid) {
		$captcha['var']='-1';
		$captcha['error']='Fallo la verificación';
		$error=true;
}

if (isset($_GET['codigo'])) {
	$codigo=substr(htmlspecialchars($_GET['codigo']),0,40);

// Verifica que el codigo esté registrado	
	$sql = sprintf("SELECT email FROM rtc_usr_login WHERE code ='$codigo' LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	if ( $row ) {
// EXISTE EL CODIGO

		$uid = $row['email'];
		$clave = mt_rand();
		$cla = hash('sha512', $uid.$clave.'1s3a3l7t');

		$sql = sprintf("UPDATE rtc_usr_login SET code = 'NULL', user_id = '$uid', clave = '$cla'  WHERE email = '$uid'");
//		echo "PASAME LO QUE SIGUE: ".$sql."<br />";
		$result = mysql_query($sql); //Ingreso el codigo de seguridad en la tabla

//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP</title></head><body><h3>Bienvenido a la Base de Datos de A.I.R.A.U.P.</h3><p>Tu direcci&oacute;n de correo es: <strong>".$uid."</strong><br>  Tu nueva contrase&ntilde;a es: <strong>".$clave."</strong></p><p>Record&aacute; utilizar tu direcci&oacute;n de correo como nombre de usuario para ingresar al sistema</p>
<p>Geek Team<br>
RRHH AIRAUP</p>
</body></html>";
		$asunto = "Base de Datos AIRAUP - Recupera Contraseña";
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
		mail($uid,$asunto,$cuerpo,$encabezado);

?>
	<div><h2>Recupera Contraseña</h2></div>
	<div>Se te envió un correo a <?php echo $uid;?> con tu nueva contrase&ntilde;a.</div>
<?php
	} else {
// NO EXISTE EL CODIGO
?>
	<div><h2>Recupera Contraseña</h2></div>
	<div>El c&oacute;digo de seguridad no es valido.</div>
<?php
	}
	
} // SI NO ESTA SETEADO EL CODIGO


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
if (isset($_POST['email'])) {
	$email['var']=substr(htmlspecialchars($_POST['email']),0,40);

	if(filter_var($email['var'], FILTER_VALIDATE_EMAIL)!='0') {
		$correo = mysql_real_escape_string(strtolower($email['var']));
		$sql = sprintf("SELECT * FROM rtc_usr_login WHERE " . "email = \"$correo\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$email['error']="";
		} else {
			$error=true;
			$email['error']="La dirección de correo no est&aacute; en uso";
		}
	} else {
		$error=true;
		$email['error']="La dirección de correo no es valida";
	}
} else {
	$email['var']="";
	$error=true;
	$email['error']="";
}


//Si estan todas las variables, se procede a verificar que los datos ingresados sean correctos.
if ($error==false) {
		if ($_SESSION['logged']) {
			session_defaults();
		}
//ACA VA SQL PARA AGREGAR EL REGISTRO
		$uid = mysql_real_escape_string($email['var']);
		
		$code = md5(mt_rand().mt_rand().mt_rand());
//		$cla = hash('sha512', $uid.$clave['var'].'1s3a3l7t');

		$sql = sprintf("UPDATE rtc_usr_login SET code = '$code' WHERE email = '$uid'");
//		echo "PASAME LO QUE SIGUE: ".$sql."<br />";
		$result = mysql_query($sql); //Ingreso el codigo de seguridad en la tabla

//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP</title></head><body><h3>Bienvenido a la Base de Datos de A.I.R.A.U.P.</h3><p>Tu codigo de seguridad es: <strong>".$code."</strong></p><p>Ingresa <a href=\"http://base.airaup.org/recupera.php?codigo=".$code."\">aquí</a> para recuperar tu contrase&ntilde;a.</p><p>Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Recupera Contraseña";
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
		mail($uid,$asunto,$cuerpo,$encabezado);
?>
<div><h2>Recupera Contraseña</h2></div>
<div>Se te envió un correo a <?php echo $uid;?> con las instrucciones para cambiar tu contraseña.</div>
<?php
	} else {
?>

<form action="recupera.php" method="post">
<div><h2>Recupera Contraseña</h2></div>
<div>Email: <input title="Ingrese su direccion de correo electronico" name="email" type="text" id="email" size="30" maxlength="32" value="<?php echo $email['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $email['error'];?></span><br />
  Ingrese las dos palabras en el recuadro:<br />
  <?php echo recaptcha_get_html($publickey);?> <span style="color:#FF0000">  <?php if ($captcha['var']=='-1') {echo $captcha['error']; $captcha['var']='0';}?></span></div>

<input type="submit" name="submit" id="submit" value="Enviar" />
<input type="reset" name="Cancelar" id="cancel" value="Cancelar" onclick="location.href='index.php';" />
</form>

<?php 
}
include 'includes/footer.php';
?>
