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
$preinscripcion="";
$fecha_inicio=$row['fecha_inicio'];
$fecha_fin=$row['fecha_fin'];
$fecha_inscripciones=$row['fecha_inscripciones'];
$user_id=$_SESSION['uid'];
$email_contacto=$row['email_contacto'];
$nombre_evento=$row['nombre'];
$predio_nombre=$row['predio_nombre'];
$predio_direccion=$row['predio_direccion'];
$forma_de_pago=$row['forma_de_pago'];
$descripcion=$row['descripcion'];
$error_muestra="";

if ($row['ticket']=="") {
	$ticket="no definido";
} else {
	$ticket="$ ".number_format($row['ticket'], 2, ',', '.');
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
			$preinscripcion="disabled";
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
		$preinscripcion="";
	} else {
		$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_id' AND evento_id='$evento' LIMIT 1";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) {
//			$sql="DELETE FROM rtc_eventos_inscripciones WHERE evento_id='$evento' AND user_id='$user_id'";
//			$result=mysql_query($sql);
			$error_muestra="Ya fue aceptada su inscripcion. Para pedir la baja contacte al coordinador";
		} else {
			echo "NO ESTA PREINSCRIPTO";
		}
	}
}
//ANALISIS DE SITUACION DEL USUARIO Y PREPARACION DE VARIABLES

	$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$status="<div class=\"enlinea\"><span class=\"muestra_amarillo\">No estas inscripto a este evento</span></div>";
	$sql_status="SELECT * FROM rtc_eventos_inscripciones WHERE evento_id='$evento' AND user_id='$user_id' LIMIT 1";
	$result_status=mysql_query($sql_status);
	if (mysql_num_rows($result_status)) {
		$status="<div class=\"enlinea\"><span class=\"muestra_verde\">Ya estas inscripto a este evento</span></div>";
	} else {
		$sql_status="SELECT * FROM rtc_eventos_preinscripciones WHERE evento_id='$evento' AND user_id='$user_id' LIMIT 1";
		$result_status=mysql_query($sql_status);
		if (mysql_num_rows($result_status)) {
			$row_status=mysql_fetch_assoc($result_status);
			$status="";
			//VER QUE LE FALTA
			if ($row_status['ok_club']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
			}
			if (!$row['ok_club']) {
				$status=$status."<div class=\"enlinea\"><span class=\"".$alarma."\">Club</span></div>";
			}
			
			if ($row_status['ok_distrito']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
			}
			if (!$row['ok_distrito']) {
				$status=$status."<div class=\"enlinea\"><span class=\"".$alarma."\">Distrito</span></div>";
			}

			if ($row_status['ok_tesoreria']) {
				$alarma="muestra_verde";
			} else {
				$alarma="muestra_alarma";
			}
			if (!$row['ok_tesoreria']) {
				$status=$status."<div class=\"enlinea\"><span class=\"".$alarma."\">Pago</span></div>";
			}

		}
	}
	
//Habilitacion de Botones
$sql="SELECT * FROM rtc_eventos_preinscripciones WHERE user_id='$user_id' AND evento_id='$evento' LIMIT 1";
$result=mysql_query($sql);
if (mysql_num_rows($result)) {
	$preinscripcion="disabled";
} else {
	$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_id' AND evento_id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	if (mysql_num_rows($result)) {
		$preinscripcion="disabled";
		$baja="";
	} else {	
		$baja="disabled";
	}
}

if ($fecha_fin<date('Y-m-d')) {
	$baja="disabled";
}
	
?>
<div><h2><?php echo $nombre_evento; ?></h2></div>
<?php 
setlocale(LC_ALL, 'es_ES');
?>
<p>
<div class="texto_general">Del <?php echo strftime ("%A %d de %B de %Y", strtotime($fecha_inicio)); ?> al <?php echo strftime ("%A %d de %B de %Y", strtotime($fecha_fin)); ?></div>
<input id="direccion" name="direccion" type="hidden" value="<?php echo $predio_direccion; ?>" />
<div id="map_canvas"></div>
<div><span class="texto_general_simple"><?php echo $descripcion; ?></span></div>
</p>
<p>
<?php if ($nivel_usuario) {?>
<div><span class="texto_general_simple">Estado de la inscripcion:</span> <span class="texto_general"><?php echo $status; ?></span></div>
<?php } ?>
<div><span class="texto_general_simple">Contacto: </span><span class="texto_general"><?php echo $email_contacto; ?></span></div>
<div><span class="texto_general_simple">Ticket: </span><span class="texto_general"><?php echo $ticket; ?></span></div>
<div><span class="texto_general_simple">Cierre de las inscripciones: </span><span class="texto_general"><?php echo strftime ("%A %d de %B de %Y", strtotime($fecha_inscripciones)); ?></span></div>
</p>

<p>
<div><span class="texto_general_simple">Lugar: </span><span class="texto_general"><?php echo $predio_nombre; ?></span></div>
<div><span class="texto_general_simple">Direccion: </span><a href="javascript: codeAddress(false)"><span class="texto_general"><?php echo $predio_direccion; ?></span></a></div>
<?php if ($nivel_usuario) {?>
<div><span class="texto_general_simple">Forma de pago</span><br />
<span class="texto_general"><?php echo $forma_de_pago; ?></span>
</div>

<?php } ?>
<div>mas detalles del encuentro en breve</div>
<?php 
setlocale(LC_ALL, 'es_ES');
?>
</p>

<?php if ($nivel_usuario) {?>
<div><form action="detalle_evento.php" method="post">
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input <?php echo $preinscripcion; ?> type="submit" name="button" value="Preinscribirme" />
  <input <?php echo $baja; ?> type="submit" name="button" value="Dar de baja" />
</form></div>
<div class="muestra_alarma"><?php echo $error_muestra; ?></div>
<?php } ?>
<?php include 'includes/footer.php'; ?>