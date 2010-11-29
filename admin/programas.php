<?php
include 'header.php';
$programa['error']="";

$programa['var']=substr(htmlspecialchars($_POST['programa']),0,40);
$programa['id']=substr(htmlspecialchars($_POST['idprograma']),0,4);
$consulta = mysql_real_escape_string($programa['var']);
$consulta1 = mysql_real_escape_string($programa['id']);

if (isset($_POST['programa'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_programas WHERE " . "programa = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$programa['error']="El programa ingresado ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_programas (id_programa, programa) VALUES ('', '$consulta')");
		$result = mysql_query($sql);
		$programa['error']="Se agregó ".$programa['var']." al listado";
	}
} else if (isset($_POST['programa'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_programas WHERE " . "programa = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
			$sql = sprintf("DELETE FROM rtc_programas WHERE id_programa='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$programa['error']="Se borró ".$programa['var']." del listado";
	} else {
		$pais['error']="El programa escrito no se encuentra en la lista";
	}
} else if (isset($_POST['programa'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_programas SET programa='$consulta' WHERE id_programa='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$programa['error']="No se pudo modificar";
	} else {
		$programa['error']="Se modificó ".$programa['var']." del listado";
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

$sql = "SELECT * FROM rtc_programas ORDER BY programa";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['programa']; ?>	  </td>
      <td align="left"><form action="programas.php" method="post">
          <input name="programa" type="text" id="programa" value="<?php echo $row['programa'];  ?>" size="30" maxlength="40" />
          <input name="idprograma" id="idprograma" type="hidden" value="<?php echo $row['id_programa'];  ?>" />
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
      <td align="left"><form action="programas.php" method="post">
      	<input name="programa" type="text" id="programa" size="30" maxlength="40">
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

