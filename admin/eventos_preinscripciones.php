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

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Confirmar Inscripcion") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));


	$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_act' AND evento_id='$evento_act' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	if ($row) {
		echo "El usuario ya esta inscripto";
		$sql = "DELETE FROM rtc_eventos_preinscripciones WHERE evento_id='$evento_act' AND user_id='$user_act'";
		$result = mysql_query($sql);
	} else {

		$sql="SELECT id FROM rtc_eventos_preinscripciones WHERE evento_id='$evento_act' AND user_id='$user_act' AND ok_club='1' AND ok_distrito='1' AND ok_tesoreria='1'";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) {
		//VERIFICAR LOS OK DE LA PREINSCRIPCION
			$notas= "";
			$sql = "SELECT notas FROM rtc_eventos_preinscripciones WHERE user_id = '$user_id' AND evento_id = '$evento' LIMIT 1";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$notas = $notas.$row['notas'];
			
			$sql="INSERT INTO rtc_eventos_inscripciones (user_id, evento_id, notas) VALUES ('$user_act','$evento_act','$notas')";
			if (mysql_query($sql)) {
					$sql = "DELETE FROM rtc_eventos_preinscripciones WHERE evento_id='$evento_act' AND user_id='$user_act'";
					$result = mysql_query($sql);
			} else {
				echo "No se pudo inscribir al socio";
			}
		}		
	}
} // FIN ALTAS DATOS



?>
<div>
  <h2>Preinscripciones</h2>
</div>
<div>
<form id="form1" name="form1" method="POST" action="eventos_preinscripciones.php">Seleccione un evento:
<?php
	if ($nivel_admin) {
		$sql1 = "SELECT rtc_eventos.nombre, rtc_eventos.id FROM rtc_eventos ORDER BY nombre"; // MEJORAR LA BUSQUEDA PARA DEVOLVER NOMBRE DEL EVENTO, DISTRITO y CLUB
	} else {
		$sql1 = "SELECT rtc_eventos.nombre, rtc_eventos.id FROM rtc_eventos, rtc_eventos_coordinadores WHERE rtc_eventos_coordinadores.evento_id=rtc_eventos.id AND rtc_eventos_coordinadores.user_id='$user_id' ORDER BY nombre"; // MEJORAR LA BUSQUEDA PARA DEVOLVER NOMBRE DEL EVENTO, DISTRITO y CLUB
	}
	$resultado = mysql_query($sql1);
	echo "<select name=\"evento\" id=\"evento\" onchange=\"location.href='eventos_preinscripciones.php?evento='+this.value\" >";
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

<h2>Estado de Preinscripciones a <?php echo $row['nombre'];?></h2>
<?php
		$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria, rtc_usr_login.email FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales, rtc_usr_login WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club AND rtc_eventos_preinscripciones.user_id = rtc_usr_login.uid ORDER BY rtc_distritos.distrito, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_clubes.club";
		$result_p = mysql_query($sql_p);
		$cantidad_inscriptos = mysql_num_rows($result_p);
		echo "Total de Preinscriptos: ".$cantidad_inscriptos." <br />";
		while($rows = mysql_fetch_assoc($result_p))
		{
			$user_id = mysql_real_escape_string($rows['user_id']); 
			$email = mysql_real_escape_string($rows['email']); 
			$nombre = mysql_real_escape_string($rows['nombre'])." ".mysql_real_escape_string($rows['apellido']); 
			$distrito = mysql_real_escape_string($rows['distrito']); 
		
			$codigo="";
			?><form id="form" name="form" method="POST" action="eventos_preinscripciones.php"> <?php 

			$disabled="";
			if ($rows['ok_club']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
				$disabled="disabled";
			}
			$codigo=$codigo."<div class=\"enlinea\"><span class=\"".$alarma."\">Club</span></div>";
			
			if ($rows['ok_distrito']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
				$disabled="disabled";
			}
			$codigo=$codigo."<div class=\"enlinea\"><span class=\"".$alarma."\">Distrito</span></div>";

			if ($rows['ok_tesoreria']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
				$disabled="disabled";
			}
			$codigo=$codigo."<div class=\"enlinea\"><span class=\"".$alarma."\">Tesoreria</span></div>";

			?><input name="user" type="hidden" value="<?php echo $user_id;?>" /><input name="evento" type="hidden" value="<?php echo $evento;?>" /><input <?php echo $disabled;?> type="submit" name="button" id="button" value="Confirmar Inscripcion" /><?php echo $codigo."<div class=\"enlinea\"><a class=\"boton\" href=\"eventos_fichas.php?user_id=".$rows['user_id']."&evento=".$evento."\">Ficha</a> ".$nombre." - ".$email." (".$distrito.")</div>";?>
			</form>
<?php 
		}
?>


<?php } // Final del IF EVENTO != 0
include 'footer.php';?>

