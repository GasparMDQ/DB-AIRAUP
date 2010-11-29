<?php
include 'header.php';
$provincia['error']="";

if (isset($_GET['pais'])){
	$pais=intval($_GET['pais']);
} else {
	$pais=intval($_POST['pais']);
}

$pais = mysql_real_escape_string(htmlspecialchars($pais));

$provincia['var']=substr(htmlspecialchars($_POST['provincia']),0,40);
$provincia['id']=substr(htmlspecialchars($_POST['idprovincia']),0,4);
$consulta = mysql_real_escape_string($provincia['var']);
$consulta1 = mysql_real_escape_string($provincia['id']);
$consulta2 = mysql_real_escape_string($pais);

if (isset($_POST['provincia'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_provincias WHERE " . "provincia = \"$consulta\" AND id_pais='$consulta2' LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$provincia['error']="La provincia ingresada ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_provincias (id_provincia, id_pais, provincia) VALUES ('', '$consulta2', '$consulta')");
		$result = mysql_query($sql);
		$provincia['error']="Se agregó ".$provincia['var']." al listado";
	}
} else if (isset($_POST['provincia'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_provincias WHERE " . "provincia = \"$consulta\" AND id_pais='$consulta2' LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$sql = sprintf("SELECT * FROM rtc_ciudades WHERE id_provincia = '$consulta1' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$provincia['error']="La provincia no puede ser borrada porque tiene ingresadas ciudades";
		} else {
			$sql = sprintf("DELETE FROM rtc_provincias WHERE id_provincia='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$provincia['error']="Se borró ".$provincia['var']." del listado";
		}
	} else {
		$provincia['error']="La provincia escrita no se encuentra en la lista";
	}
} else if (isset($_POST['provincia'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_provincias SET provincia='$consulta' WHERE id_provincia='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$provincia['error']="No se pudo modificar";
	} else {
		$provincia['error']="Se modificó ".$provincia['var']." del listado";
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
      <td>&nbsp;</td>
      <td><form id="form1" name="form1" method="get" action="provincias.php">
        Id de Pais:
        <input name="pais" type="text" id="pais" size="3" maxlength="3" />
                        <input type="submit" name="button" id="button" value="Enviar" />
      </form>
      </td>
      
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
<?php 
	require_once '/home/gasparmdq/configDB/configuracion.php';
	require_once 'includes/abredb.php';

	$sql = "SELECT * FROM rtc_paises ORDER BY pais";
	$result = mysql_query($sql);
	echo "<select name=\"pais\" id=\"pais\" onchange=\"location.href='provincias.php?pais='+this.value\" >";
	echo "<option value=\"0\">Seleccione Pais</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_paises']==$pais) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_paises']}\" {$sel} >{$row['pais']}</option>";
	}
	?> </td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left"><?php echo $provincia['error']; ?></td>
    </tr>
<?php

$sql = "SELECT * FROM rtc_provincias WHERE id_pais = $pais ORDER BY provincia";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['provincia']; ?>	  </td>
      <td align="left"><form action="provincias.php" method="post">
          <input name="provincia" type="text" id="provincia" value="<?php echo $row['provincia'];  ?>" size="30" maxlength="40" />
          <input name="idprovincia" id="idprovincia" type="hidden" value="<?php echo $row['id_provincia'];  ?>" />
   	    <input type="hidden" name="pais" id="pais" value="<?php echo $pais;  ?>" />
          <input type="submit" name="submit" id="submit" value="Modificar" />
          <input type="submit" name="submit" id="submit" value="Borrar" />
      </form></td>
    </tr>
<?php } ?> 
</table>
<?php 
	if ($pais!='0') {
?>

  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Nombre:</td>
      <td align="left"><form action="provincias.php" method="post">
      	<input name="provincia" type="text" id="provincia" size="30" maxlength="40">
      	<input type="hidden" name="pais" id="pais" value="<?php echo $pais;  ?>" />
      	<input type="submit" name="submit" id="submit" value="Agregar" />
      </form></td>
    </tr>
    <tr>
      <td colspan="3" align="center">    
      <p>&nbsp;</p>      </tr>
  </table>

<?php 
}
?>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3" align="center"><form action="index.php" method="post">
        <input type="submit" name="submit" id="submit" value="Volver" />
      </form></tr>
  </table>

<?php include 'footer.php';?>

