<?php
include 'header.php';
$pais['error']="";

$pais['var']=substr(htmlspecialchars($_POST['pais']),0,40);
$pais['id']=substr(htmlspecialchars($_POST['idpais']),0,4);
$consulta = mysql_real_escape_string($pais['var']);
$consulta1 = mysql_real_escape_string($pais['id']);

if (isset($_POST['pais'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_paises WHERE " . "pais = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$pais['error']="El país ingresado ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_paises (id_paises, pais) VALUES ('', '$consulta')");
		$result = mysql_query($sql);
		$pais['error']="Se agregó ".$pais['var']." al listado";
	}
} else if (isset($_POST['pais'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_paises WHERE " . "pais = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$sql = sprintf("SELECT * FROM rtc_provincias WHERE " . "id_pais = \"$consulta1\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$pais['error']="El país no puede ser borrado porque tiene ingresadas provincias";
		} else {
			$sql = sprintf("DELETE FROM rtc_paises WHERE id_paises='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$pais['error']="Se borró ".$pais['var']." del listado";
		}
	} else {
		$pais['error']="El pais escrito no se encuentra en la lista";
	}
} else if (isset($_POST['pais'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_paises SET pais='$consulta' WHERE id_paises='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$pais['error']="No se pudo modificar";
	} else {
		$pais['error']="Se modificó ".$pais['var']." del listado";
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
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

$sql = "SELECT * FROM rtc_paises ORDER BY pais";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['pais']; ?>	  </td>
      <td align="left"><form action="paises.php" method="post">
          <input name="pais" type="text" id="pais" value="<?php echo $row['pais'];  ?>" size="30" maxlength="40" />
          <input name="idpais" id="idpais" type="hidden" value="<?php echo $row['id_paises'];  ?>" />
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
      <td>Nombre:</td>
      <td align="left"><form action="paises.php" method="post">
      	<input name="pais" type="text" id="pais" size="30" maxlength="40">
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

