<?php 
include 'includes/header.php';
require_once 'includes/permisos.php';
require_once 'includes/funciones.php';

if (isset($_POST['evento'])){
	$evento=intval($_POST['evento']);
} else if (isset($_GET['evento'])) {
		$evento=intval($_GET['evento']);
	} else {
		header("Location: index.php");
}

$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);

$baja="";
$fecha_fin=$row['fecha_fin'];
$user_id=$_SESSION['uid'];
if ($fecha_fin<date('Y-m-d')) {
	$baja="disabled";
}
//PREINSCRIPCION
if (isset($_POST['button']) AND $_POST['button']=="Preinscribirme" AND $nivel_usuario) {

	$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_id' AND evento_id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	if (mysql_num_rows($result)) {
		echo "YA ESTA PREINSCRIPTO";
	} else {	
		$sql="SELECT * FROM rtc_eventos_preinscripciones WHERE user_id='$user_id' AND evento_id='$evento' LIMIT 1";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) {
			echo "YA ESTA PREINSCRIPTO";
		} else {
			$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			$ok_club=$row['ok_club'];
			$ok_distrito=$row['ok_distrito'];
			$ok_tesoreria=$row['ok_tesoreria'];
			$sql="INSERT INTO rtc_eventos_preinscripciones (evento_id, user_id, ok_club, ok_distrito, ok_tesoreria) VALUES ('$evento','$user_id','$ok_club','$ok_distrito','$ok_tesoreria')";
			$result=mysql_query($sql);
		}
	}
}

//BAJA DEL ENCUENTRO
if (isset($_POST['button']) AND $_POST['button']=="Dar de baja" AND $nivel_usuario) {
	$user_id=$_SESSION['uid'];
	
	$sql="SELECT * FROM rtc_eventos_preinscripciones WHERE user_id='$user_id' AND evento_id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	if (mysql_num_rows($result)) {
		$sql="DELETE FROM rtc_eventos_preinscripciones WHERE evento_id='$evento' AND user_id='$user_id'";
		$result=mysql_query($sql);
	} else {
		$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_id' AND evento_id='$evento' LIMIT 1";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) {
			$sql="DELETE FROM rtc_eventos_inscripciones WHERE evento_id='$evento' AND user_id='$user_id'";
			$result=mysql_query($sql);
		} else {
			echo "NO ESTA PREINSCRIPTO";
		}
	}
}
//ANALISIS DE SITUACION DEL USUARIO Y PREPARACION DE VARIABLES

	$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$status="No estas inscripto a este evento";
	$sql_status="SELECT * FROM rtc_eventos_inscripciones WHERE evento_id='$evento' AND user_id='$user_id' LIMIT 1";
	$result_status=mysql_query($sql_status);
	if (mysql_num_rows($result_status)) {
		$status="Ya estas inscripto a este evento";
	} else {
		$sql_status="SELECT * FROM rtc_eventos_preinscripciones WHERE evento_id='$evento' AND user_id='$user_id' LIMIT 1";
		$result_status=mysql_query($sql_status);
		if (mysql_num_rows($result_status)) {
			//VER QUE LE FALTA
			if ($row['ok_club']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
			}
			$status="<div class=\"enlinea\"><span class=\"".$alarma."\">Club</span></div>";
			
			if ($row['ok_distrito']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
			}
			$status=$status."<div class=\"enlinea\"><span class=\"".$alarma."\">Distrito</span></div>";

			if ($row['ok_tesoreria']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
			}
			$status=$status."<div class=\"enlinea\"><span class=\"".$alarma."\">Tesoreria</span></div>";

		}
	}
?>

<div><h2><?php echo $row['nombre']; ?></h2></div>
<div class="texto_general">Estado de la inscripcion: <?php echo $status; ?></div>
<div>mail principal de contacto con el encuentro</div>
<div>detalles del encuentro</div>
<div><form action="detalle_evento.php" method="post">
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" value="Preinscribirme" />
  <input <?php echo $baja; ?> type="submit" name="button" value="Dar de baja" />
</form></div>
<?php 
include 'includes/footer.php';
?>