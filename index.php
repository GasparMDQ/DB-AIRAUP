<?php 
include 'includes/header.php';
require_once 'includes/permisos.php';
require_once 'includes/funciones.php';

$fecha_de_hoy=date('Y-m-d');

$sql="SELECT * FROM rtc_eventos, rtc_clubes, rtc_distritos WHERE rtc_eventos.fecha_inicio>='$fecha_de_hoy' AND rtc_eventos.club_id=rtc_clubes.id_club AND rtc_eventos.distrito_id=rtc_distritos.id_distrito ORDER BY rtc_eventos.fecha_inicio LIMIT 5";
$result_listado=mysql_query($sql);

?>
<div>
<h3>¡Bienvenido!</h3>
<p>&lt;listado de eventos proximos (5 eventos)&gt;</p>
<?php
	while ($listado_eventos=mysql_fetch_assoc($result_listado)) {
		echo "<p>".$listado_eventos['nombre']." - ".$listado_eventos['club']." (".$listado_eventos['distrito'].") - ".$listado_eventos['fecha_inicio']."</p>";
	}

?>
</div>
<?php // SI ESTA LOGUEADO
if ($_SESSION['logged']) {
?>
<div>
<h3>&Uacute;ltimos eventos asistidos</h3>

<?php 
	$user_id = $_SESSION['uid'];
	$sql = "SELECT * FROM rtc_eventos, rtc_eventos_inscripciones WHERE rtc_eventos_inscripciones.user_id='$user_id' AND rtc_eventos.id=rtc_eventos_inscripciones.evento_id ORDER BY rtc_eventos.fecha_fin LIMIT 5";
	$result=mysql_query($sql);
	while ($row=mysql_fetch_assoc($result)) {
		echo "<p>".$row['nombre']."<br />";
		if ($row['fecha_fin']>date('Y-m-d')) {
			echo "Asistencia: evento no finalizado <br />";
		} else {
			echo "Asistencia Real: ".number_format(user_porcen($user_id,$row['evento_id'],$row['mesa_id'],'A'),2)."<br />";
			echo "Asistencia Justificada: ".number_format(user_porcen($user_id,$row['evento_id'],$row['mesa_id'],'J'),2)."</p>";
		}
	}
?>

</div>
<?php } //FIN SI ESTA LOGUEADO?>
<?php 
include 'includes/footer.php';
?>