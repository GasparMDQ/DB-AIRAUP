<?php
include 'header.php';

$esadmin=false;

if ($nivel_admin OR $nivel_rrhh) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}


?>
<div>
<h2>Descargas de RRHH</h2>
<ul><li><a href="../descargas/evaluaciones_RRHH_ERAUP.xls">Encuestas finales ERAUP 2011</a></li></ul>
</div>

<?php include 'footer.php';?>

