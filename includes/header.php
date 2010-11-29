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
<link href="/css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="java/ajax.js"></script>
<script type="text/javascript" src="java/jquery-1.4.2.min.js"></script>
</head>

<body class="body">
<?php
require 'includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
} ?>
<div id="container">
	<div id="header">
    	<h1><img src="images/logo_color_rtc.png" title="Logo Rotaract" alt="Logo Rotaract" width="56" height="56" />&nbsp;&nbsp;Base de datos <abbr title="Agencia Informativa Rotaractiana Argentina Uruguaya Paraguaya">A.I.R.A.U.P.</abbr>&nbsp;&nbsp;<img src="images/2.0.png" title="Web 2.0" alt="web 2.0" width="48" height="48" /></h1>
	</div>
	<?php include 'includes/acceso.php'; ?>
	<?php include 'includes/menu.php'; ?>
<div id="mainContent">
