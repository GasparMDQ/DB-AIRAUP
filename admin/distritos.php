<?php
include 'header.php';
$distrito['error']="";

$admin=mysql_real_escape_string(substr(htmlspecialchars($_POST['admin']),0,40));
$rdr=mysql_real_escape_string(substr(htmlspecialchars($_POST['rdr']),0,40));
$distrito['var']=substr(htmlspecialchars($_POST['distrito']),0,40);
$distrito['id']=substr(htmlspecialchars($_POST['iddistrito']),0,4);
$consulta = mysql_real_escape_string($distrito['var']);
$consulta1 = mysql_real_escape_string($distrito['id']);

if (isset($_POST['distrito'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_distritos WHERE " . "distrito = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$error=true;
		$distrito['error']="El distrito ingresado ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_distritos (id_distrito, distrito, uid_rdr, uid_admin) VALUES ('', '$consulta','$rdr','$admin')");
		$result = mysql_query($sql);
		$distrito['error']="Se agregó ".$distrito['var']." al listado";
	}
} else if (isset($_POST['distrito'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_distritos WHERE " . "distrito = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$sql = sprintf("SELECT * FROM rtc_clubes WHERE " . "id_distrito = \"$consulta1\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$distrito['error']="El distrito no puede ser borrado porque tiene ingresado clubes";
		} else {
			$sql = sprintf("DELETE FROM rtc_distritos WHERE id_distrito='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$distrito['error']="Se borró ".$distrito['var']." del listado";
		}
	} else {
		$distrito['error']="El distrito escrito no se encuentra en la lista";
	}
} else if (isset($_POST['distrito'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_distritos SET distrito='$consulta', uid_rdr='$rdr', uid_admin='$admin' WHERE id_distrito='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$distrito['error']="No se pudo modificar";
	} else {
		$distrito['error']="Se modificó ".$distrito['var']." del listado";
	}
}

?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="40">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left"><?php echo $distrito['error']; ?></td>
    </tr>
<?php
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

$sql = "SELECT * FROM rtc_distritos ORDER BY distrito";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{

?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['distrito']; ?>	  </td>
      <td align="left"><form action="distritos.php" method="post">
          <input name="iddistrito" id="iddistrito" type="hidden" value="<?php echo $row['id_distrito'];  ?>" />
        <input name="distrito" type="text" id="distrito" value="<?php echo $row['distrito'];  ?>" size="10" maxlength="40" />
	</td>
	<td>RDR:
<?php
	$id_dist=$row['id_distrito'];
	$sql1 = "SELECT * FROM rtc_usr_personales, rtc_usr_institucional WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito=$id_dist ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"rdr\" id=\"rdr\">";
	echo "<option value=\"0\" selected > </option>";
	$sel='';
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($row['uid_rdr']==$rowtmp['uid']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['uid']}\" {$sel} >{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>
	</td>
	<td>Admin:
<?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"admin\" id=\"admin\">";
	echo "<option value=\"0\" selected > </option>";
	$sel='';
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($row['uid_admin']==$rowtmp['uid']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['uid']}\" {$sel} >{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>
	</td><td>
      <input type="submit" name="submit" id="submit" value="Modificar" />
          <input type="submit" name="submit" id="submit" value="Borrar" />
      </form></td>
    </tr>
<?php } ?> 
    <tr>
      <td><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>N&uacute;mero:</td>
      <td align="left"><form action="distritos.php" method="post">
      	<input name="distrito" type="text" id="distrito" size="10" maxlength="40">
	</td>
	<td>RDR:
<?php
	$sql = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql);
	echo "<select name=\"rdr\" id=\"rdr\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\" >{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>
	</td>
	<td>Admin:
<?php
	$sql = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql);
	echo "<select name=\"admin\" id=\"admin\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>
	</td><td>
        <input type="submit" name="submit" id="submit" value="Agregar" />
      </form></td>
    </tr>
    <tr>
      <td colspan="3" align="center">    
      <p>&nbsp;</p>      </tr>
    <tr>
      <td colspan="3" align="center"><form action="index.php" method="post">
        <input type="submit" name="submit" id="submit" value="Volver" />
      </form></tr>
  </table>



<?php include 'footer.php';?>

