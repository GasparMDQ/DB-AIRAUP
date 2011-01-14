<?php
include 'header.php';

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

$esadmin=false;

if ($nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

?>
<p>Importar datos institucionales</p>
<?php
		$sql_p = "SELECT * FROM rtc_importa_zoho_club ORDER BY club";
		$result_p = mysql_query($sql_p);
		while($rows = mysql_fetch_assoc($result_p))
		{
			$em = mysql_real_escape_string($rows['email']);
			$club = mysql_real_escape_string($rows['club']); 
			$distrito = mysql_real_escape_string($rows['distrito']); 
			$fdm =  date('c');

			$sql = sprintf("SELECT * FROM rtc_usr_login WHERE email='$em' LIMIT 1");
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			if (!$row) {
				echo "<p class=\"muestra_alarma\">".$em." No existe su login</p>";
			} else {
				$user_id=$row['uid']; //ACA CARGO EL ID
				$sql = "SELECT * FROM rtc_clubes WHERE club='$club' LIMIT 1";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				if ($row) {
//					echo "<p>".$em." ".$club." = ".$row['club']."</p>";
					$sql = "SELECT * FROM rtc_usr_institucional WHERE user_id = '$user_id' LIMIT 1";
					$result = mysql_query($sql);
					if (!mysql_num_rows($result)) {
						$fdm =  date('c');
						$sql = "INSERT INTO rtc_usr_institucional (user_id, fecha_de_modificacion) VALUES ('$user_id','$fdm')";
						$result = mysql_query($sql);
					} 
					$clu = $row['id_club'];
					$dist = $row['id_distrito'];
					$sql = sprintf("UPDATE rtc_usr_institucional SET distrito = '$dist', club = '$clu', fecha_de_modificacion = '$fdm' WHERE user_id='$user_id'");
//					echo "<p>".$sql."</p>";
					$result = mysql_query($sql);
					if ($result) {
						$sql = sprintf("DELETE FROM rtc_importa_zoho_club WHERE email='$em'");
						$result = mysql_query($sql);
					}
				} else {
					echo "<p class=\"muestra_alarma\">".$user_id." ".$em." ".$distrito." ".$club." no se encontro</p>";
				}
			}
		}

?>
<?php include 'footer.php';?>

