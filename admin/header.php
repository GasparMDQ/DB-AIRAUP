<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Base de Datos</title>
<link href="../css/base.css" rel="stylesheet" type="text/css" />
</head>

<body class="body">
<?php
require '../includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
}
?>
<div id="container">
  <div id="header">
    <h1><img src="../images/logo_color_rtc.png" title="Logo Rotaract" alt="Logo Rotaract" width="56" height="56" />&nbsp;&nbsp;Administración BD A.I.R.A.U.P.&nbsp;&nbsp;<img src="../images/2.0.png" title="Web 2.0" alt="web 2.0" width="48" height="48" /></h1>
<!-- end #header --></div>
  <div id="acceso">
    <?php include '../includes/acceso.php'; ?>
  </div>
  <div id="separadorT"></div>
  <div id="accesorapido"></div>
  <div id="mainContent">

<?php 
$consulta = $_SESSION['uid'];
$sql = sprintf("SELECT * FROM rtc_admin WHERE " . "uid = \"$consulta\" LIMIT 1");
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
if (!$_SESSION['logged'] || !$row) {
	header("Location: ../index.php");
}
?>