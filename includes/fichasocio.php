<?php
session_start();
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';

$idsocio=intval($_GET['socio']);

$sql = "SELECT * FROM rtc_usuarios WHERE uid = '$idsocio'";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);


if ($_SESSION['logged']) {
	$nivel_usuario=true;
	//Verifica si el user es ADMIN del Sitio
	$uid_c = $_SESSION['uid'];
	$sql_p = "SELECT * FROM rtc_admin WHERE uid = '$uid_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_admin=true;
	}

	$sql_u = "SELECT * FROM rtc_usuarios WHERE uid = '$uid_c' LIMIT 1";
	$result_u = mysql_query($sql_u);
	$row_u = mysql_fetch_assoc($result_u);
	$club_c=$row_u['club'];
	$distrito_c=$row_u['distrito'];
	$club_s=$row['club'];
	
	//Verifica si el user es RDR o ADMIN DISTRITAL
	$sql_p = "SELECT * FROM rtc_distritos WHERE (uid_rdr = '$uid_c' OR uid_admin = '$uid_c') AND id_distrito = '$distrito_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_distrito=true;
	}
	//Verifica si el user es MIEMBRO DEL CLUB
	if ($club_s==$club_c) {
		$nivel_club=true;
	}

}

echo "<div id=\"foto_socio\">foto</div>";
echo "<h2>".$row['nombre']." ".$row['apellido']."</h2>";
if ($nivel_usuario){ echo $row['email']."<br />";}
if ($nivel_club or $nivel_distrito or $nivel_admin){echo $row['direccion']."<br />";}
if ($nivel_club or $nivel_distrito or $nivel_admin){echo "Celular: ".$row['celular']."<br />";}
if ($nivel_club or $nivel_distrito or $nivel_admin){echo "Fijo: ".$row['telefono']."<br />";}
if ($nivel_club or $nivel_distrito or $nivel_admin){setlocale(LC_ALL, 'es_ES'); echo "Fecha de Nacimiento: ".strftime ("%d %B %Y", strtotime($row['fecha_de_nacimiento']))."<br />";}
?>
&lt;listado de cargos&gt;
