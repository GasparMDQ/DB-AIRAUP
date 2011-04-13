<?php
	session_start();
	require_once '/home/gasparmdq/configDB/configuracion.php';
//	require_once '/opt/lampp/htdocs/configDB/configuracion.php';
	require_once 'includes/abredb.php';

require '../includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
}
require_once '../includes/permisos.php';
if ($nivel_club OR $nivel_distrito OR $nivel_admin OR $nivel_rrhh OR $nivel_evento OR $nivel_evento_tesoreria) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: ../index.php");
}
	require_once '../includes/funciones.php';

	
?>
<!DOCTYPE HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es" dir="ltr">
<meta name="google-site-verification" content="eU9j3SqOc1mJGYkZJ1MqBJv4kLonHJH0DaVvfa540rM" /><title>Base de Datos</title>
<meta http-equiv="X-UA-Compatible" content="chrome=1">

<link href="../css/dropdown.css" rel="stylesheet" type="text/css" media="all"  />
<link href="../css/dropdown/themes/default/default.ultimate.css" rel="stylesheet" type="text/css" />

<!--[if lt IE 7]>
<script src="js/jquery/jquery.js"></script>
<script src="js/jquery/jquery.dropdown.js"></script>
<![endif]-->


<link href="../css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../java/ajax.js"></script>
<script type="text/javascript" src="../java/jquery-1.4.2.min.js"></script>


</head>

<body class="body">


<div id="container_admin">
<div id="header">

	<div id="menudiv"><ul>
	<li><a href="../index.php">Inscripciones</a></li>
	<li><a href="../distritos.php">Distritos</a></li>
	<li><a href="../clubes.php">Clubes</a></li>
	<li><a href="../basedatos.php">Reportes</a></li>
</ul>
	<a href="../index.php"><img src="../images/header.png" alt="Base de Datos" width="352" height="42" border="0" title="Base de Datos" /></a></div>
    
    <div id="acceso"><?php include '../includes/acceso.php'; ?></div>
</div>
<?php 
include 'main.php';

?>
  
  <div id="mainContent_admin">

