<?php
include 'header.php';
$cargo['error']="";

$cargo['var']=substr(htmlspecialchars($_POST['cargo']),0,80);
$cargo['id']=substr(htmlspecialchars($_POST['idcargo']),0,4);
$consulta = mysql_real_escape_string($cargo['var']);
$consulta1 = mysql_real_escape_string($cargo['id']);

if (isset($_POST['cargo'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_cargos WHERE " . "cargo = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$cargo['error']="El cargo ingresado ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_cargos (id, cargo) VALUES ('', '$consulta')");
		$result = mysql_query($sql);
		$cargo['error']="Se agregó ".$cargo['var']." al listado";
	}
} else if (isset($_POST['cargo'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_cargos WHERE " . "cargo = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) {
		$sql = sprintf("DELETE FROM rtc_cargos WHERE id='$consulta1' LIMIT 1");
		$result = mysql_query($sql);
		$cargo['error']="Se borró ".$cargo['var']." del listado";
	} else { 
		$cargo['error']="El cargo escrito no se encuentra en la lista";
	}
} else if (isset($_POST['cargo'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_cargos SET cargo='$consulta' WHERE id='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$cargo['error']="No se pudo modificar";
	} else {
		$cargo['error']="Se modificó ".$cargo['var']." del listado";
	}
}

	include 'main.php';
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
      <td align="left"><?php echo $cargo['error']; ?></td>
    </tr>
<?php
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

$sql = "SELECT * FROM rtc_cargos ORDER BY cargo";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['cargo']; ?>	  </td>
      <td align="left"><form action="cargos.php" method="post">
          <input name="cargo" type="text" id="cargo" value="<?php echo $row['cargo'];  ?>" size="30" maxlength="80" />
          <input name="idcargo" id="idcargo" type="hidden" value="<?php echo $row['id'];  ?>" />
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
      <td align="left"><form action="cargos.php" method="post">
      	<input name="cargo" type="text" id="cargo" size="30" maxlength="80">
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

