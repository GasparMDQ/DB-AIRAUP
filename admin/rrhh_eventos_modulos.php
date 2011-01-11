<?php
include 'header.php';

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

$esadmin=false;

if ($nivel_rrhh OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

if (isset($_POST['evento'])){
	$evento=intval($_POST['evento']);
} else {
	$evento=0;
}

if (isset($_POST['mesa'])){
	$mesa=intval($_POST['mesa']);
} else {
	$mesa=0;
}

if (isset($_POST['modulo'])){
	$modulo=intval($_POST['modulo']);
} else {
	$modulo=0;
}
// AGREGAR MODULOS
if (isset($_POST['modulo_nombre']) && isset($_POST['instructor_1']) && isset($_POST['instructor_2']) && isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Agregar" ) {
	$m_nombre = mysql_real_escape_string(substr(htmlspecialchars($_POST['modulo_nombre']),0,40));
	$m_i1 = mysql_real_escape_string(substr(htmlspecialchars($_POST['instructor_1']),0,60));
	$m_i2 = mysql_real_escape_string(substr(htmlspecialchars($_POST['instructor_2']),0,60));
	$m_me = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));
	
	$sql = "INSERT INTO rtc_eventos_mesa_modulos (modulo, instructor_1, instructor_2, mesa_id, evento_id) VALUES ('$m_nombre', '$m_i1', '$m_i2', '$m_me', '$m_ev')";
	$result = mysql_query($sql);
	$modulo=0;
}

// EDICION DE MESAS
if (isset($_POST['modulo_nombre']) && isset($_POST['instructor_1']) && isset($_POST['instructor_2']) && isset($_POST['modulo']) && isset($_POST['mesa']) && isset($_POST['evento'])  && isset($_POST['button']) && $_POST['button']=="Grabar" ) {
	$m_nombre = mysql_real_escape_string(substr(htmlspecialchars($_POST['modulo_nombre']),0,40));
	$m_i1 = mysql_real_escape_string(substr(htmlspecialchars($_POST['instructor_1']),0,60));
	$m_i2 = mysql_real_escape_string(substr(htmlspecialchars($_POST['instructor_2']),0,60));
	$m_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['modulo']),0,10));
	$m_me = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));
	
	$sql = "UPDATE rtc_eventos_mesa_modulos SET modulo='$m_nombre', instructor_1='$m_i1', instructor_2='$m_i2' WHERE id='$m_id' AND mesa_id='$m_me' AND evento_id='$m_ev'";
	$result = mysql_query($sql);
	$modulo=0;
}
// BORRADO DE MESAS
if (isset($_POST['modulo_nombre']) && isset($_POST['instructor_1']) && isset($_POST['instructor_2']) && isset($_POST['modulo']) && isset($_POST['mesa']) && isset($_POST['evento'])  && isset($_POST['button']) && $_POST['button']=="Borrar" ) {
	$m_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['modulo']),0,10));
	$m_me = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));

	$sql = "DELETE FROM rtc_eventos_mesa_modulos WHERE id='$m_id' AND mesa_id='$m_me' AND evento_id='$m_ev'";
	$result = mysql_query($sql);
	$modulo=0;
}

?>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
Seleccione una mesa:
<?php
	$sql1 = "SELECT * FROM rtc_eventos, rtc_eventos_mesa ORDER BY rtc_eventos.nombre, rtc_eventos_mesa.mesa";
	$resultado = mysql_query($sql1);
	echo "<select name=\"mesa\" id=\"mesa\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['id']}\">{$rowtmp['nombre']} - {$rowtmp['mesa']}</option>";	
	}
	echo "</select>";
?>
<input type="submit" name="button" id="button" value="Enviar" />
</form>
</div>
<?php if ($mesa!='0') {?>
<div>
<?php 
	$sql_a="SELECT * FROM rtc_eventos_mesa WHERE id='$mesa' LIMIT 1";
	$result_a=mysql_query($sql_a);
	$row_a=mysql_fetch_assoc($result_a);
	$evento=$row_a['evento_id'];
	$mesa_nombre=$row_a['mesa'];
	$coordinador_1=$row_a['coord_1_id'];
	$coordinador_2=$row_a['coord_2_id'];
		$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_1' LIMIT 1";
		$result_c=mysql_query($sql_c);
		$row_c = mysql_fetch_assoc($result_c);
		$coord1= $row_c['nombre']." ".$row_c['apellido'];

		$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_2' LIMIT 1";
		$result_c=mysql_query($sql_c);
		$row_c = mysql_fetch_assoc($result_c);
		$coord2= $row_c['nombre']." ".$row_c['apellido'];


	$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	
	$evento_nombre=$row['nombre'];
?>
<h2>Encuentro: <?php echo $evento_nombre; ?></h2>
<h3>Mesa: <?php echo $mesa_nombre; ?></h3>
Coordinador: <?php echo $coord1; ?><br />
Coordinador: <?php echo $coord2; ?>
</div>
<div>
<h2>Modulos</h2>
<?php if (isset($_POST['button']) && $_POST['button']=="Nuevo Modulo" && isset($_POST['evento'])) {?>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
Modulo: 
  <input name="modulo_nombre" type="text" id="modulo_nombre" value="" size="30" maxlength="40" />
  <br />
Instructor:  <input name="instructor_1" type="text" id="instructor_1" value="" size="30" maxlength="60" />
<br />
Instructor:   <input name="instructor_2" type="text" id="instructor_2" value="" size="30" maxlength="60" />
 <br />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input type="submit" name="button" id="button" value="Agregar" />
  <input type="submit" name="button" id="button" value="Cancelar" />
</form>

</div>
<?php } // Final del IF AGREGA MODULO?>
<?php 
	$sql="SELECT * FROM rtc_eventos_mesa_modulos WHERE mesa_id='$mesa' ORDER BY modulo";
	$result=mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$modulo_id=$row['id'];
		$modulo_nombre=$row['modulo'];
		$instructor_1=$row['instructor_1'];
		$instructor_2=$row['instructor_2'];
		$evento_id=$row['evento_id'];
?>

<?php if ($modulo!=$modulo_id){ ?>
<p>
Modulo: <?php echo $modulo_nombre; ?><br />
Instructor: <?php echo $instructor_1; ?><br />
Instructor: <?php echo $instructor_2; ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
  <input name="modulo" type="hidden" id="modulo" value="<?php echo $modulo_id; ?>" />
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Editar" />
</form>
</p>
<? } else { // SI SELECCIONE EDITAR O BORRAR EL MODULO ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
Modulo: 
  <input name="modulo_nombre" type="text" id="modulo_nombre" value="<?php echo $modulo_nombre; ?>" size="30" maxlength="40" />
  <br />
Instructor: <input name="instructor_1" type="text" id="instructor_1" value="<?php echo $instructor_1; ?>" size="30" maxlength="60" /><br />
Instructor: <input name="instructor_2" type="text" id="instructor_2" value="<?php echo $instructor_2; ?>" size="30" maxlength="60" /><br />
  <input name="modulo" type="hidden" id="modulo" value="<?php echo $modulo_id; ?>" />
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Grabar" />
  <input type="submit" name="button" id="button" value="Borrar" />
</form>

<?php } // Final del IF MODULO != MODULO_ID ?>
<?php } // Final del WHILE ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Nuevo Modulo" />
</form>

</div>
<?php } // Final del IF MESA != 0
include 'footer.php';?>

