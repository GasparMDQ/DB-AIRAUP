<?php
include 'header.php';
//INSERTAR PHP DE PRUEBA ACA
$fecha="2011-03-30";
$fecha_hoy=date('Y-m-d');
if ($fecha_hoy<=$fecha) {
	echo "Si";
} else {
	echo "No";
}
include 'footer.php';?>

