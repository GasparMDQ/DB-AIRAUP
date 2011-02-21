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
			echo "Tiene ".$socios_pendientes." socios pendientes de aprobacion";
		}
	}

	if ($nivel_distrito) {
	}

?>

</div>