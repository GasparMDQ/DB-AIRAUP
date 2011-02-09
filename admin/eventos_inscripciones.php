<?php
include 'header.php';

$esadmin=false;

if ($nivel_evento OR $nivel_admin) {
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

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Alta Directa") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));


	$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_act' AND evento_id='$evento_act' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	if ($row) {
		echo "El usuario ya esta inscripto";
	} else {
		$sql="INSERT INTO rtc_eventos_inscripciones (mesa_id, user_id, evento_id) VALUES ('0','$user_act','$evento_act')";
		$result=mysql_query($sql);
//		echo mysql_error($result);
	}
} // FIN ALTAS DATOS

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Baja") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));


	$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_act' AND evento_id='$evento_act' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	if ($row) {
		$sql="DELETE FROM rtc_eventos_inscripciones WHERE user_id='$user_act' AND evento_id='$evento_act'";
		$result=mysql_query($sql);
	} else {
		echo "El usuario no estaba inscripto";
	}
} // FIN ALTAS DATOS


?>
<div>
  <h2>Inscriptos</h2>
</div>
<div>
<form id="form1" name="form1" method="POST" action="eventos_inscripciones.php">Seleccione un evento:
<?php
	$sql1 = "SELECT * FROM rtc_eventos ORDER BY nombre"; // MEJORAR LA BUSQUEDA PARA DEVOLVER NOMBRE DEL EVENTO, DISTRITO y CLUB
	$resultado = mysql_query($sql1);
	echo "<select name=\"evento\" id=\"evento\" onchange=\"location.href='eventos_inscripciones.php?evento='+this.value\" >";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['id']}\">{$rowtmp['nombre']}</option>";	
	}
	echo "</select>";
?>
</form>
</div>
<?php if ($evento!='0') {
	$sql="SELECT nombre FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
?>

<h2>Inscriptos a <?php echo $row['nombre'];?></h2>
<?php
		$sql_p = "SELECT rtc_eventos_inscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito, rtc_clubes.club FROM rtc_clubes, rtc_eventos_inscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_eventos_inscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_inscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.nombre, rtc_usr_personales.apellido";
		$result_p = mysql_query($sql_p);
		$cantidad_inscriptos = mysql_num_rows($result_p);
		echo "Total de Inscriptos: ".$cantidad_inscriptos." <br />";
		while($rows = mysql_fetch_assoc($result_p))
		{
			$user_id = mysql_real_escape_string($rows['user_id']); 
			$nombre = mysql_real_escape_string($rows['nombre'])." ".mysql_real_escape_string($rows['apellido']); 
			$distrito = mysql_real_escape_string($rows['distrito']); 
			$club = mysql_real_escape_string($rows['club']); 

			?><form id="form" name="form" method="POST" action="eventos_inscripciones.php"> 
  <input type="submit" name="button2" id="button2" value="Baja" />
  <?php 

			echo  "(".$distrito." | ".$club.") ".$nombre;
			?><input name="user" type="hidden" value="<?php echo $user_id;?>" /><input name="evento" type="hidden" value="<?php echo $evento;?>" />
</form>
<?php 
		}
?>

<form id="form" name="form" method="POST" action="eventos_inscripciones.php"> <?php 

	$sql1 = "SELECT rtc_usr_personales.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito FROM rtc_usr_personales, rtc_usr_institucional, rtc_distritos WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id  AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito ORDER BY rtc_distritos.distrito, rtc_usr_personales.nombre, rtc_usr_personales.apellido";
//	echo $sql1;
	$resultado = mysql_query($sql1);
	echo "<select name=\"user\" id=\"user\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\" >({$rowtmp['distrito']})- {$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";

			?><input name="evento" type="hidden" value="<?php echo $evento;?>" /><input type="submit" name="button" id="button" value="Alta Directa" />
			</form>

<?php } // Final del IF EVENTO != 0
include 'footer.php';?>

