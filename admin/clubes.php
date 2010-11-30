<?php
//PROGRAMAR DOS CASOS, SI ES ADMIN Q PERMITA SELECCIONAR CUALQUIER DISTRITO, SI ES RDR O ADMIN DISTRITAL, SOLO PERMITA VER/MODIFICAR EL DISTRITO QUE LE CORRESPONDE

include 'header.php';

if ($nivel_distrito OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

$club['error']="";

if (isset($_GET['distrito'])){
	$distrito=intval($_GET['distrito']);
} else {
	$distrito=intval($_POST['distrito']);
}

if (!$nivel_admin AND $distrito!=$distrito_c AND $distrito!=0) {
	header("Location: index.php");
} else {
	if (!$nivel_admin) {
		$distrito = $distrito_c;
	}
}


$admin=mysql_real_escape_string(substr(htmlspecialchars($_POST['admin']),0,40));
$presidente=mysql_real_escape_string(substr(htmlspecialchars($_POST['presidente']),0,40));
$distrito=mysql_real_escape_string(htmlspecialchars($distrito));


$club['var']=substr(htmlspecialchars($_POST['clubb']),0,40);
$club['id']=substr(htmlspecialchars($_POST['idclubb']),0,4);
$consulta = mysql_real_escape_string($club['var']);
$consulta1 = mysql_real_escape_string($club['id']);
$consulta2 = mysql_real_escape_string($distrito);

if (isset($_POST['clubb'])&& $_POST['submit']=='Agregar') {
	$sql = sprintf("SELECT * FROM rtc_clubes WHERE " . "club = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$club['error']="El club ingresado ya existe";
	} else {
		$sql = sprintf("INSERT INTO rtc_clubes (id_club, id_distrito, club, uid_presidente, uid_admin) VALUES ('', '$consulta2', '$consulta','$presidente','$admin')");
		$result = mysql_query($sql);
		$club['error']="Se agregó ".$club['var']." al listado";
	}
} else if (isset($_POST['clubb'])&& $_POST['submit']=='Borrar') {
	$sql = sprintf("SELECT * FROM rtc_clubes WHERE " . "club = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$sql = sprintf("SELECT * FROM rtc_usuarios WHERE " . "club = \"$consulta1\" LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( $row ) {
			$club['error']="El club no puede ser borrado porque tiene ingresados socios";
		} else {
			$sql = sprintf("DELETE FROM rtc_clubes WHERE id_club='$consulta1' LIMIT 1");
			$result = mysql_query($sql);
			$club['error']="Se borró ".$club['var']." del listado";
		}
	} else {
		$club['error']="El club escrito no se encuentra en la lista";
	}
} else if (isset($_POST['clubb'])&& $_POST['submit']=='Modificar') {
	if ($nivel_admin) {
		$sql = sprintf("UPDATE rtc_clubes SET club='$consulta', uid_admin='$admin', uid_presidente='$presidente' WHERE id_club='$consulta1' LIMIT 1 ");
	} else {
		$sql = sprintf("UPDATE rtc_clubes SET club='$consulta', uid_presidente='$presidente' WHERE id_club='$consulta1' LIMIT 1 ");
	}
	$result = mysql_query($sql);
	if ( $result == false ) {
		$club['error']="No se pudo modificar";
	} else {
		$club['error']="Se modificó ".$club['var']." del listado";
	}
}

	if ($nivel_admin) {
		echo "
		<table>
		    <tr>
		      <td width=\"40\">&nbsp;</td>
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
		    </tr>
			<tr>
		      <td>&nbsp;</td>
		      <td><form id=\"form1\" name=\"form1\" method=\"get\" action=\"clubes.php\">
		        Id de Distrito:
		        <input name=\"distrito\" type=\"text\" id=\"distrito\" size=\"3\" maxlength=\"3\" />
		                        <input type=\"submit\" name=\"button\" id=\"button\" value=\"Enviar\" />
		      </form>
		      </td>
		      <td>&nbsp;</td>
		    </tr>
		    <tr>
		      <td>&nbsp;</td>
		      <td>
		";

		$sql = "SELECT * FROM rtc_distritos ORDER BY distrito";
		$result = mysql_query($sql);
		echo "<select name=\"distrito\" id=\"distrito\" onchange=\"location.href='clubes.php?distrito='+this.value\" >";
		echo "<option value=\"0\">Seleccione Distrito</option>";
		$sel='';
		while($row = mysql_fetch_assoc($result))
		{
			if ($row['id_distrito']==$distrito) { $sel = 'selected="selected"';} else {$sel = '';}
			echo "<option value=\"{$row['id_distrito']}\" {$sel} >{$row['distrito']}</option>";
		}
		echo "
				</td>
	      		<td>&nbsp;</td>
	    	</tr>
	  	</table>
		";
} //fin del if nivel_admin
?>
<table>
    <tr>
      <td width="40"><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left" colspan="3"><?php echo $club['error']; ?></td>
  </tr>
<?php

$sql = "SELECT * FROM rtc_clubes WHERE id_distrito = $distrito ORDER BY club";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
?>    
    <tr>
      <td>&nbsp;</td>
      <td> <?php echo $row['club']; ?>	  </td>
      <td align="left"> <form action="clubes.php" method="post">
          <input name="clubb" type="text" id="clubb" value="<?php echo $row['club'];  ?>" size="20" maxlength="80" />
          <input name="idclubb" id="idclubb" type="hidden" value="<?php echo $row['id_club'];  ?>" />
   	    <input type="hidden" name="distrito" id="distrito" value="<?php echo $distrito;  ?>" />
	</td>
	<td>P:
<?php
	$idclub=$row['id_club'];
	$sql1 = "SELECT * FROM rtc_usuarios WHERE club=$idclub";
	$resultado = mysql_query($sql1);
	echo "<select name=\"presidente\" id=\"presidente\">";
	echo "<option value=\"0\" selected > </option>";
	$sel='';
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($row['uid_presidente']==$rowtmp['uid']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['uid']}\" {$sel} >{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>	</td>
	<td>A:
<?php
	$sql1 = "SELECT * FROM rtc_usuarios WHERE club=$idclub";
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
?>	</td><td>
          <input type="submit" name="submit" id="submit" value="Modificar" />
          <input type="submit" name="submit" id="submit" value="Borrar" />
      </form></td>
    </tr>
<?php } ?> 
</table>
<?php 
	if ($distrito!='0') {
?>

  <table>
    <tr>
      <td width="40"><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Nombre:</td>
      <td align="left"><form action="clubes.php" method="post">
      	<input name="clubb" type="text" id="clubb" size="20" maxlength="80">
      	<input type="hidden" name="distrito" id="distrito" value="<?php echo $distrito;  ?>" />
	</td>
	<td>Presidente:
<?php
	$sql = "SELECT * FROM rtc_usuarios WHERE distrito=$distrito";
	$resultado = mysql_query($sql);
	echo "<select name=\"presidente\" id=\"presidente\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['uid']}\" >{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>
	</td>
	<td>Admin:
<?php
	$sql = "SELECT * FROM rtc_usuarios WHERE distrito=$distrito";
	$resultado = mysql_query($sql);
	echo "<select name=\"admin\" id=\"admin\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['uid']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
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
  </table>

<?php 
}

include 'footer.php';?>

