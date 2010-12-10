<?php 
//Inicializar indicador de errores
$error=false;

// Leer datos del socios y cargarlos en las variables correspondientes.
//if (!isset($_POST['enviar'])) {
//	echo "Acceso Denegado";
	echo "Reestrutcturando el sitio - finalización el 10-12-10 18hs gmt-3";
	die();
//}
$user = $_SESSION['uid'];
$sql = "SELECT * FROM rtc_usuarios WHERE uid = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);


//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
	//cargar en las variables los datos leidos de la DB

if (isset($_POST['userid'])&& $_POST['userid']!='') {
	$userid['var']=substr(htmlspecialchars($_POST['userid']),0,40);
}



if (isset($_POST['clave']) && $_POST['clave']!='') {
	$clave['var']=substr(htmlspecialchars($_POST['clave']),0,16);
	$clave['error']="";
} else {
	$clave['var']="";
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
	if ($clave['var']!='' && strlen($clave['var'])<8) {
		$error=true;
		$clave['error']="La clave debe tener entre 8 y 16 caracteres";
		$clave['var']="";
		$clave2['var']="";
	}
}

if (isset($_POST['claveold']) && $_POST['claveold']!='') {
	$claveold['var']=substr(htmlspecialchars($_POST['claveold']),0,16);
	$pass = hash('sha512', $userid['var'].$claveold['var'].'1s3a3l7t');
	$sql = sprintf("SELECT * FROM rtc_usuarios WHERE clave = '$pass' LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$claveold['error']="";
	} else {
		$claveold['error']="Clave Incorrecta - Debe ingresar su clave actual para efectuar modificaciones al perfil";
		$error=true;
	}
} else {
	$claveold['var']="";
	$claveold['error']="Ingrese su Clave - Debe ingresar su clave actual para efectuar modificaciones al perfil";
	$error=true;
}

if (isset($_POST['email'])) {
	$email['var']=substr(htmlspecialchars($_POST['email']),0,40);

	if(filter_var($email['var'], FILTER_VALIDATE_EMAIL)!='0') {
		$correo = mysql_real_escape_string(strtolower($email['var']));
		$sql = sprintf("SELECT * FROM rtc_usuarios WHERE " . "email = \"$correo\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		if ( $row['user_id'] != $userid['var']) {
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
	$email['var']=$usuario['email'];
	$error=true;
	$email['error']="*";
}

if (isset($_POST['nombre'])&& $_POST['nombre']!='') {
	$nombre['var']=substr(htmlspecialchars($_POST['nombre']),0,40);
	$nombre['error']="";
} else {
	$nombre['var']=$usuario['nombre'];
	$error=true;
	$nombre['error']="*";
}

if (isset($_POST['apellido'])&& $_POST['apellido']!='') {
	$apellido['var']=substr(htmlspecialchars($_POST['apellido']),0,40);
	$apellido['error']="";
} else {
	$apellido['var']=$usuario['apellido'];
	$error=true;
	$apellido['error']="*";
}

	$fecha=date_parse($usuario['fecha_de_nacimiento']);
if (isset($_POST['dia'])&& $_POST['dia']!='0') {
	$dia['var']=substr(htmlspecialchars($_POST['dia']),0,2);
	$dia['error']="";
} else {
	$dia['var'] = $fecha['day'];
}
if (isset($_POST['mes']) && $_POST['mes']!='0') {
	$mes=substr(htmlspecialchars($_POST['mes']),0,2);
	$dia['error']="";
} else { 
	$mes = $fecha['month'];
}
if (isset($_POST['anio']) && $_POST['anio']!='0') {
	$anio=substr(htmlspecialchars($_POST['anio']),0,4);
	$dia['error']="";
} else {
	$anio = $fecha['year'];
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
	$tipodni['var'] = $usuario['tipo_de_documento'];
	$error=true;
	$tipodni['error']="*";
}

if (isset($_POST['numero_de_documento'])&& $_POST['numero_de_documento']!='') {
	$numerodni['var']=substr(htmlspecialchars($_POST['numero_de_documento']),0,15);
	$numerodni['error']="";
} else {
	$numerodni['var'] = $usuario['numero_de_documento'];
	$error=true;
	$numerodni['error']="*";
}

if (isset($_POST['ocupacion']) && $_POST['ocupacion']!='0') {
	$ocupacion['var']=substr(htmlspecialchars($_POST['ocupacion']),0,40);
	$ocupacion['error']="";
} else {
	$ocupacion['var'] = $usuario['ocupacion'];
	$error=true;
	$ocupacion['error']="*";
}

if (isset($_POST['direccion'])&& $_POST['direccion']!='') {
	$direccion['var']=substr(htmlspecialchars($_POST['direccion']),0,40);
	$direccion['error']="";
} else {
	$direccion['var'] = $usuario['direccion'];
	$error=true;
	$direccion['error']="*";
}

if (isset($_POST['pais'])&& $_POST['pais']!='0') {
	$pais['var']=substr(htmlspecialchars($_POST['pais']),0,40);
	$pais['error']="";
} else {
	$pais['var'] = $usuario['pais'];
	$error=true;
	$pais['error']="*";
}

if (isset($_POST['otropais'])&& $pais['var']=='-1' && $_POST['otropais']!='') {
	$otro_pais['var']=substr(htmlspecialchars($_POST['otropais']),0,40);
	$otro_pais['error']="";
} else {
	$otro_pais['var'] = $usuario['opais'];
	if ($pais['var']=='-1') {
		$error=true;
		$otro_pais['error']="*";
	}
}

if (isset($_POST['provincia'])&& $_POST['provincia']!='0') {
	$provincia['var']=substr(htmlspecialchars($_POST['provincia']),0,40);
	$provincia['error']="";
} else {
	$provincia['var'] = $usuario['provincia'];
	$error=true;
	$provincia['error']="*";
}

if (isset($_POST['otraprov'])&& $provincia['var']=='-1' && $_POST['otraprov']!='') {
	$otra_prov['var']=substr(htmlspecialchars($_POST['otraprov']),0,40);
	$otra_prov['error']="";
} else {
	$otra_prov['var'] = $usuario['oprovincia'];
	if ($provincia['var']=='-1') {
		$error=true;
		$otra_prov['error']="*";
	}
}

if (isset($_POST['ciudad'])&& $_POST['ciudad']!='0') {
	$ciudad['var']=substr(htmlspecialchars($_POST['ciudad']),0,40);
	$ciudad['error']="";
} else {
	$ciudad['var'] = $usuario['ciudad'];
	$error=true;
	$ciudad['error']="*";
}

if (isset($_POST['otraciud'])&& $ciudad['var']=='-1' && $_POST['otraciud']!='') {
	$otra_ciud['var']=substr(htmlspecialchars($_POST['otraciud']),0,40);
	$otra_ciud['error']="";
} else {
	$otra_ciud['var'] = $usuario['ociudad'];
	if ($ciudad['var']=='-1') {
		$error=true;
		$otra_ciud['error']="*";
	}
}

if (isset($_POST['codigo_postal'])) {
	$codigopostal['var']=substr(htmlspecialchars($_POST['codigo_postal']),0,40);
} else {
	$codigopostal['var'] = $usuario['codigo_postal'];
}

if (isset($_POST['telefono'])) {
	$numerodetel['var']=substr(htmlspecialchars($_POST['telefono']),0,40); 
} else {
	$numerodetel['var'] = $usuario['telefono'];
}

if (isset($_POST['celular'])) {
	$numerodecel['var']=substr(htmlspecialchars($_POST['celular']),0,40);
} else {
	$numerodecel['var'] = $usuario['celular'];
}

if (isset($_POST['programa_ri'])&& $_POST['programa_ri']!='0') {
	$programari['var']=substr(htmlspecialchars($_POST['programa_ri']),0,40);
	$programari['error']="";
} else {
	$programari['var'] = $usuario['programa_ri'];
	$error=true;
	$programari['error']="*";
}

if (isset($_POST['otroprograma'])&& $programari['var']=='-1'&& $_POST['otroprograma']!='') {
	$otroprograma['var']=substr(htmlspecialchars($_POST['otroprograma']),0,40);
	$otroprograma['error']="";
} else {
	$otroprograma['var'] = $usuario['oprograma'];
	if ($programari['var']=='-1') {
		$error=true;
		$otroprograma['error']="*";
	}
}

if (isset($_POST['distrito'])&& $_POST['distrito']!='0') {
	$distrito['var']=substr(htmlspecialchars($_POST['distrito']),0,40);
	$distrito['error']="";
} else {
	$distrito['var'] = $usuario['distrito'];
	$error=true;
	$distrito['error']="*";
}

if (isset($_POST['club'])&& $_POST['club']!='0') {
	$club['var']=substr(htmlspecialchars($_POST['club']),0,40);
	$club['error']="";
} else {
	$club['var'] = $usuario['club'];
	$error=true;
	$club['error']="*";
}

if (isset($_POST['otrodistrito'])&& $distrito['var']=='-1' && $_POST['otrodistrito']!='') {
	$otrodistrito['var']=substr(htmlspecialchars($_POST['otrodistrito']),0,40);
	$otrodistrito['error']="";
} else {
	$otrodistrito['var'] = $usuario['odistrito'];
	if ($distrito['var']=='-1') {
		$error=true;
		$otrodistrito['error']="*";
	}
}

if (isset($_POST['otroclub'])&& $club['var']=='-1' && $_POST['otroclub']!='') {
	$otroclub['var']=substr(htmlspecialchars($_POST['otroclub']),0,40);
	$otroclub['error']="";
} else {
	$otroclub['var'] = $usuario['oclub'];
	if ($club['var']=='-1') {
		$error=true;
		$otroclub['error']="*";
	}
}

if ((isset($_POST['perfil_publico'])) AND ($error==false)) {
	$perfil['var'] = 1;
} else {
	if ((isset($_POST['perfil_publico_verifica'])) AND ($error==false)) {
			$perfil['var'] = 0;
		} else {
			$perfil['var'] = $usuario['perfil_publico'];
		}
	}

//Si estan todas las variables, se procede a modificarlos datos ingresados.
if ($error==false) {

//ACA VA SQL PARA AGREGAR EL REGISTRO



		$uid = mysql_real_escape_string($userid['var']); $em = mysql_real_escape_string($email['var']); $nom = mysql_real_escape_string($nombre['var']);
		$ape = mysql_real_escape_string($apellido['var']); $tdni = mysql_real_escape_string($tipodni['var']); $dni = mysql_real_escape_string($numerodni['var']);
		$ocu = mysql_real_escape_string($ocupacion['var']); $dire = mysql_real_escape_string($direccion['var']); $ciud = mysql_real_escape_string($ciudad['var']);
		$ociud = mysql_real_escape_string($otra_ciud['var']); $zip = mysql_real_escape_string($codigopostal['var']);
		$prov = mysql_real_escape_string($provincia['var']); $oprov = mysql_real_escape_string($otra_prov['var']); $pai = mysql_real_escape_string($pais['var']);
		$opai = mysql_real_escape_string($otro_pais['var']); $tel = mysql_real_escape_string($numerodetel['var']);
		$cel = mysql_real_escape_string($numerodecel['var']); $prog = mysql_real_escape_string($programari['var']);
		$oprog = mysql_real_escape_string($otroprograma['var']); $dist = mysql_real_escape_string($distrito['var']);
		$odist = mysql_real_escape_string($otrodistrito['var']); $clu = mysql_real_escape_string($club['var']); $oclu = mysql_real_escape_string($otroclub['var']);
		$per = mysql_real_escape_string($perfil['var']);
		$fdn =  date_format( date_create($anio.'-'.$mes.'-'.$dia['var']),'Y-m-d');
		$fdm =  date('c');
		$sql = sprintf("UPDATE rtc_usuarios SET email = '$em', nombre = '$nom', apellido = '$ape', fecha_de_nacimiento = '$fdn', tipo_de_documento = '$tdni', numero_de_documento = '$dni', ocupacion = '$ocu', direccion = '$dire', ciudad = '$ciud', ociudad = '$ociud', codigo_postal = '$zip', provincia = '$prov', oprovincia = '$oprov', pais = '$pai', opais = '$opai', telefono = '$tel', celular = '$cel', programa_ri = '$prog', oprograma = '$oprog', distrito = '$dist', odistrito = '$odist', club = '$clu', oclub = '$oclu', fecha_de_modificacion = '$fdm', perfil_publico = '$per' WHERE user_id='$uid'");
		$result = mysql_query($sql);
	if ($clave['var']!='') {
		$cla = hash('sha512', $uid.$clave['var'].'1s3a3l7t');
		$sql = sprintf("UPDATE rtc_usuarios SET clave = '$cla' WHERE user_id='$uid'");
		$result = mysql_query($sql);
	}


//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP - Modificacion de ".$nom." ".$ape.".</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>Se registró una modificación en tus datos. Por seguridad se te avisa por medio de este correo.</p><p>Tu nombre de usuario es: <strong>".$uid."</strong></p><p>Los mismos te sirven para acceder a todos nuestros recursos y a tu perfil, donde podes actualizar tus datos personales y rotaractianos.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Modifiacion en tus datos";
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
		mail($em,$asunto,$cuerpo,$encabezado);
		$cuerpo ="<html><head><title>Base de Datos AIRAUP - Pedido de Agregado de Datos</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>El usuario <strong>".$uid."</strong> inform&oacute; de nuevos valores para agregar en las listas desplegables.</p><p>Los mismo son:</p><table width=\"100%\" border=\"0\"><tr><td>Campo</td><td>id</td><td>Otro</td></tr><tr><td>Pa&iacute;s:</td><td>".$pai."</td><td>".$opai."</td></tr><tr><td>Provincia:</td><td>".$prov."</td><td>".$oprov."</td></tr><tr><td>Ciudad:</td><td>".$ciud."</td><td>".$ociud."</td></tr><tr><td>Distrito</td><td>".$dist."</td><td>".$odist."</td></tr><tr><td>Club</td><td>".$clu."</td><td>".$oclu."</td></tr><tr><td>Programa RI:</td><td>".$prog."</td><td>".$oprog."</td></tr></table><p>&nbsp;</p><p>Una vez agregados a las tablas, modificar el usuario para que su informaci&oacute;n se corresponda con la actualizaci&oacute;n.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Agregado de Datos";
		if ($pai=='-1' || $prov=='-1' || $ciud=='-1' || $dist=='-1' || $clu=='-1' || $prog=='-1') { mail("gasparmdq@gmail.com",$asunto,$cuerpo,$encabezado); }
		
?>

<?php
}
?>

<form action="socios_perfil.php" method="post">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><input name="seccion" type="hidden" id="seccion" value="1" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h1>Perfil Personal de <?php echo $usuario['user_id'];?> </h1></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
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
      <td align="left">        <input title="La clave debe tener entre 8 y 16 caracteres" name="clave" type="password" id="clave" size="30" maxlength="16" value="<?php echo $clave['var'];  ?>" />&nbsp;<span style="color:#FF0000"><?php echo $clave['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Repetir la nueva contraseña:</td>
      <td align="left"><input title="Repita la clave" name="clave2" type="password" id="clave2" size="30" maxlength="16" value="<?php echo $clave2['var'];  ?>" />&nbsp;<span style="color:#FF0000"><?php echo $clave2['error'];?></span> </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Email:</td>
      <td align="left">        <input title="Ingrese su direccion de correo electronico" name="email" type="text" id="email" size="30" maxlength="32" value="<?php echo $email['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $email['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Nombre:</td>
      <td align="left">        <input title="Ingrese su nombre" name="nombre" type="text" id="nombre" size="30" maxlength="40" value="<?php echo $nombre['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $nombre['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Apellido:</td>
      <td align="left">        <input title="Ingrese su apellido" name="apellido" type="text" id="apellido" size="30" maxlength="40" value="<?php echo $apellido['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $apellido['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
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
      <td align="left">&nbsp;</td>
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
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Número de documento:</td>
      <td align="left">        <input title="Ingrese su numero de documento" name="numero_de_documento" type="text" id="numero_de_documento" size="30" maxlength="10" value="<?php echo $numerodni['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php echo $numerodni['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
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
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Dirección:</td>
      <td align="left">        <input title="Ingrese su direccion" name="direccion" type="text" id="direccion" value="<?php echo $direccion['var'];  ?>" size="30" maxlength="80"/>&nbsp;<span style="color:#FF0000"><?php echo $direccion['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
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
      <td align="left">&nbsp;</td>
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
        <td align="left">&nbsp;</td>
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
        <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otro Pais:</td>
      <td align="left"><input title="Ingrese su pais" name="otropais" type="text" id="otropais" value="<?php echo $otro_pais['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($pais['var']=='-1') {echo $otro_pais['error'];}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otra Provincia / Departamento:</td>
      <td align="left"><input title="Ingrese su provincia / departamento" name="otraprov" type="text" id="otraprov" value="<?php echo $otra_prov['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($provincia['var']=='-1') {echo $otra_prov['error'];}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otra Ciudad:</td>
      <td align="left"><input title="Ingrese la ciudad" name="otraciud" type="text" id="otraciud" value="<?php echo $otra_ciud['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($ciudad['var']=='-1') {echo $otra_ciud['error'];}?></span>	  </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Código Postal:</td>
      <td align="left">        <input title="Ingrese su codigo postal" name="codigo_postal" type="text" id="codigo_postal" size="30" maxlength="10" value="<?php echo $codigopostal['var'];  ?>"/>      </td>
      <td align="left">&nbsp;</td>
    </tr>
      <tr>
        <td width="40">&nbsp;</td>
        <td>Número de Teléfono:</td>
      <td align="left">        <input title="Ingrese su numero de telefono" name="telefono" type="text" id="telefono" size="30" maxlength="20" value="<?php echo $numerodetel['var'];  ?>"/>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Número de Celular:</td>
      <td align="left">        <input title="Ingrese su numero de celular" name="celular" type="text" id="celular" size="30" maxlength="20" value="<?php echo $numerodecel['var'];  ?>"/>      </td>
      <td align="left">&nbsp;</td>
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
      <td align="left"><input title="No figura tu club? Ingresalo aqui" name="otroclub" type="text" id="otroclub" size="30" maxlength="40" value="<?php echo $otroclub['var'];  ?>"/>&nbsp;<span style="color:#FF0000"><?php if ($club['var']=='-1') {echo $otroclub['error'];}?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Perfil público:</td>
      <td align="left">        <input title="Elija si sus datos seran publicos o privados" name="perfil_publico" type="checkbox" id="perfil_publico" value="1" <?php if ($perfil['var']=='1') { echo "checked=\"checked\"";}  ?>/>
      <input name="perfil_publico_verifica" type="hidden" id="perfil_publico_verifica" value="1" /></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Contraseña actual:</td>
      <td align="left"><input title="La clave debe tener entre 8 y 16 caracteres" name="claveold" type="password" id="claveold" size="30" maxlength="16" value="" />&nbsp;<span style="color:#FF0000"><?php echo $claveold['error'];?></span></td>
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
