<?php 
//Inicializar indicador de errores
$error=false;

// Leer datos del socios y cargarlos en las variables correspondientes.
if (!isset($_POST['enviar'])) {
	echo "Acceso Denegado";
	die();
}
$user = $_SESSION['uid'];
$sql = "SELECT * FROM rtc_usuarios WHERE uid = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);

//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
//cargar en las variables los datos leidos de la DB

if (isset($_POST['userid'])&& $_POST['userid']!='') {
	$userid['var']=substr(htmlspecialchars($_POST['userid']),0,40);
}

if (isset($_POST['cargo'])&& $_POST['cargo']!='') {
	$cargo=substr(htmlspecialchars($_POST['cargo']),0,10);
} else {
	$error=true;
}

if (isset($_POST['periodo'])&& $_POST['periodo']!='') {
	$periodo=substr(htmlspecialchars($_POST['periodo']),0,10);
} else {
	$error=true;
}


//if (isset($_POST['claveold']) && $_POST['claveold']!='') {
//	$claveold['var']=substr(htmlspecialchars($_POST['claveold']),0,16);
//	$pass = hash('sha512', $userid['var'].$claveold['var'].'1s3a3l7t');
//	$sql = sprintf("SELECT * FROM rtc_usuarios WHERE clave = '$pass' LIMIT 1");
//	$result = mysql_query($sql);
//	$row = mysql_fetch_object($result);
//	if ( $row ) {
//		$claveold['error']="";
//	} else {
//		$claveold['error']="Clave Incorrecta.";
//		$error=true;
//	}
//} else {
//	$claveold['var']="";
//	$claveold['error']="Ingrese su clave.";
//	$error=true;
//}

if (isset($_POST['enviar'])) {
	$accion=substr(htmlspecialchars($_POST['enviar']),0,10);

	//Si estan todas las variables, se procede a modificarlos datos ingresados.
	if ($error==false) {
	//ACA VA SQL PARA AGREGAR EL REGISTRO
		if ($accion=='Agregar') {
			$sql = sprintf("INSERT INTO rtc_institucional (uid, usuario, cargo, periodo) VALUES ('','$user','$cargo','$periodo')");
			$result = mysql_query($sql);
		}
	} else if ($accion=='Eliminar') {
		$idfila=substr(htmlspecialchars($_POST['idfila']),0,10);
		$sql = sprintf("DELETE FROM rtc_institucional WHERE uid=$idfila");
		$result = mysql_query($sql);
	}
}

?>

  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><input name="seccion" type="hidden" id="seccion" value="3" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h1>Perfil Institucional de <?php echo $usuario['user_id'];?> </h1></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left"><img src="../images/socios_perfil.png" alt="Socios" width="48" height="48" hspace="0" vspace="0" border="0" align="right" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php

	$sql = sprintf("SELECT * FROM rtc_institucional WHERE usuario=$user ORDER BY periodo");
	$result = mysql_query($sql);
	while($rowc = mysql_fetch_assoc($result))
	{
?>
<form action="socios_perfil.php" method="post">
<input name="userid" type="hidden" value="<?php echo $usuario['user_id'];?>" />
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
<input name="userid" type="hidden" value="<?php echo $usuario['user_id'];?>" />
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