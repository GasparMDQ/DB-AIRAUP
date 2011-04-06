<?php
include 'header.php';

$esadmin=false;

if ($nivel_rrhh OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

if (isset($_POST['mesa']) && isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Actualizar") {

	$mesa_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['mesa']),0,10)));
	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));


	$sql="SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_act' AND evento_id='$evento_act' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	if ($row) {
		$sql="UPDATE rtc_eventos_inscripciones SET mesa_id='$mesa_act' WHERE user_id='$user_act' AND evento_id='$evento_act'";
		$result=mysql_query($sql);
//		echo mysql_error($result);
	} else {
		$sql="INSERT INTO rtc_eventos_inscripciones (mesa_id, user_id, evento_id) VALUES ('$mesa_act','$user_act','$evento_act')";
		$result=mysql_query($sql);
//		echo mysql_error($result);
	}
} // FIN ACTUALIZAR DATOS


////////////////////////////////////////////
$evento_id=2;// SE DEFINE EL EVENTO A CARGAR
////////////////////////////////////////////


?>
<h2>Inscribir Asistentes a Mesas del ERAUP 2011</h2>
<?php
//		$sql_p = "SELECT rtc_eventos_inscripciones.user_id, rtc_eventos_inscripciones.mesa_id FROM rtc_eventos_inscripciones, rtc_importa_inscriptos_eraup2011, rtc_usr_login WHERE rtc_eventos_inscripciones.user_id=rtc_usr_login.uid AND rtc_usr_login.email=rtc_importa_inscriptos_eraup2011.email AND rtc_eventos_inscripciones.evento_id='1' ORDER BY rtc_importa_inscriptos_eraup2011.mesa, rtc_importa_inscriptos_eraup2011.distrito, rtc_eventos_inscripciones.mesa_id, rtc_eventos_inscripciones.user_id";
		$sql_p = "SELECT rtc_eventos_inscripciones.user_id, rtc_eventos_inscripciones.mesa_id FROM rtc_eventos_inscripciones, rtc_usr_institucional, rtc_distritos WHERE evento_id='".$evento_id."' AND rtc_eventos_inscripciones.user_id=rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito ORDER BY rtc_distritos.distrito, rtc_eventos_inscripciones.mesa_id";
//		echo $sql_p;
		$result_p = mysql_query($sql_p);
		while($rows = mysql_fetch_assoc($result_p))
		{
			$user_id = mysql_real_escape_string($rows['user_id']); 
			$mesa_id = mysql_real_escape_string($rows['mesa_id']);

			$sql="SELECT apellido, nombre FROM rtc_usr_personales WHERE user_id='$user_id' LIMIT 1";
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			$nombre=$row['nombre'];
			$apellido=$row['apellido'];

			$sql="SELECT distrito, club FROM rtc_usr_institucional WHERE user_id='$user_id' LIMIT 1";
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			$dis_id=$row['distrito'];
			$club_id=$row['club'];

			$sql="SELECT club FROM rtc_clubes WHERE id_club='$club_id' LIMIT 1";
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			$club=$row['club'];

			$sql="SELECT distrito FROM rtc_distritos WHERE id_distrito='$dis_id' LIMIT 1";
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			$distrito=$row['distrito'];

			$sql="SELECT email FROM rtc_usr_login WHERE uid='$user_id' LIMIT 1";
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			$em=$row['email'];

			?><form id="form" name="form" method="POST" action="rrhh_eventos_mesas_inc.php"> <?php 

			echo $nombre." ".$apellido." (".$distrito." - ".$club."): ";
			$sql1 = "SELECT * FROM rtc_eventos_mesa WHERE evento_id='".$evento_id."' ORDER BY mesa";
			$resultado = mysql_query($sql1);
			$sel='';
			echo "<select name=\"mesa\" id=\"mesa\">";
			echo "<option value=\"0\" selected > </option>";
			while ($rowtmp = mysql_fetch_assoc($resultado))
			{
				if ($rowtmp['id']==$mesa_id) { $sel = 'selected="selected"';} else {$sel = '';}
				echo "<option value=\"{$rowtmp['id']}\" {$sel}>{$rowtmp['mesa']}</option>";	
			}
			echo "</select>";
			?><input name="user" type="hidden" value="<?php echo $user_id;?>" /><input name="evento" type="hidden" value="<?php echo $evento_id;?>" /><input type="submit" name="button" id="button" value="Actualizar" />
			</form>
<?php 
		}
?>
<?php include 'footer.php';?>

