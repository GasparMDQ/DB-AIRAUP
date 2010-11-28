<?php
include 'header.php';
$provincia['error']="";

if (isset($_GET['provincia'])){
	$provincia=intval($_GET['provincia']);
} else {
	$provincia=intval($_POST['provincia']);
}
if (isset($_GET['pais'])){
	$pais=intval($_GET['pais']);
} else {
	$pais=intval($_POST['pais']);
}

$pais = mysql_real_escape_string(htmlspecialchars($pais));
$provincia = mysql_real_escape_string(htmlspecialchars($provincia));

if ($pais=='0' && $provincia != '0') {
	$sql = "SELECT * FROM rtc_provincias WHERE id_provincia = $provincia LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$pais = $row['id_pais'];
}

$ciudad['var']=substr(htmlspecialchars($_POST['ciudad']),0,40);
$ciudad['id']=substr(htmlspecialchars($_POST['idciudad']),0,4);
$codtel=substr(htmlspecialchars($_POST['cod_tel']),0,4);
$consulta = mysql_real_escape_string($ciudad['var']);
$consulta1 = mysql_real_escape_string($ciudad['id']);
$consulta2 = mysql_real_escape_string($provincia);
$consulta3 = mysql_real_escape_string($codtel);

if (isset($_POST['ciudad'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_ciudades WHERE ciudad = \"$consulta\" AND id_provincia = $consulta2 LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$ciudad['error']="La ciudad ingresada ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_ciudades (id_ciudades, id_provincia, ciudad, cod_tel) VALUES ('', '$consulta2', '$consulta','$consulta3')");
		$result = mysql_query($sql);
		$ciudad['error']="Se agregó ".$ciudad['var']." al listado";
	}
} else if (isset($_POST['ciudad'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_ciudades WHERE " . "ciudad = \"$consulta\" AND id_provincia = $consulta2 LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
			$sql = sprintf("DELETE FROM rtc_ciudades WHERE id_ciudades='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$ciudad['error']="Se borró ".$ciudad['var']." del listado";
	} else {
		$ciudad['error']="La ciudad escrita no se encuentra en la lista";
	}
} else if (isset($_POST['ciudad'])&& $_POST['submit']=='Modificar') {
	$sql = sprintf("UPDATE rtc_ciudades SET ciudad='$consulta', cod_tel='$consulta3' WHERE id_ciudades='$consulta1' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$ciudad['error']="No se pudo modificar";
	} else {
		$ciudad['error']="Se modificó ".$ciudad['var']." del listado";
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
      <td>&nbsp;</td>
      <td><form id="form1" name="form1" method="get" action="ciudades.php">
        Id de Provincia - Departamento - Estado:
            <input name="provincia" type="text" id="provincia" size="3" maxlength="3" />
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
	echo "<select name=\"pais\" id=\"pais\" onchange=\"location.href='ciudades.php?pais='+this.value\" >";
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
    <tr>
      <td>&nbsp;</td>
      <td>
<?php 
	$sql = "SELECT * FROM rtc_provincias WHERE id_pais = $pais ORDER BY provincia";
	$result = mysql_query($sql);
	echo "<select name=\"provincia\" id=\"provincia\" onchange=\"location.href='ciudades.php?provincia='+this.value\" >";
	echo "<option value=\"0\">Seleccione Provincia o Departamento</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_provincia']==$provincia) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_provincia']}\" {$sel} >{$row['provincia']}</option>";
	}
	?> </td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left"><?php echo $ciudad['error']; ?></td>
    </tr>
<?php

$sql = "SELECT * FROM rtc_ciudades WHERE id_provincia = $provincia ORDER BY ciudad";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['ciudad']; ?>	  </td>
      <td align="left"><form action="ciudades.php" method="post">
          <input name="ciudad" type="text" id="ciudad" value="<?php echo $row['ciudad'];  ?>" size="30" maxlength="40" />
          <input name="cod_tel" type="text" id="cod_tel" value="<?php echo $row['cod_tel'];  ?>" size="6" maxlength="5" />
          <input name="idciudad" id="idciudad" type="hidden" value="<?php echo $row['id_ciudades'];  ?>" />
      	<input type="hidden" name="provincia" id="provincia" value="<?php echo $provincia;  ?>" />
   	    <input type="hidden" name="pais" id="pais" value="<?php echo $pais;  ?>" />
          <input type="submit" name="submit" id="submit" value="Modificar" />
          <input type="submit" name="submit" id="submit" value="Borrar" />
      </form></td>
    </tr>
<?php } ?> 
</table>
<?php 
	if ($provincia!='0') {
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
      <td align="left"><form action="ciudades.php" method="post">
      	<input name="ciudad" type="text" id="ciudad" size="30" maxlength="40">
      	<input type="hidden" name="provincia" id="provincia" value="<?php echo $provincia;  ?>" />
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

