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
if (isset($_POST['user_id']) && $_POST['user_id']!='') {
	$userid['var']=substr(htmlspecialchars($_POST['user_id']),0,40);
//Verifico que el usuario no exista
	$username = mysql_real_escape_string($userid['var']);
	$sql = sprintf("SELECT * FROM rtc_usuarios WHERE " . "user_id = \"$username\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$error=true;
		$userid['error']="El usuario ya existe";
	} else {
		$userid['error']="";
	}
} else {
	$userid['var']="";
	$error=true;
	$userid['error']="*";
}

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
		$sql = sprintf("SELECT * FROM rtc_usuarios WHERE " . "email = \"$correo\" LIMIT 1");
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

if (isset($_POST['dia'])&& $_POST['dia']!='0') {
	$dia['var']=substr(htmlspecialchars($_POST['dia']),0,2);
	$dia['error']="";
} else {
	$dia['var']="0";
}
if (isset($_POST['mes']) && $_POST['mes']!='0') {
	$mes=substr(htmlspecialchars($_POST['mes']),0,2);
	$dia['error']="";
} else { 
	$mes="0";
}
if (isset($_POST['anio']) && $_POST['anio']!='0') {
	$anio=substr(htmlspecialchars($_POST['anio']),0,4);
	$dia['error']="";
} else {
	$anio="0";
}
if ($dia['var']!='0' && $mes!='0' && $anio!='0') {
	if (!checkdate((int)$mes, (int)$dia['var'], (int)$anio)) {
		$dia['error']="Fecha incorrecta";
	}
} else {
	$error=true; $dia['error']="*";
}

if (isset($_POST['tipo_de_documento'])&& $_POST['tipo_de_documento']!='0') {
	$tipodni['var']=substr(htmlspecialchars($_POST['tipo_de_documento']),0,20);
	$tipodni['error']="";
} else {
	$tipodni['var']="0";
	$error=true;
	$tipodni['error']="*";
}

if (isset($_POST['numero_de_documento'])&& $_POST['numero_de_documento']!='') {
	$numerodni['var']=substr(htmlspecialchars($_POST['numero_de_documento']),0,15);
	$numerodni['error']="";
} else {
	$numerodni['var']="";
	$error=true;
	$numerodni['error']="*";
}

if (isset($_POST['ocupacion']) && $_POST['ocupacion']!='0') {
	$ocupacion['var']=substr(htmlspecialchars($_POST['ocupacion']),0,40);
	$ocupacion['error']="";
} else {
	$ocupacion['var']="";
	$error=true;
	$ocupacion['error']="*";
}

if (isset($_POST['direccion'])&& $_POST['direccion']!='') {
	$direccion['var']=substr(htmlspecialchars($_POST['direccion']),0,40);
	$direccion['error']="";
} else {
	$direccion['var']="";
	$error=true;
	$direccion['error']="*";
}

if (isset($_POST['pais'])&& $_POST['pais']!='0') {
	$pais['var']=substr(htmlspecialchars($_POST['pais']),0,40);
	$pais['error']="";
} else {
	$pais['var']="0";
	$error=true;
	$pais['error']="*";
}

if (isset($_POST['otropais'])&& $pais['var']=='-1' && $_POST['otropais']!='') {
	$otro_pais['var']=substr(htmlspecialchars($_POST['otropais']),0,40);
	$otro_pais['error']="";
} else {
	$otro_pais['var']="";
	if ($pais['var']=='-1') {
		$error=true;
		$otro_pais['error']="*";
	}
}

if (isset($_POST['provincia'])&& $_POST['provincia']!='0') {
	$provincia['var']=substr(htmlspecialchars($_POST['provincia']),0,40);
	$provincia['error']="";
} else {
	$provincia['var']="0";
	$error=true;
	$provincia['error']="*";
}

if (isset($_POST['otraprov'])&& $provincia['var']=='-1' && $_POST['otraprov']!='') {
	$otra_prov['var']=substr(htmlspecialchars($_POST['otraprov']),0,40);
	$otra_prov['error']="";
} else {
	$otra_prov['var']="";
	if ($provincia['var']=='-1') {
		$error=true;
		$otra_prov['error']="*";
	}
}

if (isset($_POST['ciudad'])&& $_POST['ciudad']!='0') {
	$ciudad['var']=substr(htmlspecialchars($_POST['ciudad']),0,40);
	$ciudad['error']="";
} else {
	$ciudad['var']="";
	$error=true;
	$ciudad['error']="*";
}

if (isset($_POST['otraciud'])&& $ciudad['var']=='-1' && $_POST['otraciud']!='') {
	$otra_ciud['var']=substr(htmlspecialchars($_POST['otraciud']),0,40);
	$otra_ciud['error']="";
} else {
	$otra_ciud['var']="";
	if ($ciudad['var']=='-1') {
		$error=true;
		$otra_ciud['error']="*";
	}
}

if (isset($_POST['codigo_postal'])) {
	$codigopostal['var']=substr(htmlspecialchars($_POST['codigo_postal']),0,40);
} else {
	$codigopostal['var']="";
}

if (isset($_POST['telefono'])) {
	$numerodetel['var']=substr(htmlspecialchars($_POST['telefono']),0,40); 
} else {
	$numerodetel['var']="";
}

if (isset($_POST['celular'])) {
	$numerodecel['var']=substr(htmlspecialchars($_POST['celular']),0,40);
} else {
	$numerodecel['var']="";
}

if (isset($_POST['programa_ri'])&& $_POST['programa_ri']!='0') {
	$programari['var']=substr(htmlspecialchars($_POST['programa_ri']),0,40);
	$programari['error']="";
} else {
	$programari['var']="";
	$error=true;
	$programari['error']="*";
}

if (isset($_POST['otroprograma'])&& $programari['var']=='-1'&& $_POST['otroprograma']!='') {
	$otroprograma['var']=substr(htmlspecialchars($_POST['otroprograma']),0,40);
	$otroprograma['error']="";
} else {
	$otroprograma['var']="";
	if ($programari['var']=='-1') {
		$error=true;
		$otroprograma['error']="*";
	}
}

if (isset($_POST['distrito'])&& $_POST['distrito']!='0') {
	$distrito['var']=substr(htmlspecialchars($_POST['distrito']),0,40);
	$distrito['error']="";
} else {
	$distrito['var']="";
	$error=true;
	$distrito['error']="*";
}

if (isset($_POST['club'])&& $_POST['club']!='0') {
	$club['var']=substr(htmlspecialchars($_POST['club']),0,40);
	$club['error']="";
} else {
	$club['var']="";
	$error=true;
	$club['error']="*";
}

if (isset($_POST['otrodistrito'])&& $distrito['var']=='-1' && $_POST['otrodistrito']!='') {
	$otrodistrito['var']=substr(htmlspecialchars($_POST['otrodistrito']),0,40);
	$otrodistrito['error']="";
} else {
	$otrodistrito['var']="";
	if ($distrito['var']=='-1') {
		$error=true;
		$otrodistrito['error']="*";
	}
}

if (isset($_POST['otroclub'])&& $club['var']=='-1' && $_POST['otroclub']!='') {
	$otroclub['var']=substr(htmlspecialchars($_POST['otroclub']),0,40);
	$otroclub['error']="";
} else {
	$otroclub['var']="";
	if ($club['var']=='-1') {
		$error=true;
		$otroclub['error']="*";
	}
}

if (isset($_POST['perfil_publico'])) {
	$perfil['var']=1;
} else if (!isset($_POST['submit'])) {
	$perfil['var'] = 1;
	} else {
		$perfil['var'] = 0;
	}

//Si estan todas las variables, se procede a verificar que los datos ingresados sean correctos.
if ($error==false) {
		if ($_SESSION['logged']) {
			session_defaults();
		}
//ACA VA SQL PARA AGREGAR EL REGISTRO
		$uid = mysql_real_escape_string($userid['var']); $em = $email['var']; $nom = $nombre['var'];
		$ape = $apellido['var']; $tdni = $tipodni['var']; $dni = $numerodni['var'];
		$ocu = $ocupacion['var']; $dire = $direccion['var']; $ciud = $ciudad['var'];
		$ociud = $otra_ciud['var']; $zip = $codigopostal['var'];
		$prov = $provincia['var']; $oprov = $otra_prov['var']; $pai = $pais['var'];
		$opai = $otro_pais['var']; $tel = $numerodetel['var'];
		$cel = $numerodecel['var']; $prog = $programari['var'];
		$oprog = $otroprograma['var']; $dist = $distrito['var'];
		$odist = $otrodistrito['var']; $clu = $club['var']; $oclu = $otroclub['var'];
		$per = $perfil['var'];
		$fdn =  date_format( date_create($anio.'-'.$mes.'-'.$dia['var']),'Y-m-d');
		$fdc =  date('c');
		$fdm =  date('c');
		$fua =  date('c');
		$faa =  date('c');
		$cla = hash('sha512', $uid.$clave['var'].'1s3a3l7t');
		$sql = sprintf("INSERT INTO rtc_usuarios (user_id, clave, email, nombre, apellido, fecha_de_nacimiento, tipo_de_documento, numero_de_documento, ocupacion, direccion, ciudad, ociudad, codigo_postal, provincia, oprovincia, pais, opais, telefono, celular, programa_ri, oprograma, distrito, odistrito, club, oclub, fecha_de_creacion, fecha_de_modificacion, fecha_ultimo_acceso, fecha_acceso_actual, perfil_publico) VALUES ('$uid', '$cla', '$em', '$nom', '$ape', '$fdn', '$tdni', '$dni', '$ocu', '$dire', '$ciud', '$ociud', '$zip', '$prov', '$oprov', '$pai', '$opai', '$tel', '$cel', '$prog', '$oprog', '$dist', '$odist', '$clu', '$oclu', '$fdc', '$fdm', '$fua', '$faa', '$per')");
		$result = mysql_query($sql);
//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP - Alta de ".$nom." ".$ape.".</title></head><body><h3>Bienvenido a la Base de Datos de A.I.R.A.U.P.</h3><p>Tu nombre de usuario es: <strong>".$uid."</strong><br>Tu password es: <strong>".$clave['var']."</strong></p><p>Los mismos te sirven para acceder a todos nuestros recursos y a tu perfil, donde podes actualizar tus datos personales y rotaractianos.</p><p>Esperamos que este recurso te sea de mucha utilidad!</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Alta de ".$nom." ".$ape;
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
		mail($em,$asunto,$cuerpo,$encabezado);
		$cuerpo ="<html><head><title>Base de Datos AIRAUP - Pedido de Agregado de Datos</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>El usuario <strong>".$uid."</strong> inform&oacute; de nuevos valores para agregar en las listas desplegables.</p><p>Los mismo son:</p><table width=\"100%\" border=\"0\"><tr><td>Campo</td><td>id</td><td>Otro</td></tr><tr><td>Pa&iacute;s:</td><td>".$pai."</td><td>".$opai."</td></tr><tr><td>Provincia:</td><td>".$prov."</td><td>".$oprov."</td></tr><tr><td>Ciudad:</td><td>".$ciud."</td><td>".$ociud."</td></tr><tr><td>Distrito</td><td>".$dist."</td><td>".$odist."</td></tr><tr><td>Club</td><td>".$clu."</td><td>".$oclu."</td></tr><tr><td>Programa RI:</td><td>".$prog."</td><td>".$oprog."</td></tr></table><p>&nbsp;</p><p>Una vez agregados a las tablas, modificar el usuario para que su informaci&oacute;n se corresponda con la actualizaci&oacute;n.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Agregado de Datos";
		if ($pai=='-1' || $prov=='-1' || $ciud=='-1' || $dist=='-1' || $clu=='-1' || $prog=='-1') { mail("gasparmdq@gmail.com",$asunto,$cuerpo,$encabezado); }
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
      <td align="left"><img src="../images/socios_alta.png" alt="Socios" width="48" height="48" hspace="0" vspace="0" border="0" align="right" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Se ha registrado con éxito al usuario <?php echo $userid['var'];?>. Para ver o modificar su perfil acceda desde el menú de socios.</td>
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
      <td align="left"><img src="../images/socios_alta.png" alt="Socios" width="48" height="48" hspace="0" vspace="0" border="0" align="right" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><span style="color:#FF0000"><?php if ($error==true) {echo "* Campos Obligatorios";}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Nombre de Usuario:</td>
      <td align="left">        <input title="Nombre para identificarse en el sistema" required speech name="user_id" type="text" id="user_id" maxlength="32" size="30" value="<?php echo $userid['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $userid['error'];?></span>    </td>
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
      <td>Email:</td>
      <td align="left">        <input title="Ingrese su direccion de correo electronico" name="email" type="text" id="email" size="30" maxlength="32" value="<?php echo $email['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $email['error'];?></span>      </td>
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
      <td>Fecha de Nacimiento:</td>
      <td align="left">
      	<select name="dia" id="dia">
        	<option value="0" <?php if ($dia['var']=='0') { echo 'selected="selected"';}?>>D&iacute;a</option>
		  	<?php 
				$sel='';			
				for ($i = 1; $i <= 31; $i++) {
					if ($i==$dia['var']) { $sel='selected="selected"'; } else { $sel=''; }
    				echo "<option value=\"{$i}\" {$sel} >".$i."</option>";
				}
			?>
        </select>
        <select name="mes" id="mes">
          <option value="0" <?php if ($mes=='0') { echo 'selected="selected"'; } ?>>Mes</option>
          <option value="1" <?php if ($mes=='1') { echo 'selected="selected"'; } ?>>Enero</option>
          <option value="2" <?php if ($mes=='2') { echo 'selected="selected"'; } ?>>Febrero</option>
          <option value="3" <?php if ($mes=='3') { echo 'selected="selected"'; } ?>>Marzo</option>
          <option value="4" <?php if ($mes=='4') { echo 'selected="selected"'; } ?>>Abril</option>
          <option value="5" <?php if ($mes=='5') { echo 'selected="selected"'; } ?>>Mayo</option>
          <option value="6" <?php if ($mes=='6') { echo 'selected="selected"'; } ?>>Junio</option>
          <option value="7" <?php if ($mes=='7') { echo 'selected="selected"'; } ?>>Julio</option>
          <option value="8" <?php if ($mes=='8') { echo 'selected="selected"'; } ?>>Agosto</option>
          <option value="9" <?php if ($mes=='9') { echo 'selected="selected"'; } ?>>Septiembre</option>
          <option value="10" <?php if ($mes=='10') { echo 'selected="selected"'; } ?>>Octubre</option>
          <option value="11" <?php if ($mes=='11') { echo 'selected="selected"'; } ?>>Noviembre</option>
          <option value="12" <?php if ($mes=='12') { echo 'selected="selected"'; } ?>>Diciembre</option>
        </select>
        <select name="anio" id="anio">
          <option value="0" selected="selected">A&ntilde;o</option>
		  	<?php 
				$sel='';			
				for ($i = 1920; $i <= 2009; $i++) {
					if ($i==$anio) { $sel='selected="selected"'; } else { $sel=''; }
    				echo "<option value=\"{$i}\" {$sel} >".$i."</option>";
				}
			?>
        </select>&nbsp;<span style="color:#FF0000"><?php echo $dia['error'];?></span>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Tipo de Documento:</td>
      <td align="left">
        <select name="tipo_de_documento" id="tipo_de_documento">
			<option value="0" <?php if ($tipodni['var']=='0') { echo 'selected="selected"'; } ?>>Elija tipo</option>
			<option value="1" <?php if ($tipodni['var']=='1') { echo 'selected="selected"'; } ?>>Cedula de Identidad</option>
			<option value="2" <?php if ($tipodni['var']=='2') { echo 'selected="selected"'; } ?>>Documento Nacional de Identidad</option>
			<option value="3" <?php if ($tipodni['var']=='3') { echo 'selected="selected"'; } ?>>Pasaporte</option>
        </select>&nbsp;<span style="color:#FF0000"><?php echo $tipodni['error'];?></span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Número de documento:</td>
      <td align="left">        <input title="Ingrese su numero de documento" name="numero_de_documento" type="text" id="numero_de_documento" size="30" maxlength="10" value="<?php echo $numerodni['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $numerodni['error'];?></span>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Ocupación:</td>
      <td align="left">
	<?php 
	$sql = "SELECT * FROM rtc_profesion ORDER BY ocupacion";
	$result = mysql_query($sql);
	echo "<select name=\"ocupacion\" id=\"ocupacion\">";
	echo "<option value=\"0\" >Seleccione Ocupaci&oacute;n</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_ocupacion']==$ocupacion['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_ocupacion']}\" {$sel} >{$row['ocupacion']}</option>";
	}
	?>
</select>&nbsp;<span style="color:#FF0000"><?php echo $ocupacion['error'];?></span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Dirección:</td>
      <td align="left">        <input title="Ingrese su direccion" name="direccion" type="text" id="direccion" value="<?php echo $direccion['var'];  ?>" size="30" maxlength="80"/>&nbsp;<span style="color:#FF0000"><?php echo $direccion['error'];?></span>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Pais:</td>
      <td align="left">
	<?php 
	$sql = "SELECT * FROM rtc_paises ORDER BY pais";
	$result = mysql_query($sql);
	echo "<select name=\"pais\" id=\"pais\" onchange=\"getProv(this.value)\" >";
	echo "<option value=\"0\">Seleccione Pais</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_paises']==$pais['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_paises']}\" {$sel} >{$row['pais']}</option>";
	}
	?>
	<option value="-1" <?php if ($pais['var']=='-1') {echo 'selected="selected"';}?> >Otro Pais</option></select>&nbsp;<span style="color:#FF0000"><?php echo $pais['error'];?></span></td>
    </tr>
    <tr>      
    	<td width="40"></td>
        <td>Provincia - Departamento - Estado:</td>
        <td align="left"><div id="provinciadiv">
            <?php 
	$sql = "SELECT * FROM rtc_provincias WHERE id_pais = ".$pais['var']." ORDER BY provincia";
	$result = mysql_query($sql);
	echo "<select name=\"provincia\" id=\"provincia\" onchange=\"getCiudad(this.value)\" >";
if ($pais['var'] == '0') {
	echo "<option value=\"0\">Elija Pais</option>";
} else if ($pais['var'] == '-1') {
		echo "<option value=\"-1\"> Otra </option>";
	} else if ($pais['var'] != '0'){
		echo "<option value=\"0\">Seleccione</option>";
}
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_provincia']==$provincia['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_provincia']}\" {$sel} >{$row['provincia']}</option>";
	}
	?>
      <option value="-1" <?php if ($provincia['var']=='-1') {echo 'selected="selected"';}?> >Otro</option></select>&nbsp;<span style="color:#FF0000"><?php echo $provincia['error'];?></span></div></td>
    </tr>
    <tr>      
     	<td width="40"></td>
        <td>Ciudad:</td>
        <td align="left"><div id="ciudaddiv">
	<?php 
	$sql = "SELECT * FROM rtc_ciudades WHERE id_provincia = ".$provincia['var']." ORDER BY ciudad";
	$result = mysql_query($sql);
	echo "<select name=\"ciudad\" id=\"ciudad\">";
if ($pais['var'] == '0') {
	echo "<option value=\"0\">Elija Pais</option>";
} else if ($pais['var'] != '-1') {
	echo "<option value=\"0\">Seleccione</option>";
}
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_ciudades']==$ciudad['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_ciudades']}\" {$sel}>{$row['ciudad']}</option>";
	}
	?>
	<option value="-1" <?php if ($ciudad['var']=='-1') {echo 'selected="selected"';}?> >Otra Ciudad</option></select>&nbsp;<span style="color:#FF0000"><?php echo $ciudad['error'];?></span></div></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Pais:</td>
      <td align="left"><input title="Ingrese su pais" name="otropais" type="text" id="otropais" value="<?php echo $otro_pais['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($pais['var']=='-1') {echo $otro_pais['error'];}?></span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otra Provincia / Departamento:</td>
      <td align="left"><input title="Ingrese su provincia / departamento" name="otraprov" type="text" id="otraprov" value="<?php echo $otra_prov['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($provincia['var']=='-1') {echo $otra_prov['error'];}?></span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otra Ciudad:</td>
      <td align="left"><input title="Ingrese la ciudad" name="otraciud" type="text" id="otraciud" value="<?php echo $otra_ciud['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($ciudad['var']=='-1') {echo $otra_ciud['error'];}?></span>	  </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Código Postal:</td>
      <td align="left">        <input title="Ingrese su codigo postal" name="codigo_postal" type="text" id="codigo_postal" size="30" maxlength="10" value="<?php echo $codigopostal['var'];  ?>"/>      </td>
    </tr>
      <tr>
        <td width="40">&nbsp;</td>
        <td>Número de Teléfono:</td>
      <td align="left">        <input title="Ingrese su numero de telefono" name="telefono" type="text" id="telefono" size="30" maxlength="20" value="<?php echo $numerodetel['var'];  ?>"/>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Número de Celular:</td>
      <td align="left">        <input title="Ingrese su numero de celular" name="celular" type="text" id="celular" size="30" maxlength="20" value="<?php echo $numerodecel['var'];  ?>"/>      </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Programa de RI:</td>
      <td align="left">
	<?php 
	$sql = "SELECT * FROM rtc_programas ORDER BY programa";
	$result = mysql_query($sql);
	echo "<select name=\"programa_ri\" id=\"programa_ri\">";
	echo "<option value=\"0\">Seleccione Programa</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_programa']==$programari['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_programa']}\" {$sel} >{$row['programa']}</option>";
	}
?>
	<option value="-1" <?php if ($programari['var']=='-1') {echo 'selected="selected"';}?> >Otro Programa</option>
</select>&nbsp;<span style="color:#FF0000"> <?php echo $programari['error'];?> </span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Programa</td>
      <td align="left"><input title="Pertenece a otro programa? Ingreselo aqui" name="otroprograma" type="text" id="otroprograma" size="30" maxlength="40" value="<?php echo $otroprograma['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php if ($programari['var']=='-1') {echo $otroprograma['error'];}?></span>	  </td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Distrito:</td>
      <td align="left">
	<?php 
	$sql = "SELECT * FROM rtc_distritos ORDER BY distrito";
	$result = mysql_query($sql);
	echo "<select name=\"distrito\" id=\"distrito\" onchange=\"getClub(this.value)\" >";
	echo "<option value=\"0\">Seleccione Distrito</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_distrito']==$distrito['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_distrito']}\" {$sel} >{$row['distrito']}</option>";
	}
?>
	<option value="-1" <?php if ($distrito['var']=='-1') {echo 'selected="selected"';}?>>Otro Distrito</option>
</select>&nbsp;<span style="color:#FF0000"><?php echo $distrito['error'];?></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Club:</td>
      <td align="left"> <div  id="clubdiv">
	<?php 
	echo "<select name=\"club\" id=\"club\">";
if ($distrito['var'] == '0') {
	echo "<option value=\"0\">Elija Distrito</option>";
} else if ($distrito['var']!= '-1') {
		echo "<option value=\"0\">Seleccione Club</option>";
}
	$distmp = $distrito['var'];
	$sql = "SELECT * FROM rtc_clubes WHERE id_distrito = '$distmp' ORDER BY club";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_club']==$club['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_club']}\" {$sel} >{$row['club']}</option>";
	}
	?>
	<option value="-1" <?php if ($club['var']=='-1') {echo 'selected="selected"';}?> >Otro Club</option></select>&nbsp;<span style="color:#FF0000"><?php echo $club['error'];?></span>      </div></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Distrito</td>
      <td align="left"><input title="No figura tu distrito? Ingresalo aqui" name="otrodistrito" type="text" id="otrodistrito" size="30" maxlength="10" value="<?php echo $otrodistrito['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php if ($distrito['var']=='-1') {echo $otrodistrito['error'];}?></span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Club</td>
      <td align="left"><input title="No figura tu club? Ingresalo aqui" name="otroclub" type="text" id="otroclub" size="30" maxlength="40" value="<?php echo $otroclub['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php if ($club['var']=='-1') {echo $otroclub['error'];}?></span></td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Perfil público:</td>
      <td align="left">        <input title="Elija si sus datos seran publicos o privados" name="perfil_publico" type="checkbox" id="perfil_publico" value="1" <?php if ($perfil['var']=='1') { echo "checked=\"checked\"";}  ?>/>        </td>
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
