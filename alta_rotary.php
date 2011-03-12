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
	$email['var']=substr(htmlspecialchars($_POST['email']),0,64);
	$notemail=false;
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
		$username = mysql_real_escape_string(strtolower($email['var']));
		$sql = sprintf("SELECT * FROM rtc_usr_login WHERE user_id = \"$username\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$error=true;
			$email['error']="El nombre de usuario ya esta en uso";
		} else {
			$email['error']="";
			$notemail=true;
		}

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
///// CHEQUEO DE LA PARTE INSTITUCIONAL

if (isset($_POST['programa_ri'])&& $_POST['programa_ri']!='0') {
	$programari['var']=substr(htmlspecialchars($_POST['programa_ri']),0,40);
	$programari['error']="";
} else {
	$programari['var'] = 0;
	$error=true;
	$programari['error']="*";
}

if (isset($_POST['otroprograma'])&& $programari['var']=='-1'&& $_POST['otroprograma']!='') {
	$otroprograma['var']=substr(htmlspecialchars($_POST['otroprograma']),0,40);
	$otroprograma['error']="";
} else {
	$otroprograma['var'] = "";
	if ($programari['var']=='-1') {
		$error=true;
		$otroprograma['error']="*";
	}
}

if (isset($_POST['distrito'])&& $_POST['distrito']!='0') {
	$distrito['var']=substr(htmlspecialchars($_POST['distrito']),0,40);
	$distrito['error']="";
} else {
	$distrito['var'] = 0;
	$error=true;
	$distrito['error']="*";
}

if (isset($_POST['club'])&& $_POST['club']!='0') {
	$club['var']=substr(htmlspecialchars($_POST['club']),0,80);
	$club['error']="";
} else {
	$club['var'] = 0;
	$error=true;
	$club['error']="*";
}

if (isset($_POST['otrodistrito'])&& $distrito['var']=='-1' && $_POST['otrodistrito']!='') {
	$otrodistrito['var']=substr(htmlspecialchars($_POST['otrodistrito']),0,40);
	$otrodistrito['error']="";
} else {
	$otrodistrito['var'] = "";
	if ($distrito['var']=='-1') {
		$error=true;
		$otrodistrito['error']="*";
	}
}

if (isset($_POST['otroclub'])&& $club['var']=='-1' && $_POST['otroclub']!='') {
	$otroclub['var']=substr(htmlspecialchars($_POST['otroclub']),0,80);
	$otroclub['error']="";
} else {
	$otroclub['var'] = "";
	if ($club['var']=='-1') {
		$error=true;
		$otroclub['error']="*";
	}
}


///// FIN DEL CHEQUEO DE LA PARTE INSTIUCIONAL



//Si estan todas las variables, se procede a verificar que los datos ingresados sean correctos.
if ($error==false) {
		if ($_SESSION['logged']) {
			session_defaults();
		}
//ACA VA SQL PARA AGREGAR EL REGISTRO
		$user_id = mysql_real_escape_string($email['var']);
		if ($notemail) {
			$em = "";
		} else {
			$em = mysql_real_escape_string($email['var']);
		}
		$nom = mysql_real_escape_string($nombre['var']);
		$ape = mysql_real_escape_string($apellido['var']); 
		$fdc =  date('c');
		$fdm =  date('c');
		$fua =  date('c');
		$faa =  date('c');
		$cla = hash('sha512', $user_id.$clave['var'].'1s3a3l7t');
		$sql = sprintf("INSERT INTO rtc_usr_login (user_id, clave, email, fecha_de_creacion, fecha_de_modificacion, fecha_ultimo_acceso, fecha_acceso_actual) VALUES ('$user_id', '$cla', '$em', '$fdc', '$fdm', '$fua', '$faa')");
//		echo "PASAME LO QUE SIGUE: ".$sql."<br />";
		$result = mysql_query($sql); //Ingreso los datos de login a la tabla que corresponden

		$sql = sprintf("SELECT uid FROM rtc_usr_login WHERE user_id='$user_id' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$userid = $row['uid'];
//		Consigo el UID del usuario recien creado
		
		$sql = sprintf("INSERT INTO rtc_usr_personales (user_id, nombre, apellido) VALUES ('$userid', '$nom', '$ape')");
		$result = mysql_query($sql); //Ingreso el nombre y apellido a la tabla de datos personales (y creo la entrada)

		$programa_ri=$programari['var'];
		$oprograma=$otroprograma['var'];
		$distrito=$distrito['var'];
		$odistrito=$otrodistrito['var'];
		$club=$club['var'];
		$oclub=$otroclub['var'];
		$sql = sprintf("INSERT INTO rtc_usr_institucional (user_id, programa_ri, oprograma, distrito, odistrito, club, oclub) VALUES ('$userid', '$programa_ri', '$oprograma', '$distrito', '$odistrito', '$club', '$oclub')");
		$result = mysql_query($sql); //Ingreso la informacion de club y distrito a la tabla de datos institucionales(y creo la entrada)
		
		$cuerpo ="<html><head><title>Base de Datos AIRAUP - Pedido de Agregado de Datos</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>El usuario <strong>".$userid."</strong> inform&oacute; de nuevos valores para agregar en las listas desplegables.</p><p>Los mismo son:</p><table width=\"100%\" border=\"0\"><tr><td>Campo</td><td>id</td><td>Otro</td></tr><tr><td>Distrito</td><td>".$distrito."</td><td>".$odistrito."</td></tr><tr><td>Club</td><td>".$club."</td><td>".$oclub."</td></tr><tr><td>Programa RI:</td><td>".$programa_ri."</td><td>".$oprograma."</td></tr></table><p>&nbsp;</p><p>Una vez agregados a las tablas, modificar el usuario para que su informaci&oacute;n se corresponda con la actualizaci&oacute;n.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Agregado de Datos";
		if ($dist=='-1' || $clu=='-1' || $prog=='-1') { mail("gasparmdq@gmail.com",$asunto,$cuerpo,$encabezado); }

		
		
//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP - Alta de ".$nom." ".$ape.".</title></head><body><h3>Bienvenido a la Base de Datos de A.I.R.A.U.P.</h3><p>Tu mail de acceso es: <strong>".$uid."</strong><br>Tu password es: <strong>".$clave['var']."</strong></p><p>Los mismos te sirven para acceder a todos nuestros recursos y a tu perfil, donde podes actualizar tus datos personales y rotaractianos.</p><p>Esperamos que este recurso te sea de mucha utilidad!</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Alta de ".$nom." ".$ape;
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
 		if (!$notemail) { mail($em,$asunto,$cuerpo,$encabezado); }
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
      <td>Se ha registrado con éxito al usuario <?php echo $email['var'];?>. Para ver o modificar su perfil acceda desde la administracion de datos personales.</td>
      <td>&nbsp;</td>
    </tr>
  </table>
<?php
	} else {
//		echo "FALTA INGRESAR DATOS";
?>

<form action="alta_rotary.php" method="post">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h1>Inscripción a la conferencia </h1></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><span style="color:#FF0000"><?php if ($error==true) {echo "* Campos Obligatorios";}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Email o DNI:</td>
      <td align="left"><input title="Ingrese su direccion de correo electronico" name="email" type="text" id="email" size="30" maxlength="64" value="<?php echo $email['var'];  ?>"/>
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Programa de RI:</td>
      <td align="left">
	<?php 
	$sql = "SELECT * FROM rtc_cfg_programas ORDER BY programa";
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
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Programa</td>
      <td align="left"><input title="Pertenece a otro programa? Ingreselo aqui" name="otroprograma" type="text" id="otroprograma" size="30" maxlength="40" value="<?php echo $otroprograma['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php if ($programari['var']=='-1') {echo $otroprograma['error'];}?></span>	  </td>
      <td align="left">&nbsp;</td>
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
      <td align="left">&nbsp;</td>
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
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Distrito</td>
      <td align="left"><input title="No figura tu distrito? Ingresalo aqui" name="otrodistrito" type="text" id="otrodistrito" size="30" maxlength="10" value="<?php echo $otrodistrito['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php if ($distrito['var']=='-1') {echo $otrodistrito['error'];}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Club</td>
      <td align="left"><input title="No figura tu club? Ingresalo aqui" name="otroclub" type="text" id="otroclub" size="30" maxlength="80" value="<?php echo $otroclub['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php if ($club['var']=='-1') {echo $otroclub['error'];}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Se inscribe a la conferencia de Distrito?</td>
      <td align="left"><input type="checkbox" name="conferencia" id="conferencia" /></td>
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
