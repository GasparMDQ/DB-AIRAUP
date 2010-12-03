<?php
//PROGRAMAR DOS CASOS, SI ES ADMIN Q PERMITA SELECCIONAR CUALQUIER DISTRITO, SI ES RDR O ADMIN DISTRITAL, SOLO PERMITA VER/MODIFICAR EL DISTRITO QUE LE CORRESPONDE

include 'header.php';

if ($nivel_club OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

$club_error="";

if (isset($_GET['club'])){
	$club_id=intval($_GET['club']);
} else {
	$club_id=intval($_POST['club']);
}

if (!$nivel_admin AND $club_id!=$club_c AND $club!=0) {
	header("Location: index.php");
} else {
	if (!$nivel_admin) {
		$club_id = $club_c;
	}
}
//HASTA ACA EDITE

$club_admin=mysql_real_escape_string(substr(htmlspecialchars($_POST['admin']),0,40));
$club_ciudad=mysql_real_escape_string(substr(htmlspecialchars($_POST['ciudad']),0,40));
$club_direccion=mysql_real_escape_string(substr(htmlspecialchars($_POST['direccion']),0,40));
$club_email=mysql_real_escape_string(substr(htmlspecialchars($_POST['email']),0,40));
$club_nombre=mysql_real_escape_string(substr(htmlspecialchars($_POST['nombre']),0,40));
$club_socio=mysql_real_escape_string(substr(htmlspecialchars($_POST['suid']),0,40));

//METODOS PARA BORRAR SOCIO, CAMBIAR ADMIN, MODIFICAR INFORMACION (EMAIL, DIRECCION, CIUDAD)


if (isset($_POST['submit']) AND ($_POST['submit']=='Dar de Baja')) {
	$sql = sprintf("UPDATE rtc_usuarios SET club='0', distrito='0' WHERE uid='$club_socio' LIMIT 1 ");
	$result = mysql_query($sql);
	if ($result == false) {
		$club_error = "El socio no pudo ser dado de baja";
	} else {
		$sql = sprintf("SELECT * FROM rtc_usuarios WHERE uid='$club_socio' LIMIT 1 ");
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$club_error = "Se dio de baja a ".$row['nombre']." ".$row['apellido'];
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
	      <td><form id=\"form1\" name=\"form1\" method=\"get\" action=\"socios.php\">
	        Id de Club:
	        <input name=\"club\" type=\"text\" id=\"club\" size=\"3\" maxlength=\"3\" />
			<input type=\"submit\" name=\"button\" id=\"button\" value=\"Enviar\" />
	      </form>
	      </td>
	      <td>&nbsp;</td>
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td>
		";

	$sql = "SELECT * FROM rtc_clubes ORDER BY club";
	$result = mysql_query($sql);
	echo "<select name=\"club\" id=\"club\" onchange=\"location.href='socios.php?club='+this.value\" >";
	echo "<option value=\"0\">Seleccione Club</option>";
	$sel='';
	while($row = mysql_fetch_assoc($result))
	{
		if ($row['id_club']==$club_id) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$row['id_club']}\" {$sel} >{$row['club']}</option>";
	}
	echo "
			</td>
      		<td>&nbsp;</td>
    	</tr>
  	</table>
	";
} //fin del if nivel_admin

if ($club_id!=0) {

	$sql_club = "SELECT * FROM rtc_clubes WHERE id_club=$club_id LIMIT 1";
	$result_club = mysql_query($sql_club);
	$row_club = mysql_fetch_assoc($result_club);
	
	$sql = "SELECT * FROM rtc_usuarios WHERE club = $club_id ORDER BY apellido, nombre";
	$result = mysql_query($sql);
	
	echo "<h2>Rotaract Club ".$row_club['club']."</h2>";
	echo "Miembros: ".mysql_num_rows($result);
?>
<table>
    <tr>
      <td width="40"><p>&nbsp;</p></td>
      <td>&nbsp;</td>
      <td align="left" colspan="3"><?php echo $club_error; ?></td>
  </tr>
<?php

while($row = mysql_fetch_assoc($result))
{
?>    
	<tr>
		<td>&nbsp;</td>
		<td> <?php echo $row['nombre']." ".$row['apellido']; ?>	  </td>
		<td align="left">
        	<form action="socios.php" method="post">
			<input name="suid" id="suid" type="hidden" value="<?php echo $row['uid'];  ?>" />
		</td>
		<td>
			<input type="submit" name="submit" id="submit" value="Dar de Baja" />
			</form>
		</td>
	</tr>
<?php } ?> 
</table>

<?php 
} //FINAL DEL IF CLUB_ID!=0
include 'footer.php';?>

