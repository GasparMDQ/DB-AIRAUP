<?php
session_start();
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';
require_once 'permisos.php';

$idsocio=intval($_GET['socio']);

$sql = "SELECT * FROM rtc_usr_personales, rtc_usr_institucional, rtc_usr_login WHERE rtc_usr_personales.user_id = '$idsocio' AND  rtc_usr_institucional.user_id = '$idsocio' AND rtc_usr_login.uid = '$idsocio' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$idc=$row['club'];
$idd=$row['distrito'];

echo "<div id=\"foto_socio\"></div>";
echo "<h2>".$row['nombre']." ".$row['apellido']."</h2>";
if ($nivel_usuario){ echo $row['email']."<br />";}
if ($nivel_admin OR ($nivel_distrito AND $nivel_distrito_id==$idd) OR ($nivel_usuario_club_id==$idc)){echo $row['direccion']."<br />";}
if ($nivel_admin OR ($nivel_distrito_id==$idd) OR ($nivel_usuario_club_id==$idc)){echo "Celular: ".$row['celular']."<br />";}
if ($nivel_admin OR ($nivel_distrito AND $nivel_distrito_id==$idd) OR ($nivel_club AND $nivel_club_id==$idc)){echo "Fijo: ".$row['telefono']."<br />";}
if ($nivel_admin OR ($nivel_distrito_id==$idd) OR ($nivel_usuario_club_id==$idc)){setlocale(LC_ALL, 'es_ES'); echo "Fecha de Nacimiento: ".strftime ("%d %B %Y", strtotime($row['fecha_de_nacimiento']))."<br />";}
?>
&lt;listado de cargos&gt;
