<?php
include 'header.php';

$periodo_error="";

$periodo=substr(htmlspecialchars($_POST['periodo']),0,40);
$periodo_id=substr(htmlspecialchars($_POST['idperiodo']),0,4);
$consulta = mysql_real_escape_string($periodo);
$consulta1 = mysql_real_escape_string($periodo_id);

if (isset($_POST['periodo'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_cfg_periodos WHERE " . "periodo = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$periodo_error="El per&iacute;odo ingresado ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_cfg_periodos (id_periodo, periodo) VALUES ('', '$consulta')");
		$result = mysql_query($sql);
		$periodo_error="Se agreg&oacute; ".$periodo." al listado";
	}
} else if (isset($_POST['periodo'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_cfg_periodos WHERE " . "periodo = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$sql = sprintf("SELECT * FROM rtc_usr_institucional_cargos WHERE " . "periodo = \"$consulta1\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$periodo_error="El per&iacute;odo no puede ser borrado porque hay socios con cargos en el mismo";
		} else {
			$sql = sprintf("DELETE FROM rtc_cfg_periodos WHERE id_periodo='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$periodo_error="Se borr&oacute; ".$periodo." del listado";
		}
	} else {
		$periodo_error="El per&iacute;odo escrito no se encuentra en la lista";
	}
} else if (isset($_POST['periodo'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_cfg_periodos SET periodo='$consulta' WHERE id_periodo='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$periodo_error="No se pudo modificar";
	} else {
		$periodo_error="Se modificó ".$periodo." del listado";
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
$sql = "SELECT * FROM rtc_cfg_periodos ORDER BY periodo";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['periodo']; ?>	  </td>
      <td align="left"><form action="periodos.php" method="post">
          <input name="periodo" type="text" id="periodo" value="<?php echo $row['periodo'];  ?>" size="12" maxlength="10" />
          <input name="idperiodo" id="idperiodo" type="hidden" value="<?php echo $row['id_periodo'];  ?>" />
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
      <td>A&ntilde;o:</td>
      <td align="left"><form action="periodos.php" method="post">
      	<input name="periodo" type="text" id="periodo" size="12" maxlength="10">
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

