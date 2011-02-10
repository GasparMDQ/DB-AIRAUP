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
	
	$sql = "UPDATE rtc_eventos_mesa_modulos SET modulo='$m_nombre', instructor_1='$m_i1', instructor_2='$m_i2', fin='0' WHERE id='$m_id' AND mesa_id='$m_me' AND evento_id='$m_ev'";
	$result = mysql_query($sql);
	$modulo=0;
}
// BORRADO DE MESAS
if (isset($_POST['modulo_nombre']) && isset($_POST['instructor_1']) && isset($_POST['instructor_2']) && isset($_POST['modulo']) && isset($_POST['mesa']) && isset($_POST['evento'])  && isset($_POST['button']) && $_POST['button']=="Borrar" ) {
	$m_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['modulo']),0,10));
	$m_me = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));

	$sql = "SELECT * FROM rtc_eventos_asistencia WHERE modulo_id='$m_id' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	if ($row) {
		echo "<div class=\"muestra_alarma\">Imposible borrar porque el modulo tiene asistencias ingresadas</div>";
	} else {
		$sql = "DELETE FROM rtc_eventos_mesa_modulos WHERE id='$m_id' AND mesa_id='$m_me' AND evento_id='$m_ev'";
		$result = mysql_query($sql);
		$modulo=0;
	}
}

// FINALIZADO DE MESAS
if (isset($_POST['modulo_nombre']) && isset($_POST['instructor_1']) && isset($_POST['instructor_2']) && isset($_POST['modulo']) && isset($_POST['mesa']) && isset($_POST['evento'])  && isset($_POST['button']) && $_POST['button']=="Finalizar Modulo" ) {
	$m_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['modulo']),0,10));
	$m_me = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa']),0,10));
	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));

	$sql = "UPDATE rtc_eventos_mesa_modulos SET fin='1' WHERE id='$m_id' AND mesa_id='$m_me' AND evento_id='$m_ev'";
	$result = mysql_query($sql);
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
	$sql_dis = "SELECT distrito FROM rtc_usr_institucional WHERE user_id='$uid' LIMIT 1";
	$result_dis = mysql_query($sql_dis);
	$row_dis = mysql_fetch_assoc($result_dis);
	$dis=$row_dis['distrito'];

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
<div><h2>Edicion de Modulos</h2></div>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
Seleccione una mesa:
<?php
	$sql1 = "SELECT * FROM rtc_eventos, rtc_eventos_mesa WHERE rtc_eventos_mesa.evento_id=rtc_eventos.id ORDER BY rtc_eventos.nombre, rtc_eventos_mesa.mesa";
	$resultado = mysql_query($sql1);
	echo "<select name=\"mesa\" id=\"mesa\" onchange=\"location.href='rrhh_eventos_modulos.php?mesa='+this.value\" >";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['id']}\">{$rowtmp['nombre']} - {$rowtmp['mesa']}</option>";	
	}
	echo "</select>";
?>
</form>
</div>
<?php if ($mesa!=0) {?>
<div>
<?php 
	$sql_a="SELECT * FROM rtc_eventos_mesa WHERE id='$mesa' LIMIT 1";
	$result_a=mysql_query($sql_a);
	$row_a=mysql_fetch_assoc($result_a);
	$evento=$row_a['evento_id'];
	$mesa_nombre=$row_a['mesa'];

	$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	
	$evento_nombre=$row['nombre'];
?>
<h2>Encuentro: <?php echo $evento_nombre; ?></h2>
<h3>Mesa: <?php echo $mesa_nombre; ?></h3>
<?php 
			$sql_c="SELECT rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_usr_personales.user_id FROM rtc_usr_personales, rtc_eventos_mesa_coordinadores WHERE rtc_eventos_mesa_coordinadores.mesa_id='$mesa' AND rtc_usr_personales.user_id=rtc_eventos_mesa_coordinadores.user_id ORDER BY rtc_usr_personales.nombre, rtc_usr_personales.apellido";
			$result_c=mysql_query($sql_c);
			while($row_c = mysql_fetch_assoc($result_c)){
				echo "Coordinador: ".$row_c['nombre']." ".$row_c['apellido']." (".$row_c['user_id'].")<br />";
			}
?>
</div>
<div>
<h2>Modulos</h2>
<?php if (isset($_POST['button']) && $_POST['button']=="Nuevo Modulo" && isset($_POST['evento'])) {?>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
	Modulo: <input name="modulo_nombre" type="text" id="modulo_nombre" value="" size="30" maxlength="40" /><br />
	Instructor: <input name="instructor_1" type="text" id="instructor_1" value="" size="30" maxlength="60" /><br />
	Instructor: <input name="instructor_2" type="text" id="instructor_2" value="" size="30" maxlength="60" /><br />
	<input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
	<input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
	<input type="submit" name="button" id="button" value="Agregar" />
	<input type="submit" name="button" id="button" value="Cancelar" />
</form>

</div>
<?php } // Final del IF AGREGA MODULO?>
<?php 
	$sql="SELECT * FROM rtc_eventos_mesa_modulos WHERE mesa_id='$mesa' ORDER BY fin, modulo";
	$result=mysql_query($sql);
?>
<h3>Cantidad de modulos: <?php echo mysql_num_rows($result); ?></h3>
<?php
	while($row = mysql_fetch_assoc($result)) {
		$modulo_id=$row['id'];
		$modulo_nombre=$row['modulo'];
		$instructor_1=$row['instructor_1'];
		$instructor_2=$row['instructor_2'];
		$evento_id=$row['evento_id'];
		if ($row['fin']) {
			$finaliza="<span class=\"muestra_verde\">Si</span>";
		} else {
			$finaliza="<span class=\"muestra_alarma\">No</span>";
		}


?>

<?php if ($modulo!=$modulo_id){ ?>
<p>
Modulo: <?php echo $modulo_nombre; ?><br />
Instructor: <?php echo $instructor_1; ?><br />
Instructor: <?php echo $instructor_2; ?><br />
Finalizo: <?php echo $finaliza; ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
  <input name="modulo" type="hidden" id="modulo" value="<?php echo $modulo_id; ?>" />
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Editar / Cargar Asistencia" />
</form>

</p>
<?php } else { // SI SELECCIONE EDITAR O BORRAR EL MODULO ?>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
Modulo (<?php echo $modulo_id;?> ): 
  <input name="modulo_nombre" type="text" id="modulo_nombre" value="<?php echo $modulo_nombre; ?>" size="30" maxlength="40" />
  <br />
Instructor: <input name="instructor_1" type="text" id="instructor_1" value="<?php echo $instructor_1; ?>" size="30" maxlength="60" /><br />
Instructor: <input name="instructor_2" type="text" id="instructor_2" value="<?php echo $instructor_2; ?>" size="30" maxlength="60" /><br />
  <input name="modulo" type="hidden" id="modulo" value="<?php echo $modulo_id; ?>" />
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Grabar" />
  <input type="submit" name="button" id="button" value="Finalizar Modulo" />
  <input type="submit" name="button" id="button" value="Borrar" />
</form>

<?php
	$sql_asis = "SELECT rtc_usr_personales.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito FROM rtc_eventos_inscripciones, rtc_usr_personales, rtc_usr_institucional, rtc_distritos WHERE rtc_eventos_inscripciones.user_id = rtc_usr_personales.user_id AND rtc_eventos_inscripciones.mesa_id = '$mesa' AND rtc_usr_personales.user_id=rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito ORDER BY rtc_distritos.distrito, rtc_usr_personales.nombre, rtc_usr_personales.apellido";
	$resultado_asis = mysql_query($sql_asis);
	while ($row_asis = mysql_fetch_assoc($resultado_asis)) { ?>

<form id="form1" name="form1" method="POST" action="rrhh_eventos_modulos.php">
	<?php echo "(".$row_asis['distrito'].") ".$row_asis['nombre']." ".$row_asis['apellido']; ?>
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

