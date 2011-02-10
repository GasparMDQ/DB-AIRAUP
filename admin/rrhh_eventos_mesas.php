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
if (isset($_POST['mesa_nombre']) && isset($_POST['coordinador']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Grabar" ) {
	$m_nombre = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa_nombre']),0,40));
	$m_c = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));
	
	$sql = "INSERT INTO rtc_eventos_mesa (mesa, evento_id) VALUES ('$m_nombre', '$m_ev')";
	$result = mysql_query($sql);
	$sql_tmp = "SELECT id FROM rtc_eventos_mesa WHERE evento_id='$m_ev' AND mesa='$m_nombre' LIMIT 1";
	$result_tmp = mysql_query($sql_tmp);
	$row_tmp = mysql_fetch_assoc($result_tmp);
	$mesa = $row_tmp['id'];
	if ($m_c <> 0) {
		$sql = "INSERT INTO rtc_eventos_mesa_coordinadores (mesa_id, user_id) VALUES ('$mesa','$m_c')";
		$result = mysql_query($sql);
	}
	$mesa=0;
}
// ACTUALIZACION DE MESAS  
if (isset($_POST['mesa_nombre']) && isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Actualizar" ) {
	$m_nombre = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa_nombre']),0,80));
	$m_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));
	
	$sql = "UPDATE rtc_eventos_mesa SET mesa='$m_nombre' WHERE id='$m_id' AND evento_id='$m_ev'";
	$result = mysql_query($sql);
	$mesa=0;
}

// AGREGADO DE COORDINADORES 
if (isset($_POST['coordinador']) && isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Agregar" ) {
	$m_coordinador = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador']),0,10));
	$mesa = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	
	$sql = "SELECT * FROM rtc_eventos_mesa_coordinadores WHERE mesa_id='$mesa' AND user_id='$m_coordinador' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	if ($row) {
		echo "<div class=\"muestra_alarma\">El coordinador ya esta inscripto en la mesa</div>";
	} else {
		$sql = "INSERT INTO rtc_eventos_mesa_coordinadores (mesa_id, user_id) VALUES ('$mesa','$m_coordinador')";
		$result = mysql_query($sql);
	}
}

// BORRADO DE COORDINADORES 
if (isset($_POST['coordinador']) && isset($_POST['mesa']) && isset($_POST['button']) && $_POST['button']=="Eliminar" ) {
	$m_coordinador = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador']),0,10));
	$mesa = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	
	$sql = "SELECT * FROM rtc_eventos_mesa_coordinadores WHERE mesa_id='$mesa' AND user_id='$m_coordinador' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	if ($row) {
		$sql = "DELETE FROM rtc_eventos_mesa_coordinadores WHERE mesa_id='$mesa' AND user_id='$m_coordinador'";
		$result = mysql_query($sql);
	} else {
		echo "<div class=\"muestra_alarma\">El coordinador no figura en la mesa</div>";
	}
}


// BORRADO DE MESAS
if (isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Borrar Mesa" ) {
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
			$sql = "DELETE FROM rtc_eventos_mesa_coordinadores WHERE mesa_id='$m_id'";
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
  <input name="mesa_nombre" type="text" id="mesa_nombre" value="" size="30" maxlength="80" />
  <br />
Coordinador: <?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY nombre, apellido";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador\" id=\"coordinador\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?> <br />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Grabar" />
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
?>

<?php if ($mesa!=$mesa_id){ ?>
<p>
Mesa: <?php echo $mesa_nombre; ?><br />
<?php 
			$sql_c="SELECT rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_usr_personales.user_id FROM rtc_usr_personales, rtc_eventos_mesa_coordinadores WHERE rtc_eventos_mesa_coordinadores.mesa_id='$mesa_id' AND rtc_usr_personales.user_id=rtc_eventos_mesa_coordinadores.user_id ORDER BY rtc_usr_personales.nombre, rtc_usr_personales.apellido";
			$result_c=mysql_query($sql_c);
			while($row_c = mysql_fetch_assoc($result_c)){
				echo "Coordinador: ".$row_c['nombre']." ".$row_c['apellido']." (".$row_c['user_id'].")<br />";
			}
?>

<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Editar" />
  <hr />
</form>
</p>
<?php } else { // SI SELECCIONE EDITAR O BORRAR LA MESA ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
Mesa: 
  <input name="mesa_nombre" type="text" id="mesa_nombre" value="<?php echo $mesa_nombre; ?>" size="30" maxlength="80" />  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" /><input type="submit" name="button" id="button" value="Actualizar" />
</form>
<?php 
			$sql_c="SELECT rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_usr_personales.user_id FROM rtc_usr_personales, rtc_eventos_mesa_coordinadores WHERE rtc_eventos_mesa_coordinadores.mesa_id='$mesa_id' AND rtc_usr_personales.user_id=rtc_eventos_mesa_coordinadores.user_id ORDER BY rtc_usr_personales.nombre, rtc_usr_personales.apellido";
			$result_c=mysql_query($sql_c);
			while($row_c = mysql_fetch_assoc($result_c)){ ?>

				<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
					<input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
					<input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
				    <input name="coordinador" type="hidden" id="coordinador" value="<?php echo $row_c['user_id']; ?>" />
<?php				echo "Coordinador: ".$row_c['nombre']." ".$row_c['apellido']." (".$row_c['user_id'].")"; ?>
					<input type="submit" name="button" id="button" value="Eliminar" />
					</form>
<?php
			}
?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
	<input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
    <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
	Coordinador: 
<?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY nombre, apellido";
	$resultado = mysql_query($sql1);
	echo "<select name=\"coordinador\" id=\"coordinador\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\">{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>
	<input type="submit" name="button" id="button" value="Agregar" />
</form>


<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">
	<input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
    <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
	<input type="submit" name="button" id="button" value="Borrar Mesa" />
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

