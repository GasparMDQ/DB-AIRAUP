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
} else if (isset($_GET['mesa'])) {
		$mesa=intval($_GET['mesa']);
	} else {
		$mesa=0;
}

if (isset($_POST['modulo'])){
	$modulo=intval($_POST['modulo']);
} else {
	$modulo=0;
}

// AGREGAR ASISTENCIA
if (isset($_POST['user']) && isset($_POST['modulo']) && isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Asistio" ) {

	$uid = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10)));
	$mod = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['modulo']),0,10)));
	$mes = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['mesa']),0,10)));
	$eve = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));
	if (isset($_POST['notas'])) {
		$not = mysql_real_escape_string(substr(htmlspecialchars($_POST['notas']),0,256));
	} else {
		$not = "";
	}
	
	$sql = "INSERT INTO rtc_eventos_asistencia (evento_id, user_id, mesa_id, distrito_id, modulo_id, notas) VALUES ('$eve', '$uid', '$mes', '$dis', '$mod', '$not')";
	$result = mysql_query($sql);
}

// BORRAR ASISTENCIA
if (isset($_POST['user']) && isset($_POST['modulo']) && isset($_POST['mesa']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Borrar" ) {

	$uid = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10)));
	$mod = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['modulo']),0,10)));

	$sql = "DELETE FROM rtc_eventos_asistencia WHERE user_id='$uid' AND modulo_id='$mod'";
	$result = mysql_query($sql);
}



?> 
<div><h2>Sistema de Asistencia</h2></div>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_asistencia.php">
Seleccione una mesa:
<?php
	$sql1 = "SELECT * FROM rtc_eventos, rtc_eventos_mesa ORDER BY rtc_eventos.nombre, rtc_eventos_mesa.mesa";
	$resultado = mysql_query($sql1);
	echo "<select name=\"mesa\" id=\"mesa\" onchange=\"location.href='rrhh_eventos_asistencia.php?mesa='+this.value\" >";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['id']}\">{$rowtmp['nombre']} - {$rowtmp['mesa']}</option>";	
	}
	echo "</select>";
?>
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
<?php 
	$sql="SELECT * FROM rtc_eventos_mesa_modulos WHERE mesa_id='$mesa' ORDER BY modulo";
	$result=mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$modulo_id=$row['id'];
		$modulo_nombre=$row['modulo'];
		$instructor_1=$row['instructor_1'];
		$instructor_2=$row['instructor_2'];
		$evento_id=$row['evento_id'];
		if ($row['fin']) {
			$finaliza='Si';
		} else {
			$finaliza='No';
		}
?>

<?php if ($modulo!=$modulo_id){ ?>
<p>
Modulo: <?php echo $modulo_nombre; ?><br />
Finalizo: <?php echo $finaliza; ?>
<?php if (!$row['fin']) {?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_asistencia.php">
  <input name="modulo" type="hidden" id="modulo" value="<?php echo $modulo_id; ?>" />
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Cargar Asistencia" />
</form>
<?php } // Final de IF ROW FIN ?>
</p>
<? } else { // SI SELECCIONE CARGAR ASISTENCIA ?>
Modulo: <?php echo $modulo_nombre; ?><br />

<?php
	$sql_asis = "SELECT * FROM rtc_eventos_inscripciones, rtc_usr_personales WHERE rtc_eventos_inscripciones.user_id = rtc_usr_personales.user_id AND rtc_eventos_inscripciones.mesa_id = '$mesa' ORDER BY rtc_usr_personales.apellido, rtc_usr_personales.nombre";
	$resultado_asis = mysql_query($sql_asis);
	while ($row_asis = mysql_fetch_assoc($resultado_asis)) { ?>

<form id="form1" name="form1" method="POST" action="rrhh_eventos_asistencia.php">
	<?php echo $row_asis['nombre']." ".$row_asis['apellido']; ?>
	<input name="user" type="hidden" id="user" value="<?php echo $row_asis['user_id']; ?>" />
	<input name="modulo" type="hidden" id="modulo" value="<?php echo $modulo_id; ?>" />
	<input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
	<input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />

<?php
	$uid_asis = $row_asis['user_id'];
	$sql_verifica = "SELECT * FROM rtc_eventos_asistencia WHERE user_id = '$uid_asis' AND modulo_id = '$modulo_id' LIMIT 1";
	$resultado_verifica = mysql_query($sql_verifica);
	$row_verifica = mysql_fetch_assoc($resultado_verifica);
?>

<?php if ($row_verifica) {?>
	<input type="submit" name="button" id="button" value="Borrar" /><?php echo $row_verifica['notas']; ?>

<?php } else { ?>
	<input type="submit" name="button" id="button" value="Asistio" />
    <input name="notas" type="text" id="notas" size="40" maxlength="256" />
<?php } // Final del IF YA EXISTE LA ASISTENCIA ?>
</form>

<?php } // FINALIZA WHILE DE SOCIOS ?>

<form id="form1" name="form1" method="POST" action="rrhh_eventos_asistencia.php">
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input type="submit" name="button3" id="button3" value="Finalizar Carga" />
</form>

<?php } // Final del IF MODULO != MODULO_ID ?>
<?php } // Final del WHILE ?>
</div>
<?php } // Final del IF MESA != 0
include 'footer.php';?>

