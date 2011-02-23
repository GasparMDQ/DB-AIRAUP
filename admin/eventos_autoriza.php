<?php
include 'header.php';

$esadmin=false;
$nivel_evento=false;

if ($nivel_admin OR $nivel_evento OR $nivel_distrito OR $nivel_club) {
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

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Autoriza RDR") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));

// VERIFICAR QUE EL USUARIO CUENTA CON LOS PERMISOS NECESARIOS PARA ESTE TIPO DE AUTORIZACION

	$sql="UPDATE rtc_eventos_preinscripciones SET ok_distrito='1' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
	$result=mysql_query($sql);
}

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Desautoriza RDR") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));


	$sql="UPDATE rtc_eventos_preinscripciones SET ok_distrito='0' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
	$result=mysql_query($sql);
}
 // FIN OK DISTRITAL 



?>
<div>
  <h2>Autorizaciones</h2>
</div>
<?php
	$hoy=date("c");
	$sql="SELECT * FROM rtc_eventos WHERE fecha_fin>='$hoy'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
	$evento=$row['id'];
	$requiere_club=$row['ok_club'];
	$requiere_distrito=$row['ok_distrito'];
	$requiere_tesoreria=$row['ok_tesoreria'];
	
?>

<h2>Estado de Autorizaciones a <?php echo $row['nombre'];?></h2>
<?php
		//SI ES ADMIN VE TODAS LAS AUTORIZACIONES, SINO SOLO LAS DE SU DISTRITO Y/O CLUB
		if ($nivel_admin) {
			$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
		} else if ($nivel_distrito) {
			$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club AND rtc_usr_institucional.distrito='$nivel_distrito_id' ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
		} else if ($nivel_club) {
			$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club AND rtc_usr_institucional.club='$nivel_club_id' ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
		} else {
			//VER QUE HACER ACA
		}
		
		$result_p = mysql_query($sql_p);
		$cantidad_inscriptos = mysql_num_rows($result_p);
		echo "Total de Preinscriptos: ".$cantidad_inscriptos." <br />";
		while($rows = mysql_fetch_assoc($result_p))
		{
			$user_id = mysql_real_escape_string($rows['user_id']); 
			$nombre = mysql_real_escape_string($rows['nombre'])." ".mysql_real_escape_string($rows['apellido']); 
			$distrito = mysql_real_escape_string($rows['distrito']); 

			?><form id="form" name="form" method="POST" action="eventos_autoriza.php"> <?php 

			echo "<div class=\"enlinea\">".$nombre." (".$distrito.")</div>";
			
			$disabled="disabled";
			if ($nivel_club) {
				$disabled="";
			}
			if(!$requiere_club){
				if($rows['ok_club']) {
					echo "<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Desautoriza Club\"/>";
				} else {
					echo "<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Autoriza Club\"/>";
				}
			} else {
				echo "<div class=\"enlinea\">No requiere autorizacion del club </div>";
			}
			
			$disabled="disabled";
			if ($nivel_distrito) {
				$disabled="";
			}
			if(!$requiere_distrito){
				if($rows['ok_distrito']) {
					echo "<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Desautoriza RDR\"/>";
				} else {
					echo "<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Autoriza RDR\"/>";
				}
			} else {
				echo "<div class=\"enlinea\">No requiere autorizacion del distrito </div>";
			}

			$disabled="disabled";
			if ($nivel_tesorero) {
				$disabled="";
			}
			if(!$requiere_tesoreria){
				echo "<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Autoriza Pago\"/>";
			} else {
				echo "<div class=\"enlinea\">No requiere pago de se&ntilde;a </div>";
			}
			
			if ($rows['ok_club'] AND $rows['ok_distrito'] AND $rows['ok_tesoreria']) {
				echo "<div class=\"enlinea\">Esperando confirmacion del organizador</div>";
			}
			?><input name="user" type="hidden" value="<?php echo $user_id;?>" /><input name="evento" type="hidden" value="<?php echo $evento;?>" />
</form>
<?php 
		}
?>


<?php } // Final del WHILE
include 'footer.php';?>

