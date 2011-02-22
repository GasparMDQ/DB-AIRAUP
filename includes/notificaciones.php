<?php 
	require_once 'includes/permisos.php';
?>
<div id="notificaciones">

<?php
	if ($nivel_usuario) {
	}

	if ($nivel_club) {
		$socios_pendientes=0;
		$sql="SELECT * FROM rtc_usr_institucional WHERE club='$nivel_club_id' AND verifica_club='0'";
		$result=mysql_query($sql);
		$socios_pendientes=mysql_num_rows($result);
		if ($socios_pendientes) {
			echo "Tiene ".$socios_pendientes." socios pendientes ";
		}
	}

	if ($nivel_distrito) {
		$autorizaciones_pendientes=0;
		$sql="SELECT * FROM rtc_eventos_preinscripciones, rtc_usr_institucional WHERE rtc_usr_institucional.distrito='$nivel_distrito_id' AND rtc_eventos_preinscripciones.ok_distrito='0' AND rtc_usr_institucional.user_id=rtc_eventos_preinscripciones.user_id";
		$result=mysql_query($sql);
		$autorizaciones_pendientes=mysql_num_rows($result);
		if ($autorizaciones_pendientes) {
			echo "Tiene ".$autorizaciones_pendientes." autorizaciones pendientes ";
		}
	}

?>

</div>