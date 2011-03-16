<?php
	session_start();
	require_once '/home/gasparmdq/configDB/configuracion.php';
//	require_once '/opt/lampp/htdocs/configDB/configuracion.php';
	require_once 'includes/abredb.php';
	
?>
<!DOCTYPE HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="eU9j3SqOc1mJGYkZJ1MqBJv4kLonHJH0DaVvfa540rM" /><title>Base de Datos</title>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<link href="/css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="java/ajax.js"></script>
<script type="text/javascript" src="java/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
</head>

<body class="body" onLoad="initialize()">
<?php
require 'includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
}
require_once 'includes/permisos.php';
?>

<div id="container">
<div id="header">
	<?php include 'includes/menu.php'; ?>
	<?php include 'includes/acceso.php'; ?>
<div class="separadorB"></div>
	<?php include 'includes/notificaciones.php'; ?>
</div>
<div class="separadorB"></div>
<div id="mainContent">
