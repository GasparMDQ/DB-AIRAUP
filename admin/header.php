<?php
	session_start();
	require_once '/home/gasparmdq/configDB/configuracion.php';
	require_once 'includes/abredb.php';
	
?>
<!DOCTYPE HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="eU9j3SqOc1mJGYkZJ1MqBJv4kLonHJH0DaVvfa540rM" /><title>Base de Datos</title>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<link href="../css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../java/ajax.js"></script>
<script type="text/javascript" src="../java/jquery-1.4.2.min.js"></script>
</head>

<body class="body">
<?php
require '../includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
}
?>
<div id="container_admin">
  <div id="header">
    <h1><img src="../images/logo_color_rtc.png" title="Logo Rotaract" alt="Logo Rotaract" width="56" height="56" />&nbsp;&nbsp;Administración BD A.I.R.A.U.P.&nbsp;&nbsp;<img src="../images/2.0.png" title="Web 2.0" alt="web 2.0" width="48" height="48" /></h1>
<!-- end #header --></div>
  <div id="acceso">
    <?php include '../includes/acceso.php'; ?>
  </div>
  <div id="mainContent_admin">

<?php 

if ($_SESSION['logged']) {
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
	
	//Verifica si el user es RDR o ADMIN DISTRITAL
	$sql_p = "SELECT * FROM rtc_distritos WHERE (uid_rdr = '$uid_c' OR uid_admin = '$uid_c') AND id_distrito = '$distrito_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_distrito=true;
	}
	//Verifica si el user es MIEMBRO DEL CLUB
	$sql_p = "SELECT * FROM rtc_clubes WHERE (uid_presidente = '$uid_c' OR uid_admin = '$uid_c') AND id_club = '$club_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_club=true;
	}

}
if ($nivel_club OR $nivel_distrito OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: ../index.php");
}

include 'main.php';

?>