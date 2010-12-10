<?php
include 'includes/header.php';

@$usuario = new Usuario();
@$usuario->_checkSession();

//if ($_SESSION['logged']) {
//	session_defaults();
//	header("Location: includes/seccion.php?s=ingreso");
//}


//PENDIENTE: Formulario finalizado. Falta cargar los datos una vez verificados e informar de lo mismo al usuario.

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


//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
if (isset($_POST['clave']) && $_POST['clave']!='') {
	$clave['var']=substr(htmlspecialchars($_POST['clave']),0,16);
	$clave['error']="";
} else {
	$clave['var']="";
	$clave['error']="*";
}
if (isset($_POST['clave2'])) {
	$clave2['var']=substr(htmlspecialchars($_POST['clave2']),0,16);
} else {
	$clave2['var']="";
}
if ($clave['var']!=$clave2['var']) {
	$error=true;
	$clave2['error']="Las claves no coinciden";
	$clave['var']="";
	$clave2['var']="";
} else {
	if (strlen($clave['var'])<8) {
		$error=true;
		$clave['error']="La clave debe tener entre 8 y 16 caracteres";
	}
}

if (isset($_POST['email'])) {
	$email['var']=substr(htmlspecialchars($_POST['email']),0,40);

	if(filter_var($email['var'], FILTER_VALIDATE_EMAIL)!='0') {
		$correo = mysql_real_escape_string(strtolower($email['var']));
		$sql = sprintf("SELECT * FROM rtc_usr_login WHERE " . "email = \"$correo\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$error=true;
			$email['error']="La dirección de correo ya esta en uso";
		} else {
			$email['error']="";
		}
	} else {
		$error=true;
		$email['error']="La dirección de correo no es valida";
	}
} else {
	$email['var']="";
	$error=true;
	$email['error']="*";
}

if (isset($_POST['nombre'])&& $_POST['nombre']!='') {
	$nombre['var']=substr(htmlspecialchars($_POST['nombre']),0,40);
	$nombre['error']="";
} else {
	$nombre['var']="";
	$error=true;
	$nombre['error']="*";
}

if (isset($_POST['apellido'])&& $_POST['apellido']!='') {
	$apellido['var']=substr(htmlspecialchars($_POST['apellido']),0,40);
	$apellido['error']="";
} else {
	$apellido['var']="";
	$error=true;
	$apellido['error']="*";
}

//Si estan todas las variables, se procede a verificar que los datos ingresados sean correctos.
if ($error==false) {
		if ($_SESSION['logged']) {
			session_defaults();
		}
//ACA VA SQL PARA AGREGAR EL REGISTRO
		$uid = mysql_real_escape_string($email['var']);
		$em = mysql_real_escape_string($email['var']);
		$nom = mysql_real_escape_string($nombre['var']);
		$ape = mysql_real_escape_string($apellido['var']); 
		$fdc =  date('c');
		$fdm =  date('c');
		$fua =  date('c');
		$faa =  date('c');
		$cla = hash('sha512', $uid.$clave['var'].'1s3a3l7t');
		$sql = sprintf("INSERT INTO rtc_usr_login (user_id, clave, email, fecha_de_creacion, fecha_de_modificacion, fecha_ultimo_acceso, fecha_acceso_actual) VALUES ('$uid', '$cla', '$em', '$fdc', '$fdm', '$fua', '$faa')");
//		echo "PASAME LO QUE SIGUE: ".$sql."<br />";
		$result = mysql_query($sql); //Ingreso los datos de login a la tabla que corresponden

		$sql = sprintf("SELECT * FROM rtc_usr_login WHERE email='$em' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$userid = $row['uid'];
//		Consigo el UID del usuario recien creado
		
		$sql = sprintf("INSERT INTO rtc_usr_personales (user_id, nombre, apellido) VALUES ('$userid', '$nom', '$ape')");
//		echo "PASAME LO QUE SIGUE: ".$sql."<br />";
		$result = mysql_query($sql); //Ingreso el nombre y apellido a la tabla de datos personales (y creo la entrada)
		
		
//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP - Alta de ".$nom." ".$ape.".</title></head><body><h3>Bienvenido a la Base de Datos de A.I.R.A.U.P.</h3><p>Tu mail de acceso es: <strong>".$uid."</strong><br>Tu password es: <strong>".$clave['var']."</strong></p><p>Los mismos te sirven para acceder a todos nuestros recursos y a tu perfil, donde podes actualizar tus datos personales y rotaractianos.</p><p>Esperamos que este recurso te sea de mucha utilidad!</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Alta de ".$nom." ".$ape;
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
		mail($em,$asunto,$cuerpo,$encabezado);
?>
   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="40">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h1>Alta de Socios</h1></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Se ha registrado con éxito al usuario <?php echo $email['var'];?>. Para ver o modificar su perfil acceda desde el menú de socios.</td>
      <td>&nbsp;</td>
    </tr>
  </table>
<?php
	} else {
//		echo "FALTA INGRESAR DATOS";
?>

<form action="socios_alta.php" method="post">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h1>Alta de Socios</h1></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><span style="color:#FF0000"><?php if ($error==true) {echo "* Campos Obligatorios";}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Email:</td>
      <td align="left"><input title="Ingrese su direccion de correo electronico" name="email" type="text" id="email" size="30" maxlength="32" value="<?php echo $email['var'];  ?>"/>
      &nbsp;<span style="color:#FF0000"><?php echo $email['error'];?></span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Clave:</td>
      <td align="left">        <input title="La clave debe tener entre 8 y 16 caracteres" required name="clave" type="password" id="clave" size="30" maxlength="16" value="<?php echo $clave['var'];  ?>" />&nbsp;<span style="color:#FF0000"><?php echo $clave['error'];?></span>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Repetir la Clave:</td>
      <td align="left"><input title="Repita la clave" required name="clave2" type="password" id="clave2" size="30" maxlength="16" value="<?php echo $clave2['var'];  ?>" />&nbsp;<span style="color:#FF0000"><?php echo $clave2['error'];?></span> </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Nombre:</td>
      <td align="left">        <input title="Ingrese su nombre" name="nombre" type="text" id="nombre" size="30" maxlength="40" value="<?php echo $nombre['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $nombre['error'];?></span>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Apellido:</td>
      <td align="left">        <input title="Ingrese su apellido" name="apellido" type="text" id="apellido" size="30" maxlength="40" value="<?php echo $apellido['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $apellido['error'];?></span>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Ingrese las dos palabras en el recuadro</td>
      <td align="left"><?php echo recaptcha_get_html($publickey);?> <span style="color:#FF0000">  <?php if ($captcha['var']=='-1') {echo $captcha['error']; $captcha['var']='0';}?></span></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><p>&nbsp;</p></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <input type="submit" name="submit" id="submit" value="Enviar" />
        <input type="reset" name="Cancelar" id="cancel" value="Cancelar" onclick="location.href='index.php';" />		</td>
    </tr>
  </table>

</form>

<?php 
}
include 'includes/footer.php';
?>
