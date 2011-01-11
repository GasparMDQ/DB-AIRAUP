<?php
include 'header.php';

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';
?>
<p>Importar datos personales</p>
<?php
		$sql_p = "SELECT * FROM rtc_importa_zoho";
		$result_p = mysql_query($sql_p);
		while($rows = mysql_fetch_assoc($result_p))
		{
			$uid = mysql_real_escape_string($rows['email']);
			$em = mysql_real_escape_string($rows['email']);
			$nom = mysql_real_escape_string($rows['nombre']);
			$ape = mysql_real_escape_string($rows['apellido']); 
			$ndd = mysql_real_escape_string($rows['numero_doc']); 
			$tel = mysql_real_escape_string($rows['telefono']); 
			$cel = mysql_real_escape_string($rows['celular']); 
			$fdc =  date('c');
			$fdm =  date('c');
			$fua =  date('c');
			$faa =  date('c');
			$cla = hash('sha512', $uid.$rows['password'].'1s3a3l7t');

			$sql = sprintf("SELECT * FROM rtc_usr_login WHERE email='$em' LIMIT 1");
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				if ($row) {
//					echo "<p>".$em." Ya existe en login";
				} else {
					$sql = sprintf("INSERT INTO rtc_usr_login (user_id, clave, email, fecha_de_creacion, fecha_de_modificacion, fecha_ultimo_acceso, fecha_acceso_actual) VALUES ('$uid', '$cla', '$em', '$fdc', '$fdm', '$fua', '$faa')");
				if (mysql_query($sql)) {
					echo "<p>".$em." Agregado su login</p>";
				} else {
					echo "<p class=\"muestra_alarma\">".$em." Error al agregar su login";
				}
				}


			$sql = sprintf("SELECT * FROM rtc_usr_login WHERE email='$em' LIMIT 1");
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) {
				$userid = $row['uid'];
				$sql = sprintf("INSERT INTO rtc_usr_personales (user_id, nombre, apellido) VALUES ('$userid', '$nom', '$ape')");
				if (mysql_query($sql)) {
					echo "<p>".$em." Agregado datos Personales";
					$sql = sprintf("DELETE FROM rtc_importa_zoho WHERE email='$em'");
					$result = mysql_query($sql);
				} else {
					echo "<p>".$em." Actualizados datos personales";
					$sql = sprintf("UPDATE rtc_usr_personales SET nombre='$nom', apellido='$ape' WHERE email='$em'");
					$result = mysql_query($sql);
					$sql = sprintf("DELETE FROM rtc_importa_zoho WHERE email='$em'");
					$result = mysql_query($sql);
				}
			} else {
				echo "<p class=\"muestra_alarma\">".$em." No existe el login";
			}
	
		}

?>
<?php include 'footer.php';?>

