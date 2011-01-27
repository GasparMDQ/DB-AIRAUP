<?php 
include 'includes/header.php';
require_once 'includes/permisos.php';
require_once 'includes/funciones.php';

?>
<div>
<h3>¡Bienvenido!</h3>
<p>&lt;listado de eventos proximos (5 eventos)&gt;</p>
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