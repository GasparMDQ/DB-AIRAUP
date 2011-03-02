<?php 
	require_once 'includes/permisos.php';
?>
<div id="notificaciones">

<?php
	$autorizaciones_pendientes=0;

	if ($nivel_usuario) {
	}

	if ($nivel_club) {
		$socios_pendientes=0;
		//Verifica si tiene socios esperando por el alta en el club
		$sql="SELECT * FROM rtc_usr_institucional WHERE club='$nivel_club_id' AND verifica_club='0'";
		$result=mysql_query($sql);
		$socios_pendientes=mysql_num_rows($result);
		if ($socios_pendientes) {
			echo "Tiene ".$socios_pendientes." socios pendientes ";
		}
		//Verifica si tiene autorizaciones a eventos pendientes de aprobacion
		$sql="SELECT * FROM rtc_eventos_preinscripciones, rtc_usr_institucional WHERE rtc_usr_institucional.club='$nivel_club_id' AND rtc_eventos_preinscripciones.ok_club='0' AND rtc_usr_institucional.user_id=rtc_eventos_preinscripciones.user_id";
		$result=mysql_query($sql);
		$autorizaciones_pendientes=$autorizaciones_pendientes+mysql_num_rows($result);

	}

	if ($nivel_distrito) {
		//Verifica si tiene autorizaciones a eventos pendientes de aprobacion
		$sql="SELECT * FROM rtc_eventos_preinscripciones, rtc_usr_institucional WHERE rtc_usr_institucional.distrito='$nivel_distrito_id' AND rtc_eventos_preinscripciones.ok_distrito='0' AND rtc_usr_institucional.user_id=rtc_eventos_preinscripciones.user_id";
		$result=mysql_query($sql);
		$autorizaciones_pendientes=$autorizaciones_pendientes+mysql_num_rows($result);
	}
	
	if ($nivel_evento_tesoreria) {
		$user_id=$_SESSION['uid'];
		//Verifica si tiene autorizaciones de pagos a eventos pendientes de aprobacion
		$sql="SELECT * FROM rtc_eventos_preinscripciones, rtc_eventos_tesoreria WHERE rtc_eventos_tesoreria.user_id='$user_id' AND rtc_eventos_tesoreria.evento_id=rtc_eventos_preinscripciones.evento_id AND rtc_eventos_preinscripciones.ok_tesoreria='0'";
		$result=mysql_query($sql);
		$autorizaciones_pendientes=$autorizaciones_pendientes+mysql_num_rows($result);
	}

	if ($autorizaciones_pendientes) {
		echo "<a href=\"/admin/eventos_autoriza.php\">Tiene ".$autorizaciones_pendientes." autorizaciones pendientes </a>";
	}

?>

</div>

