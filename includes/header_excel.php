<?php
	session_start();
	header("Content-typo: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=tabla.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<html>
<head>
<title>Base de Datos</title>
<script type="text/javascript" src="java/ajax.js">
</script>
</head>

<body class="body">
<?php
require 'includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
}
?>
<div id="container">
  <div id="header">
    <h1><img src="images/logo_color_rtc.png" title="Logo Rotaract" alt="Logo Rotaract" width="56" height="56" />&nbsp;&nbsp;Base de datos A.I.R.A.U.P.&nbsp;&nbsp;<img src="images/2.0.png" title="Web 2.0" alt="web 2.0" width="48" height="48" /></h1>
  <!-- end #header --></div>
  <div id="mainContent">