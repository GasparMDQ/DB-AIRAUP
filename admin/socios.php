<?php
//PROGRAMAR DOS CASOS, SI ES ADMIN Q PERMITA SELECCIONAR CUALQUIER DISTRITO, SI ES RDR O ADMIN DISTRITAL, SOLO PERMITA VER/MODIFICAR EL DISTRITO QUE LE CORRESPONDE

include 'header.php';
$esadmin=false;

if ($nivel_club OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] OR !$esadmin) {
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

$club_admin=mysql_real_escape_string(substr(htmlspecialchars($_POST['admin']),0,40));
$club_ciudad=mysql_real_escape_string(substr(htmlspecialchars($_POST['ciudad']),0,40));
$club_direccion=mysql_real_escape_string(substr(htmlspecialchars($_POST['direccion']),0,60));
$club_email=mysql_real_escape_string(substr(htmlspecialchars($_POST['email']),0,40));
$club_url=mysql_real_escape_string(substr(htmlspecialchars($_POST['url']),0,40));
$club_nombre=mysql_real_escape_string(substr(htmlspecialchars($_POST['nombre']),0,40));
$club_socio=mysql_real_escape_string(substr(htmlspecialchars($_POST['suid']),0,40));

//METODOS PARA BORRAR SOCIO, CAMBIAR ADMIN, MODIFICAR INFORMACION (EMAIL, DIRECCION, CIUDAD)

if (isset($_POST['submit']) AND ($_POST['submit']=='Dar de Baja')) {
	$sql = sprintf("UPDATE rtc_usr_institucional SET club='0', distrito='0' WHERE uid='$club_socio' LIMIT 1 ");
	$result = mysql_query($sql);
	if ($result == false) {
		$club_error = "El socio no pudo ser dado de baja";
	} else {
		$sql = sprintf("SELECT * FROM rtc_usr_personales WHERE uid='$club_socio' LIMIT 1 ");
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$club_error = "Se dio de baja a ".$row['nombre']." ".$row['apellido'];
	}
}

if (isset($_POST['submit']) AND ($_POST['submit']=='Modifica Datos')) {
	$sql = sprintf("UPDATE rtc_clubes SET club='$club_nombre', email='$club_email', url='$club_url', direccion='$club_direccion', uid_admin='$club_admin', id_ciudad='$club_ciudad'  WHERE id_club='$club_id' LIMIT 1 ");
	$result = mysql_query($sql);
	if ($result == false) {
		$club_error = "Los datos del club no pudieron ser modificados";
	} else {
		$club_error = "Los datos del club fueron modificados correctamente";
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
	      <td><form id=\"form1\" name=\"form1\" method=\"post\" action=\"socios.php\">
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


$sql_club = "SELECT * FROM rtc_clubes WHERE id_club=$club_id LIMIT 1";
$result_club = mysql_query($sql_club);
$row_club = mysql_fetch_assoc($result_club);
	
$sql = "SELECT * FROM rtc_usr_personales, rtc_usr_institucional WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id AND rtc_usr_institucional.club = $club_id ORDER BY apellido, nombre";
$result = mysql_query($sql);
	
if ($club_id!=0) {
	echo "<h2>Rotaract Club ".$row_club['club']."</h2>";
} else {
	echo "<h2>Miembros dados de baja</h2>";
}
?>
<div class="muestra_alarma"><?php echo $club_error; ?></div>
<?php
// DATOS DEL CLUB
// ACCESIBLE PARA:
//		-Presidente
//		-Administrador Club
//		-Administrador Sitio

if ($club_id!=0) {
?>
<form action="socios.php" method="post">
	<div class="tabla_ppl">
		<div class="tabla_izquierda">Nombre del Club:</div>
		<div class="tabla_derecha"><input class="texto" type="text" name="nombre" id="nombre" value="<?php echo $row_club['club']; ?>" />
		</div>
	</div> <!-- Final de tabla -->
	<div class="tabla_ppl">
		<div class="tabla_izquierda">Email:</div>
		<div class="tabla_derecha"><input name="email" type="text" class="texto" id="email" value="<?php echo $row_club['email']; ?>" maxlength="40" />
		</div>
	</div> <!-- Final de tabla -->
	<div class="tabla_ppl">
		<div class="tabla_izquierda">Direcci&oacute;n Web</div>
	    <div class="tabla_derecha"><input name="url" type="text" class="texto" id="url" value="<?php echo $row_club['url']; ?>" maxlength="40" />
	    </div>
	</div> <!-- Final de tabla -->
	<div class="tabla_ppl">
		<div class="tabla_izquierda">Ciudad</div>
		<div class="tabla_derecha">
   			<?php
				$sql_ciudad = "SELECT * FROM rtc_ciudades ORDER BY ciudad";
				$resultado_ciudad = mysql_query($sql_ciudad);
				echo "<select name=\"ciudad\" id=\"ciudad\">";
				echo "<option value=\"0\" selected > </option>";
				$sel='';
				while ($row_ciudad = mysql_fetch_assoc($resultado_ciudad))
				{
					$prov_temp = $row_ciudad['id_provincia'];
					$sql_prov = "SELECT * FROM rtc_provincias WHERE id_provincia='$prov_temp' ORDER BY provincia LIMIT 1";
					$resultado_prov = mysql_query($sql_prov);
					$row_prov = mysql_fetch_assoc($resultado_prov);
					if ($row_club['id_ciudad']==$row_ciudad['id_ciudades']) { $sel = 'selected="selected"';} else {$sel = '';}
					echo "<option value=\"{$row_ciudad['id_ciudades']}\" {$sel} >{$row_ciudad['ciudad']} - {$row_prov['provincia']}</option>";	
				}
				echo "</select>";
			?>
		</div>
	</div> <!-- Final de tabla -->
	<div class="tabla_ppl">
		<div class="tabla_izquierda">Direcci&oacute;n:</div>
		<div class="tabla_derecha"><input name="direccion" type="text" class="texto" id="direccion" value="<?php echo $row_club['direccion']; ?>" maxlength="60" />
		</div>
	</div> <!-- Final de tabla -->
	<?php if ($nivel_club_presidente OR $nivel_admin) { ?>
		<div class="tabla_ppl">
			<div class="tabla_izquierda">Administrador:</div>
			<div class="tabla_derecha">
			<?php
				$sql_admin = "SELECT * FROM rtc_usr_personales, rtc_usr_institucional WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id AND rtc_usr_institucional.club = $club_id ORDER BY apellido, nombre";
				$resultado_admin = mysql_query($sql_admin);
				echo "<select name=\"admin\" id=\"admin\">";
				echo "<option value=\"0\" selected > </option>";
				$sel='';
				while ($row_admin = mysql_fetch_assoc($resultado_admin))
				{
					if ($row_club['uid_admin']==$row_admin['uid']) { $sel = 'selected="selected"';} else {$sel = '';}
					echo "<option value=\"{$row_admin['uid']}\" {$sel} >{$row_admin['nombre']} {$row_admin['apellido']}</option>";	
				}
				echo "</select>";
			?>
			</div>
		</div> <!-- Final de tabla -->
	<?php } else { ?>
		<input name="admin" type="hidden" id="admin" value="<?php echo $row_club['uid_admin']; ?>" />
	<?php } ?>

<div class="tabla_ppl">
		<div class="tabla_izquierda"><input type="submit" name="submit" id="submit" value="Modifica Datos" /></div>
		<div class="tabla_derecha"><input name="club" type="hidden" id="club" value="<?php echo $club_id; ?>" /></div>
	</div> <!-- Final de tabla -->
</form>
<?php } ?>


<?php
// GENERO EL LISTADO DE SOCIOS DEL CLUB CON LA OPCION DE DAR DE BAJA
// ACCESIBLE PARA:
//		-Presidente
//		-Administrador Club
//		-Administrador Sitio
?>

<div class="tabla_ppl"><h2>Cantidad de miembros: <?php echo mysql_num_rows($result); ?></h2></div>

<?php while($row = mysql_fetch_assoc($result))
{ ?>    
<div class="tabla_ppl">
	<form action="socios.php" method="post">
		<div class="tabla_izquierda"><?php echo $row['nombre']." ".$row['apellido']; ?></div>
		<div class="tabla_derecha"><input name="club" type="hidden" id="club" value="<?php echo $club_id; ?>" /><input name="suid" id="suid" type="hidden" value="<?php echo $row['user_id'];  ?>" /><input type="submit" name="submit" id="submit" value="Dar de Baja" /></div>
	</form>
</div> <!-- Final de tabla -->
<?php } ?> 

<?php 
include 'footer.php';?>

