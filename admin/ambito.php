<?php
include 'header.php';

$ambito_error="";

$ambito=substr(htmlspecialchars($_POST['ambito']),0,40);
$ambito_id=substr(htmlspecialchars($_POST['idambito']),0,4);
$consulta = mysql_real_escape_string($ambito);
$consulta1 = mysql_real_escape_string($ambito_id);

if (isset($_POST['ambito'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_cfg_ambito WHERE " . "ambito = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$ambito_error="El &aacute;mbito ingresado ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_cfg_ambito (id_ambito, ambito) VALUES ('', '$consulta')");
		$result = mysql_query($sql);
		$ambito_error="Se agreg&oacute; ".$ambito." al listado";
	}
} else if (isset($_POST['ambito'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_cfg_ambito WHERE " . "ambito = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$sql = sprintf("SELECT * FROM rtc_usr_institucional_cargos WHERE " . "ambito = \"$consulta1\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$ambito_error="El &aacute;mbito no puede ser borrado porque hay socios con cargos en el mismo";
		} else {
			$sql = sprintf("DELETE FROM rtc_cfg_ambito WHERE id_ambito='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$ambito_error="Se borr&oacute; ".$ambito." del listado";
		}
	} else {
		$ambito_error="El &aacute;mbito escrito no se encuentra en la lista";
	}
} else if (isset($_POST['ambito'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_cfg_ambito SET ambito='$consulta' WHERE id_ambito='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$ambito_error="No se pudo modificar";
	} else {
		$ambito_error="Se modificó ".$ambito." del listado";
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
      <td align="left"><?php echo $pais['error']; ?></td>
    </tr>
<?php
$sql = "SELECT * FROM rtc_cfg_ambito ORDER BY ambito";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['ambito']; ?>	  </td>
      <td align="left"><form action="ambito.php" method="post">
          <input name="ambito" type="text" id="ambito" value="<?php echo $row['ambito'];  ?>" size="30" maxlength="40" />
          <input name="idambito" id="idambito" type="hidden" value="<?php echo $row['id_ambito'];  ?>" />
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
      <td>Ambito:</td>
      <td align="left"><form action="ambito.php" method="post">
      	<input name="ambito" type="text" id="ambito" size="30" maxlength="40">
        <input type="submit" name="submit" id="submit" value="Agregar" />
      </form></td>
    </tr>
    <tr>
      <td colspan="3" align="center">    
        <p>&nbsp;</p>
      </tr>
    <tr>
      <td colspan="3" align="center"><form action="index.php" method="post">
        <input type="submit" name="submit" id="submit" value="Volver" />
      </form></tr>
  </table>



<?php include 'footer.php';?>

