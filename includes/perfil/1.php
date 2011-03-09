<?php 
//Inicializar indicador de errores
$error=false;

// Leer datos del socios y cargarlos en las variables correspondientes.
if (!isset($_POST['enviar'])) {
	echo "Acceso Denegado";
//	echo "Reestrutcturando el sitio - finalización el 10-12-10 18hs gmt-3";
	die();
}

$user = $_SESSION['uid'];
$sql = "SELECT * FROM rtc_usr_personales WHERE user_id = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);

//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
	//cargar en las variables los datos leidos de la DB

if (isset($_POST['userid'])&& $_POST['userid']!='') {
	$userid['var']=substr(htmlspecialchars($_POST['userid']),0,40);
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

if ((isset($_POST['perfil_publico'])) AND ($error==false)) {
	$perfil['var'] = 1;
} else {
	if ((isset($_POST['perfil_publico_verifica'])) AND ($error==false)) {
			$perfil['var'] = 0;
		} else {
			$perfil['var'] = $usuario['perfil_publico'];
		}
	}

if (isset($_POST['contacto_nombre'])) {
	$contacto_nombre=substr(htmlspecialchars($_POST['contacto_nombre']),0,80);
} else {
	$contacto_nombre= $usuario['contacto_nombre'];
}
if (isset($_POST['contacto_telefono'])) {
	$contacto_telefono=substr(htmlspecialchars($_POST['contacto_telefono']),0,30);
} else {
	$contacto_telefono = $usuario['contacto_telefono'];
}
if (isset($_POST['contacto_relacion'])) {
	$contacto_relacion=substr(htmlspecialchars($_POST['contacto_relacion']),0,40);
} else {
	$contacto_relacion = $usuario['contacto_relacion'];
}


//Si estan todas las variables, se procede a modificarlos datos ingresados.
if ($error==false) {

//ACA VA SQL PARA AGREGAR EL REGISTRO

		$uid = mysql_real_escape_string($userid['var']);

		$sql = "SELECT * FROM rtc_usr_login WHERE uid='$uid' LIMIT 1";
		$result = mysql_query ($sql);
		$row = mysql_fetch_assoc($result);
		$em = $row['email'];

		$nom = mysql_real_escape_string($nombre['var']);
		$ape = mysql_real_escape_string($apellido['var']);
		$tdni = mysql_real_escape_string($tipodni['var']);
		$dni = mysql_real_escape_string($numerodni['var']);
		$ocu = mysql_real_escape_string($ocupacion['var']);
		$dire = mysql_real_escape_string($direccion['var']);
		$ciud = mysql_real_escape_string($ciudad['var']);
		$ociud = mysql_real_escape_string($otra_ciud['var']);
		$zip = mysql_real_escape_string($codigopostal['var']);
		$prov = mysql_real_escape_string($provincia['var']);
		$oprov = mysql_real_escape_string($otra_prov['var']);
		$pai = mysql_real_escape_string($pais['var']);
		$opai = mysql_real_escape_string($otro_pais['var']);
		$tel = mysql_real_escape_string($numerodetel['var']);
		$cel = mysql_real_escape_string($numerodecel['var']);
		$per = mysql_real_escape_string($perfil['var']);
		$fdn =  date_format( date_create($anio.'-'.$mes.'-'.$dia['var']),'Y-m-d');
		$fdm =  date('c');
		$contacto_nombre = mysql_real_escape_string($contacto_nombre);
		$contacto_telefono = mysql_real_escape_string($contacto_telefono);
		$contacto_relacion = mysql_real_escape_string($contacto_relacion);
		
		
		$sql = sprintf("UPDATE rtc_usr_personales SET nombre = '$nom', apellido = '$ape', fecha_de_nacimiento = '$fdn', tipo_de_documento = '$tdni', numero_de_documento = '$dni', ocupacion = '$ocu', direccion = '$dire', ciudad = '$ciud', ociudad = '$ociud', codigo_postal = '$zip', provincia = '$prov', oprovincia = '$oprov', pais = '$pai', opais = '$opai', telefono = '$tel', celular = '$cel', fecha_de_modificacion = '$fdm', perfil_publico = '$per', contacto_nombre = '$contacto_nombre', contacto_telefono = '$contacto_telefono', contacto_relacion = '$contacto_relacion' WHERE user_id='$user'");
		$result = mysql_query($sql);

//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
		$cuerpo = "<html><head><title>Base de Datos AIRAUP - Modificacion de ".$nom." ".$ape.".</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>Se registr&oacute; una modificaci&oacute;n en tus datos. Por seguridad se te avisa por medio de este correo.</p><p>Tu nombre de usuario es: <strong>".$row['user_id']."</strong></p><p>Los mismos te sirven para acceder a todos nuestros recursos y a tu perfil, donde podes actualizar tus datos personales y rotaractianos.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Modifiacion en tus datos";
		$encabezado = "MIME-Version: 1.0" . "\r\n";
		$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
		mail($em,$asunto,$cuerpo,$encabezado);
		$cuerpo ="<html><head><title>Base de Datos AIRAUP - Pedido de Agregado de Datos</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>El usuario <strong>".$uid."</strong> inform&oacute; de nuevos valores para agregar en las listas desplegables.</p><p>Los mismo son:</p><table width=\"100%\" border=\"0\"><tr><td>Campo</td><td>id</td><td>Otro</td></tr><tr><td>Pa&iacute;s:</td><td>".$pai."</td><td>".$opai."</td></tr><tr><td>Provincia:</td><td>".$prov."</td><td>".$oprov."</td></tr><tr><td>Ciudad:</td><td>".$ciud."</td><td>".$ociud."</td></tr></table><p>&nbsp;</p><p>Una vez agregados a las tablas, modificar el usuario para que su informaci&oacute;n se corresponda con la actualizaci&oacute;n.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
		$asunto = "Base de Datos AIRAUP - Agregado de Datos";
		if ($pai=='-1' || $prov=='-1' || $ciud=='-1') { mail("gasparmdq@gmail.com",$asunto,$cuerpo,$encabezado); }
		
?>

<?php
}

$user = $_SESSION['uid'];
$sql = "SELECT * FROM rtc_usr_personales WHERE user_id = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);

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
      <td colspan="3"><h1>Perfil Personal de <?php echo $usuario['nombre']." ".$usuario['apellido'];?> </h1></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><span style="color:#FF0000"><?php if ($error==true) {echo "* Campos Obligatorios";}?></span></td>
      <td align="left"><input name="userid" type="hidden" value="<?php echo $usuario['user_id'];?>" /></td>
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
	<?php 
	$sql = "SELECT * FROM rtc_cfg_tipos_de_documentos ORDER BY tipo";
	$result = mysql_query($sql);
	echo "<select name=\"tipo_de_documento\" id=\"tipo_de_documento\">";
	echo "<option value=\"0\" >Elija tipo</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id']==$tipodni['var']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id']}\" {$sel} >{$row['tipo']}</option>";
	}
	?>
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
      <td width="40">&nbsp;</td>
      <td>Otra Ciudad:</td>
      <td align="left"><input title="Ingrese la ciudad" name="otraciud" type="text" id="otraciud" value="<?php echo $otra_ciud['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($ciudad['var']=='-1') {echo $otra_ciud['error'];}?></span>	  </td>
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
      <td>Otro Pais:</td>
      <td align="left"><input title="Ingrese su pais" name="otropais" type="text" id="otropais" value="<?php echo $otro_pais['var'];  ?>" size="30" maxlength="40"/>&nbsp;<span style="color:#FF0000"><?php if ($pais['var']=='-1') {echo $otro_pais['error'];}?></span></td>
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
      <td>Perfil público:</td>
      <td align="left">        <input title="Elija si sus datos seran publicos o privados" name="perfil_publico" type="checkbox" id="perfil_publico" value="1" <?php if ($perfil['var']=='1') { echo "checked=\"checked\"";}  ?>/>
      <input name="perfil_publico_verifica" type="hidden" id="perfil_publico_verifica" value="1" /></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><p>&nbsp;</p></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h2>Contacto en caso de Emergecias</h2></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Nombre:</td>
      <td align="left">        <input name="contacto_nombre" type="text" id="contacto_nombre" size="30" maxlength="80" value="<?php echo $contacto_nombre;  ?>"/>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
	<tr>
        <td width="40">&nbsp;</td>
        <td>Número de Teléfono:</td>
      <td align="left">        <input name="contacto_telefono" type="text" id="contacto_telefono" size="30" maxlength="30" value="<?php echo $contacto_telefono;  ?>"/></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Relacion:</td>
      <td align="left">        <input name="contacto_relacion" type="text" id="contacto_relacion" size="30" maxlength="40" value="<?php echo $contacto_relacion;  ?>"/></td>
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
