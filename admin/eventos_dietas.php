<?php
include 'header.php';

$esadmin=false;

if ($nivel_evento OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

$user_id= $_SESSION['uid']; // ID DEL USUARIO QUE ACCEDE AL MENU

if (isset($_POST['evento'])){
	$evento=intval($_POST['evento']);
} else if (isset($_GET['evento'])) {
		$evento=intval($_GET['evento']);
	} else {
		$evento=0;
}


?>
<div>
  <h2>Dietas Especiales</h2>
</div>
<div>
<form id="form1" name="form1" method="POST" action="eventos_dietas.php">Seleccione un evento:
<?php
	if ($nivel_admin) {
		$sql1 = "SELECT rtc_eventos.nombre, rtc_eventos.id FROM rtc_eventos ORDER BY nombre"; // MEJORAR LA BUSQUEDA PARA DEVOLVER NOMBRE DEL EVENTO, DISTRITO y CLUB
	} else {
		$sql1 = "SELECT rtc_eventos.nombre, rtc_eventos.id FROM rtc_eventos, rtc_eventos_coordinadores WHERE rtc_eventos_coordinadores.evento_id=rtc_eventos.id AND rtc_eventos_coordinadores.user_id='$user_id' ORDER BY nombre"; // MEJORAR LA BUSQUEDA PARA DEVOLVER NOMBRE DEL EVENTO, DISTRITO y CLUB
	}
	$resultado = mysql_query($sql1);
	echo "<select name=\"evento\" id=\"evento\" onchange=\"location.href='eventos_dietas.php?evento='+this.value\" >";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['id']}\">{$rowtmp['nombre']}</option>";	
	}
	echo "</select>";
?>
</form>
</div>
<?php
	$sql="SELECT * FROM rtc_eventos_coordinadores WHERE evento_id='$evento' AND user_id='$user_id' LIMIT 1";
	$result = mysql_query($sql);
	$nivel_evento_id=false;
	if (mysql_num_rows($result) OR $nivel_admin) {
		$nivel_evento_id=true;
	}
	
	
	if ($evento!='0' AND $nivel_evento_id) { //VERIFICA QUE SEA ADMIN DE ESE EVENTO
	$sql="SELECT nombre FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	
?>

<h2>Asistentes a <?php echo $row['nombre'];?> con menu especial</h2>
<?php
		$sql_p = "SELECT rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_usr_salud.dietaesp, rtc_usr_salud.dietaespdesc, rtc_usr_salud.dietaveg, rtc_usr_salud.dietavegdesc, rtc_distritos.distrito, rtc_usr_personales.user_id FROM rtc_eventos_inscripciones, rtc_usr_personales, rtc_usr_salud, rtc_usr_institucional, rtc_distritos WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_usr_salud.user_id=rtc_eventos_inscripciones.user_id AND (rtc_usr_salud.dietaesp='1' OR rtc_usr_salud.dietaveg='1') AND rtc_usr_salud.user_id=rtc_usr_personales.user_id AND rtc_usr_salud.user_id=rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito ORDER BY rtc_distritos.distrito, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
		$result_p = mysql_query($sql_p);
		echo "Total de Especiales: ".mysql_num_rows($result_p)." <br />";
		while($rows = mysql_fetch_assoc($result_p))
		{
			$user_id = mysql_real_escape_string($rows['user_id']);
			$nombre = mysql_real_escape_string($rows['nombre'])." ".mysql_real_escape_string($rows['apellido']); 
			$distrito = mysql_real_escape_string($rows['distrito']); 

?>
			<p>
				<?php echo "<div class=\"texto_general\">".$nombre." (".$distrito.")</div>"; ?>
				Vegetariano: <?php if ($rows['dietaveg']) { echo "Si<br />"; } else { echo "No<br />";} ?>
				<?php if ($rows['dietaveg']) { echo "Detalle: ".$rows['dietavegdesc']."<br />"; } ?>
				Dieta Especial: <?php if ($rows['dietaesp']) { echo "Si<br />"; } else { echo "No<br />";} ?>
				<?php if ($rows['dietaesp']) { echo "Detalle: ".$rows['dietaespdesc']."<br />"; } ?>
			</p>
<?php			
		}
?>


<?php } // Final del IF EVENTO != 0
include 'footer.php';?>

