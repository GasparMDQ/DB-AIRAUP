<?php 
//Inicializar indicador de errores
$error=false;
$error_cargos=false;

// Leer datos del socios y cargarlos en las variables correspondientes.
if (!isset($_POST['enviar'])) {
	echo "Acceso Denegado";
//	echo "Reestrutcturando el sitio - finalización el 10-12-10 18hs gmt-3";
	die();
}
$user = $_SESSION['uid'];

//Verifico que existan las filas en las tablas institucionales correspondiente al Usuario. Si no estan las creo.
$sql = "SELECT * FROM rtc_usr_institucional WHERE uid = '$user' LIMIT 1";
$result = mysql_query($sql);
if (!mysql_num_rows($result)) {
	$fdm =  date('c');
	$sql = "INSERT INTO rtc_usr_institucional (user_id, fecha_de_modificacion) VALUES ('$user','$fdm')";
	$result = mysql_query($sql);
}


$sql = "SELECT * FROM rtc_usr_institucional WHERE uid = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);

//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
//cargar en las variables los datos leidos de la DB

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

if (isset($_POST['cargo'])&& $_POST['cargo']!='') {
	$cargo=substr(htmlspecialchars($_POST['cargo']),0,10);
} else if ($_POST['enviar']=='Agregar'){
	$error_cargos=true;
}

if (isset($_POST['periodo'])&& $_POST['periodo']!='') {
	$periodo=substr(htmlspecialchars($_POST['periodo']),0,10);
} else if ($_POST['enviar']=='Agregar') {
	$error_cargos=true;
}

if (isset($_POST['enviar'])) {
	$accion=substr(htmlspecialchars($_POST['enviar']),0,10);

	//Si estan todas las variables, se procede a modificarlos datos ingresados.
	if ($error_cargos==false) {
	//ACA VA SQL PARA AGREGAR EL REGISTRO
		if ($accion=='Agregar') {
			$sql = sprintf("INSERT INTO rtc_usr_institucional_cargos (uid, user_id, cargo, periodo) VALUES ('','$user','$cargo','$periodo')");
			$result = mysql_query($sql);
		} else if ($accion=='Eliminar') {
			$idfila=substr(htmlspecialchars($_POST['idfila']),0,10);
			$sql = sprintf("DELETE FROM rtc_usr_institucional_cargos WHERE uid=$idfila");
			$result = mysql_query($sql);
		}
	}
	if ($error == false) {
		if ($accion=='Enviar') {

			$prog = mysql_real_escape_string($programari['var']);
			$oprog = mysql_real_escape_string($otroprograma['var']);
			$dist = mysql_real_escape_string($distrito['var']);
			$odist = mysql_real_escape_string($otrodistrito['var']);
			$clu = mysql_real_escape_string($club['var']);
			$oclu = mysql_real_escape_string($otroclub['var']);
			$fdm =  date('c');
			$sql = sprintf("UPDATE rtc_usr_institucional SET programa_ri = '$prog', oprograma = '$oprog', distrito = '$dist', odistrito = '$odist', club = '$clu', oclub = '$oclu', fecha_de_modificacion = '$fdm' WHERE user_id='$user'");
			$result = mysql_query($sql);

//ENVIO DE MAIL CON CONFIRMACION DE ALTA Y DATOS DE USUARIO
			$cuerpo = "<html><head><title>Base de Datos AIRAUP</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>Se registró una modificación en tus datos. Por seguridad se te avisa por medio de este correo.</p><p>Tu nombre de usuario es: <strong>".$uid."</strong></p><p>El mismos te sirve para acceder a todos nuestros recursos y a tu perfil, donde podes actualizar tus datos personales y rotaractianos.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
			$asunto = "Base de Datos AIRAUP - Modifiacion en tus datos";
			$encabezado = "MIME-Version: 1.0" . "\r\n";
			$encabezado .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$encabezado .= "From: Base de Datos de AIRAUP <base@airaup.org>";
//			mail($em,$asunto,$cuerpo,$encabezado);
			$cuerpo ="<html><head><title>Base de Datos AIRAUP - Pedido de Agregado de Datos</title></head><body><h3>Base de Datos de A.I.R.A.U.P.</h3><p>El usuario <strong>".$uid."</strong> inform&oacute; de nuevos valores para agregar en las listas desplegables.</p><p>Los mismo son:</p><table width=\"100%\" border=\"0\"><tr><td>Campo</td><td>id</td><td>Otro</td></tr><tr><td>Distrito</td><td>".$dist."</td><td>".$odist."</td></tr><tr><td>Club</td><td>".$clu."</td><td>".$oclu."</td></tr><tr><td>Programa RI:</td><td>".$prog."</td><td>".$oprog."</td></tr></table><p>&nbsp;</p><p>Una vez agregados a las tablas, modificar el usuario para que su informaci&oacute;n se corresponda con la actualizaci&oacute;n.</p><p align=\"right\">Geek Team<br>RRHH AIRAUP</p></body></html>";
			$asunto = "Base de Datos AIRAUP - Agregado de Datos";
			if ($dist=='-1' || $clu=='-1' || $prog=='-1') { mail("gasparmdq@gmail.com",$asunto,$cuerpo,$encabezado); }
		}
	}
}

$sql = "SELECT * FROM rtc_usr_personales WHERE uid = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);

?>

  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="40"><input name="seccion" type="hidden" id="seccion" value="3" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4"><h1>Perfil Institucional de <?php echo $usuario['nombre']." ".$usuario['apellido'];?> </h1></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
<form action="socios_perfil.php" method="post">
<input name="seccion" type="hidden" id="seccion" value="3" />
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
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
      <td colspan="4" align="center">
        <input type="submit" name="enviar" id="submit" value="Enviar" />
        <input type="reset" name="Cancelar" id="cancel" value="Cancelar" onclick="location.href='index.php';" />		</td>
    </tr>
  </table>
</form>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php

	$sql = sprintf("SELECT * FROM rtc_usr_institucional_cargos WHERE user_id=$user ORDER BY periodo, cargo");
	$result = mysql_query($sql);
	while($rowc = mysql_fetch_assoc($result))
	{
?>
<form action="socios_perfil.php" method="post">
<input name="seccion" type="hidden" id="seccion" value="3" />
    <tr>
      <td width="40"><input name="idfila" type="hidden" value="<?php echo $rowc['uid'];?>" />&nbsp;</td>
      <td align="left"><?php echo $rowc['periodo'];?></td>
      <td align="left">
<?php 
	$cargoid = $rowc['cargo'];
	$sqlcar = sprintf("SELECT * FROM rtc_cargos WHERE id=$cargoid LIMIT 1");
	$resultcar = mysql_query($sqlcar);
	$rowcar = mysql_fetch_assoc($resultcar);
	echo $rowcar['cargo'];
?></td>
      <td align="left"><input type="submit" name="enviar" id="enviar" value="Eliminar" /></td>
      <td align="left"></form>&nbsp;</td>
    </tr>
<?php
	} //fin del WHILE
?>
  </table>

<form action="socios_perfil.php" method="post">
<input name="seccion" type="hidden" id="seccion" value="3" />
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="40">&nbsp;</td>
      <td align="left"><select name="periodo" id="periodo" title="Periodo">
        <option value="" selected="selected" >Seleccione</option>
        <option value="1997-1998">1997-1998</option>
        <option value="1998-1999">1998-1999</option>
        <option value="1999-2000">1999-2000</option>
        <option value="2000-2001">2000-2001</option>
        <option value="2001-2002">2001-2002</option>
        <option value="2002-2003">2002-2003</option>
        <option value="2003-2004">2003-2004</option>
        <option value="2004-2005">2004-2005</option>
        <option value="2005-2006">2005-2006</option>
        <option value="2006-2007">2006-2007</option>
        <option value="2007-2008">2007-2008</option>
        <option value="2008-2009">2008-2009</option>
        <option value="2009-2010">2009-2010</option>
        <option value="2010-2011">2010-2011</option>
        <option value="2011-2012">2011-2012</option>
        <option value="2012-2013">2012-2013</option>
        <option value="2013-2014">2013-2014</option>
        <option value="2014-2015">2014-2015</option>
      </select>&nbsp;</td>
      <td align="left"><select name="cargo" id="cargo" title="Cargos">
        <option value="" selected="selected" >Seleccione</option>
<?php
	$sqlcar = sprintf("SELECT * FROM rtc_cargos ORDER BY cargo");
	$resultcar = mysql_query($sqlcar);
	while ($rowcar = mysql_fetch_assoc($resultcar))
	{
		echo "<option value=\"".$rowcar['id']."\">".$rowcar['cargo']."</option>";
	}
?>
      </select>&nbsp;</td>
      <td align="left"><input type="submit" name="enviar" id="enviar" value="Agregar" /></td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
</form>