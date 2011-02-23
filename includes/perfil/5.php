<?php 
//Inicializar indicador de errores
$error = false;

// Leer datos del socios y cargarlos en las variables correspondientes.
if (!isset($_POST['enviar'])) {
	echo "Acceso Denegado";
//	echo "Reestrutcturando el sitio - finalización el 10-12-10 18hs gmt-3";
	die();
}
$user = $_SESSION['uid'];
$sql = "SELECT * FROM rtc_usr_login WHERE uid = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);


//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
//cargar en las variables los datos leidos de la DB

if (isset($_POST['userid'])&& $_POST['userid']!='') {
	$userid=substr(htmlspecialchars($_POST['userid']),0,40);
}



if (isset($_POST['clave']) && $_POST['clave']!='') {
	$clave=substr(htmlspecialchars($_POST['clave']),0,32);
	$clave_error="";
} else {
	$clave="";
}
if (isset($_POST['clave2'])) {
	$clave2=substr(htmlspecialchars($_POST['clave2']),0,32);
} else {
	$clave2="";
}
if ($clave!=$clave2) {
	$error=true;
	$clave2_error="Las claves no coinciden";
	$clave="";
	$clave2="";
} else {
	if ($clave!='' && strlen($clave)<8) {
		$error=true;
		$clave_error="La clave debe tener entre 8 y 16 caracteres";
		$clave="";
		$clave2="";
	}
}

if (isset($_POST['claveold']) && $_POST['claveold']!='') {
	$claveold=substr(htmlspecialchars($_POST['claveold']),0,32);
	$pass = hash('sha512', $userid.$claveold.'1s3a3l7t');
	$sql = sprintf("SELECT * FROM rtc_usr_login WHERE clave = '$pass' LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$claveold="";
	} else {
		$claveold_error="Clave Incorrecta - Debe ingresar su clave actual para efectuar modificaciones al perfil";
		$error=true;
	}
} else {
	$claveold="";
	$claveold_error="Ingrese su Clave - Debe ingresar su clave actual para efectuar modificaciones al perfil";
	$error=true;
}

//if (isset($_POST['email'])) {
//	$email=substr(htmlspecialchars($_POST['email']),0,40);
//	if(filter_var($email, FILTER_VALIDATE_EMAIL)!='0') {
//		$correo = mysql_real_escape_string(strtolower($email));
//		$sql = sprintf("SELECT * FROM rtc_usr_login WHERE " . "email = \"$correo\" LIMIT 1");
//		$result = mysql_query($sql);
//		$row = mysql_fetch_assoc($result);
//		if ( $row && $row['user_id'] != $userid) {
//			$error=true;
//			$email_error="La dirección de correo ya esta en uso";
//		} else {
//			$email_error="";
//		}
//	} else {
//		$error=true;
//		$email_error="La dirección de correo no es valida";
//	}
//} else {
	$email=$usuario['email'];
//	$error=true;
//	$email_error="*";
//}

//Si estan todas las variables, se procede a modificarlos datos ingresados.
if ($error==false) {

//ACA VA SQL PARA AGREGAR EL REGISTRO

		$uid = mysql_real_escape_string($userid); $em = mysql_real_escape_string($email);
		$fdm =  date('c');
//		$sql = sprintf("UPDATE rtc_usr_login SET email = '$em', fecha_de_modificacion = '$fdm' WHERE user_id='$uid'");
//		$result = mysql_query($sql);
	if ($clave!='') {
		$cla = hash('sha512', $uid.$clave.'1s3a3l7t');
		$sql = sprintf("UPDATE rtc_usr_login SET clave = '$cla', fecha_de_modificacion = '$fdm' WHERE user_id='$uid'");
		$result = mysql_query($sql);
		$clave="";
		$clave2="";
	}


//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP - Modificacion de ".$nom." ".$ape.".</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>Se registró una modificación en tus datos. Por seguridad se te avisa por medio de este correo.</p><p>Tu nombre de usuario es: <strong>".$uid."</strong></p><p>Los mismos te sirven para acceder a todos nuestros recursos y a tu perfil, donde podes actualizar tus datos personales y rotaractianos.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Modifiacion en tus datos";
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
		mail($em,$asunto,$cuerpo,$encabezado);

}
?>

<form action="socios_perfil.php" method="post">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><input name="seccion" type="hidden" id="seccion" value="5" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3"><h1>Perfil  de Cuenta de <?php echo $usuario['user_id'];?> </h1></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><span style="color:#FF0000"><?php if ($error==true) {echo "* Campos Obligatorios";}?></span></td>
      <td align="left"><input name="userid" type="hidden" value="<?php echo $usuario['user_id'];?>" /></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Nueva contraseña:</td>
      <td align="left">        <input title="La clave debe tener entre 8 y 32 caracteres" name="clave" type="password" id="clave" size="30" maxlength="32" value="<?php echo $clave;  ?>" />&nbsp;<span style="color:#FF0000"><?php echo $clave_error;?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Repetir la nueva contraseña:</td>
      <td align="left"><input title="Repita la clave" name="clave2" type="password" id="clave2" size="30" maxlength="32" value="<?php echo $clave2;  ?>" />&nbsp;<span style="color:#FF0000"><?php echo $clave2_error;?></span> </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Email:</td>
      <td align="left"><?php echo $email;  ?>
      &nbsp;<span style="color:#FF0000"><?php echo $email_error;?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Contraseña actual:</td>
      <td align="left"><input title="La clave debe tener entre 8 y 16 caracteres" name="claveold" type="password" id="claveold" size="30" maxlength="32" value="" />&nbsp;<span style="color:#FF0000"><?php echo $claveold_error;?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><p>&nbsp;</p></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
        <input type="submit" name="enviar" id="submit" value="Enviar" />
        <input type="reset" name="Cancelar" id="cancel" value="Cancelar" onclick="location.href='index.php';" />		</td>
    </tr>
  </table>
</form>
