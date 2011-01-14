<?php
include 'header.php';

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

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


?>
<h2>Importar Inscripciones de ERAUP 2011</h2>
<?php
		$sql_p = "SELECT * FROM rtc_importa_inscriptos_eraup2011 ORDER BY mesa, distrito, club";
		$result_p = mysql_query($sql_p);
		while($rows = mysql_fetch_assoc($result_p))
		{
			$em = mysql_real_escape_string($rows['email']);
			$club = mysql_real_escape_string($rows['club']); 
			$distrito = mysql_real_escape_string($rows['distrito']); 
			$nombre = mysql_real_escape_string($rows['nombre']);
			$apellido = mysql_real_escape_string($rows['apellido']); 
			$mesa = mysql_real_escape_string($rows['mesa']); 

			echo "<p>";

			$sql = sprintf("SELECT * FROM rtc_usr_login WHERE email='$em' LIMIT 1");
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			if (!$row) {
				echo "<span class=\"muestra_alarma\">".$em." No existe su login</span><br />";
			} else {
//				echo $em." Existe su login<br />";
				$personales=false;
				$institucionales=false;
				$user_id=$row['uid']; //ACA CARGO EL ID
				$sql = "SELECT * FROM rtc_clubes WHERE club='$club' LIMIT 1";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				if ($row) {
//					echo $em." ".$club." = ".$row['club']."<br />";
					$sql = "SELECT * FROM rtc_usr_institucional WHERE user_id = '$user_id' LIMIT 1";
					$result = mysql_query($sql);
					$fdm =  date('c');
					if (!mysql_num_rows($result)) {
						$sql = "INSERT INTO rtc_usr_institucional (user_id, fecha_de_modificacion) VALUES ('$user_id','$fdm')";
						$result = mysql_query($sql);
					} 
					$clu = $row['id_club'];
					$dist = $row['id_distrito'];
					$sql = sprintf("UPDATE rtc_usr_institucional SET distrito = '$dist', club = '$clu', fecha_de_modificacion = '$fdm' WHERE user_id='$user_id'");
					$result = mysql_query($sql);
					if ($result) {
//						echo "Se actualizaron los datos institucionales <br />";
						$institucionales=true;
					}
				} else {
					echo "<span class=\"muestra_alarma\">".$user_id." ".$em." ".$distrito." ".$club." no se encontro club o distrito</span><br />";
				}
				
				
				$sql = "SELECT * FROM rtc_usr_personales WHERE user_id='$user_id' LIMIT 1";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				if ($row) {
					$fdm =  date('c');
//					echo $em." ".$nombre." ".$apellido."<br />";
					$sql = sprintf("UPDATE rtc_usr_personales SET nombre = '$nombre', apellido = '$apellido', fecha_de_modificacion = '$fdm' WHERE user_id='$user_id'");
					$result = mysql_query($sql);
					if ($result) {
//						echo "Se actualizaron los datos personales <br />";
						$personales=true;					
					}
				} else {
					echo "<span class=\"muestra_alarma\">".$em." no tiene datos personales</span><br />";
				}
			if ($personales AND $institucionales) {
//				echo "Se cargaron los datos satisfactoriamente, puede proceder a la inscripcion</p>";

				$sql = "SELECT * FROM rtc_eventos_inscripciones WHERE user_id='$user_id' AND evento_id='1'";
				$result = mysql_query($sql);
				$row=mysql_fetch_assoc($result);
				if ($row) {
//					echo "<p>Ya esta Inscripto</p>";
				} else {
					$sql = "INSERT INTO rtc_eventos_inscripciones (evento_id, user_id, mesa_id) VALUES ('1','$user_id','0')";
					$result = mysql_query($sql);
				}
		
			} else {
				echo "Hubo problemas con los datos</p>";
			}
			}
		}

?>
<?php include 'footer.php';?>

