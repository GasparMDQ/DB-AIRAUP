<?php
include 'header.php';

$esadmin=false;

if ($nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

if (isset($_POST['user']) && isset($_POST['button']) && $_POST['button']=="Dar Acceso") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 


	$sql="SELECT * FROM rtc_rrhh_admin WHERE user_id='$user_act' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	if ($row) {
		echo "El usuario ya tiene acceso";
	} else {
		$sql="INSERT INTO rtc_rrhh_admin (user_id) VALUES ('$user_act')";
		$result=mysql_query($sql);
//		echo mysql_error($result);
	}
} // FIN DAR ACCESO

if (isset($_POST['user']) && isset($_POST['button']) && $_POST['button']=="Quitar Acceso") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 


	$sql="SELECT * FROM rtc_rrhh_admin WHERE user_id='$user_act' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	if ($row) {
		$sql="DELETE FROM rtc_rrhh_admin WHERE user_id='$user_act'";
		$result=mysql_query($sql);
	} else {
		echo "El usuario no tenia acceso";
	}
} // FIN ALTAS DATOS


?>
<h2>Dar Acceso de RRHH</h2>
<form id="form" name="form" method="POST" action="accesso_rrhh.php"> <?php 

	$sql1 = "SELECT rtc_usr_personales.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito FROM rtc_usr_personales, rtc_usr_institucional, rtc_distritos WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id  AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito ORDER BY rtc_usr_personales.nombre, rtc_usr_personales.apellido,  rtc_distritos.distrito";
//	echo $sql1;
	$resultado = mysql_query($sql1);
	echo "<select name=\"user\" id=\"user\">";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\" >({$rowtmp['distrito']})- {$rowtmp['nombre']} {$rowtmp['apellido']} ({$rowtmp['user_id']})</option>";	
	}
	echo "</select>";

			?><input type="submit" name="button" id="button" value="Dar Acceso" />
			</form>

<?php
		$sql_p = "SELECT rtc_usr_personales.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito FROM rtc_usr_institucional, rtc_distritos, rtc_usr_personales, rtc_rrhh_admin WHERE rtc_rrhh_admin.user_id=rtc_usr_personales.user_id AND rtc_usr_personales.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito ORDER BY rtc_distritos.distrito, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
		$result_p = mysql_query($sql_p);
		$cantidad_instr=mysql_num_rows($result_p);
		echo "Instructores: ".$cantidad_instr."<br />";
		while($rows = mysql_fetch_assoc($result_p))
		{
			$user_id = mysql_real_escape_string($rows['user_id']); 
			$nombre = mysql_real_escape_string($rows['nombre'])." ".mysql_real_escape_string($rows['apellido']); 
			$distrito = mysql_real_escape_string($rows['distrito']); 

			?><form id="form" name="form" method="POST" action="accesso_rrhh.php"> <?php 

			echo $nombre." (".$distrito."): ";
			?><input name="user" type="hidden" value="<?php echo $user_id;?>" /><input type="submit" name="button" id="button" value="Quitar Acceso" />
			</form>
<?php 
		}
?>

<?php include 'footer.php';?>

