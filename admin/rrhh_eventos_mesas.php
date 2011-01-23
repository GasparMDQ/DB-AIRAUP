<?php
include 'header.php';

$esadmin=false;

if ($nivel_rrhh OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

if (isset($_POST['evento'])){
	$evento=intval($_POST['evento']);
} else if (isset($_GET['evento'])) {
		$evento=intval($_GET['evento']);
	} else {
		$evento=0;
}

if (isset($_POST['mesa'])){
	$mesa=intval($_POST['mesa']);
} else {
	$mesa=0;
}
// AGREGAR DE MESAS
if (isset($_POST['mesa_nombre']) && isset($_POST['coordinador_1']) && isset($_POST['coordinador_2']) && isset($_POST['coordinador_3']) && isset($_POST['coordinador_4']) && isset($_POST['coordinador_5']) && isset($_POST['coordinador_6']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Agregar" ) {
	$m_nombre = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa_nombre']),0,40));
	$m_c1 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_1']),0,10));
	$m_c2 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_2']),0,10));
	$m_c3 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_3']),0,10));
	$m_c4 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_4']),0,10));
	$m_c5 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_5']),0,10));
	$m_c6 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_6']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));
	
	$sql = "INSERT INTO rtc_eventos_mesa (mesa, evento_id, coord_1_id, coord_2_id, coord_3_id, coord_4_id, coord_5_id, coord_6_id) VALUES ('$m_nombre', '$m_ev', '$m_c1', '$m_c2', '$m_c3', '$m_c4', '$m_c5', '$m_c6')";
	$result = mysql_query($sql);
	$mesa=0;
}
// EDICION DE MESAS
if (isset($_POST['mesa_nombre']) && isset($_POST['coordinador_1']) && isset($_POST['coordinador_2']) && isset($_POST['coordinador_2']) && isset($_POST['coordinador_3']) && isset($_POST['coordinador_4']) && isset($_POST['coordinador_5']) && isset($_POST['coordinador_6'])  && isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Grabar" ) {
	$m_nombre = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa_nombre']),0,40));
	$m_c1 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_1']),0,10));
	$m_c2 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_2']),0,10));
	$m_c3 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_3']),0,10));
	$m_c4 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_4']),0,10));
	$m_c5 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_5']),0,10));
	$m_c6 = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador_6']),0,10));
	$m_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));
	
	$sql = "UPDATE rtc_eventos_mesa SET mesa='$m_nombre', coord_1_id='$m_c1', coord_2_id='$m_c2', coord_3_id='$m_c3', coord_4_id='$m_c4', coord_5_id='$m_c5', coord_6_id='$m_c6' WHERE id='$m_id' AND evento_id='$m_ev'";
	$result = mysql_query($sql);
	$mesa=0;
}
// BORRADO DE MESAS
if (isset($_POST['mesa_nombre']) && isset($_POST['coordinador_1']) && isset($_POST['coordinador_2']) && isset($_POST['coordinador_2']) && isset($_POST['coordinador_3']) && isset($_POST['coordinador_4']) && isset($_POST['coordinador_5']) && isset($_POST['coordinador_6']) && isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Borrar" ) {
	$m_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));

	$sql = "SELECT * FROM rtc_eventos_mesa_modulos WHERE mesa_id='$m_id' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	if ($row) {
		echo "<div class=\"muestra_alarma\">Imposible borrar porque la mesa tiene modulos ingresados</div>";
	} else {
		$sql = "SELECT * FROM rtc_eventos_inscripciones WHERE mesa_id='$m_id' AND evento_id='$m_ev' LIMIT 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		if ($row) {
			echo "<div class=\"muestra_alarma\">Imposible borrar porque la mesa tiene inscriptos</div>";
		} else {
			$sql = "DELETE FROM rtc_eventos_mesa WHERE id='$m_id' AND evento_id='$m_ev'";
			$result = mysql_query($sql);
			$mesa=0;
		}
	}
}

?>
<div><h2>Edicion de Mesas</h2></div>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">Seleccione un evento:
<?php
	$sql1 = "SELECT * FROM rtc_eventos ORDER BY nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"evento\" id=\"evento\" onchange=\"location.href='rrhh_eventos_mesas.php?evento='+this.value\" >";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['id']}\">{$rowtmp['nombre']}</option>";	
	}
	echo "</select>";
?>
</form>
</div>
<?php if ($evento!='0') {?>
<div>
<?php 
	$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	
	$evento_nombre=$row['nombre'];
?>
<h2>Encuentro: <?php echo $evento_nombre; ?></h2>

</div>
<div>
<h2>Mesas</h2>
<?php if (isset($_POST['button']) && $_POST['button']=="Nueva Mesa" && isset($_POST['evento'])) {?>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
Mesa: 
  <input name="mesa_nombre" type="text" id="mesa_nombre" value="" size="30" maxlength="40" />
  <br />
Coordinador: <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_1\" id=\"coordinador_1\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_2\" id=\"coordinador_2\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_3\" id=\"coordinador_3\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_4\" id=\"coordinador_4\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_5\" id=\"coordinador_5\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_6\" id=\"coordinador_6\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Agregar" />
  <input type="submit" name="button" id="button" value="Cancelar" />
</form>

</div>
<?php } // Final del IF AGREGA MESA?>
<?php 
	$sql="SELECT * FROM rtc_eventos_mesa WHERE evento_id='$evento' ORDER BY mesa";
	$result=mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$mesa_id=$row['id'];
		$mesa_nombre=$row['mesa'];
		$coordinador_1=$row['coord_1_id'];
		$coordinador_2=$row['coord_2_id'];
		$coordinador_3=$row['coord_3_id'];
		$coordinador_4=$row['coord_4_id'];
		$coordinador_5=$row['coord_5_id'];
		$coordinador_6=$row['coord_6_id'];
			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_1' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord1= $row_c['nombre']." ".$row_c['apellido'];

			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_2' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord2= $row_c['nombre']." ".$row_c['apellido'];

			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_3' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord3= $row_c['nombre']." ".$row_c['apellido'];

			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_4' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord4= $row_c['nombre']." ".$row_c['apellido'];

			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_5' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord5= $row_c['nombre']." ".$row_c['apellido'];

			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_6' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord6= $row_c['nombre']." ".$row_c['apellido'];

?>

<?php if ($mesa!=$mesa_id){ ?>
<p>
Mesa: <?php echo $mesa_nombre; ?><br />
Coordinador: <?php echo $coord1."(".$coordinador_1.")"; ?><br />
Coordinador: <?php echo $coord2."(".$coordinador_2.")"; ?><br />
Coordinador: <?php echo $coord3."(".$coordinador_3.")"; ?><br />
Coordinador: <?php echo $coord4."(".$coordinador_4.")"; ?><br />
Coordinador: <?php echo $coord5."(".$coordinador_5.")"; ?><br />
Coordinador: <?php echo $coord6."(".$coordinador_6.")"; ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Editar" />
</form>
</p>
<?php } else { // SI SELECCIONE EDITAR O BORRAR LA MESA ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
Mesa: 
  <input name="mesa_nombre" type="text" id="mesa_nombre" value="<?php echo $mesa_nombre; ?>" size="30" maxlength="40" />
  <br />
Coordinador: <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_1\" id=\"coordinador_1\">";
	echo "<option value=\"0\" selected > </option>";
	$sel='';
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($coordinador_1==$rowtmp['user_id']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['user_id']}\" {$sel} >{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_2\" id=\"coordinador_2\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($coordinador_2==$rowtmp['user_id']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_3\" id=\"coordinador_3\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($coordinador_3==$rowtmp['user_id']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_4\" id=\"coordinador_4\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($coordinador_4==$rowtmp['user_id']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_5\" id=\"coordinador_5\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($coordinador_5==$rowtmp['user_id']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
Coordinador:  <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador_6\" id=\"coordinador_6\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		if ($coordinador_6==$rowtmp['user_id']) { $sel = 'selected="selected"';} else {$sel = '';}
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Grabar" />
  <input type="submit" name="button" id="button" value="Borrar" />
</form>

<?php } // Final del IF MESA != MESA_ID ?>
<?php } // Final del WHILE ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Nueva Mesa" />
</form>

</div>
<?php } // Final del IF EVENTO != 0
include 'footer.php';?>

