<?php
include 'header.php';

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';
?>
<p>Importar datos</p>
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
					echo "<p class=\"muestra_alarma\">".$em." Ya existe en login";
				} else {
					$sql = sprintf("INSERT INTO rtc_usr_login (user_id, clave, email, fecha_de_creacion, fecha_de_modificacion, fecha_ultimo_acceso, fecha_acceso_actual) VALUES ('$uid', '$cla', '$em', '$fdc', '$fdm', '$fua', '$faa')");
					$result = mysql_query($sql);
					echo "<p>".$em." Agregado su login</p>";
				}


			$sql = sprintf("SELECT * FROM rtc_usr_login WHERE email='$em' LIMIT 1");
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			if ($row) {
				$userid = $row['uid'];
				$sql = sprintf("INSERT INTO rtc_usr_personales (user_id, nombre, apellido) VALUES ('$userid', '$nom', '$ape')");
				$result = mysql_query($sql);
				echo "<p class=\"muestra_alarma\">".$em." Agregado datos Personales";
			} else {
				echo "<p>".$em." No existe el login";
			}
	
		}

?>
<?php include 'footer.php';?>

