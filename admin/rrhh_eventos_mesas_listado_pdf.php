<?php
include 'header.php';

$esadmin=false;

if ($nivel_rrhh OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

$evento=0;
$mesa=0;

if (isset($_POST['evento'])){
	$evento=intval($_POST['evento']);
}

if (isset($_POST['mesa'])){
	$mesa=intval($_POST['mesa']);
}

if ($evento!=0 AND $mesa!=0 AND $_POST['button']="Generar Listado") {

//GENERA LISTADO

	$sql="SELECT mesa, coord_1_id, coord_2_id FROM rtc_eventos_mesa WHERE id='$mesa' AND evento_id='$evento' LIMIT 1";
	$result= mysql_query ($sql);
	$row=mysql_fetch_assoc($result);
	$mesa_nombre=$row['mesa']; // nombre de mesa
	$c1id=$row['coord_1_id'];
	$c2id=$row['coord_2_id'];
	$sql="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$c1id' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$coordina1=$row['nombre']." ".$row['apellido']; // coordinador 1
	$sql="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$c2id' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$coordina2=$row['nombre']." ".$row['apellido']; // coordinador 2

?>
<div><h2>Listados de Participantes</h2></div>
<div>
<h2>Mesa: <?php echo $mesa_nombre; ?></h2>
<h3>Coordinadores: <?php echo $coordina1." | ".$coordina2; ?></h3>
<table width="90%" border="0">
  <tr><th>Distrito</th><th>Nombre</th></tr>

<?php
	$sql="SELECT rtc_distritos.distrito, rtc_usr_personales.nombre, rtc_usr_personales.apellido FROM rtc_distritos, rtc_eventos_inscripciones, rtc_usr_personales, rtc_usr_institucional WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_eventos_inscripciones.mesa_id='$mesa' AND rtc_usr_personales.user_id=rtc_eventos_inscripciones.user_id AND rtc_usr_institucional.user_id=rtc_eventos_inscripciones.user_id AND rtc_distritos.id_distrito=rtc_usr_institucional.distrito ORDER BY rtc_distritos.distrito, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
	$result=mysql_query($sql);
	while ($row=mysql_fetch_assoc($result)){
		$distrito=$row['distrito'];
		$nombre=$row['nombre']." ".$row['apellido'];
?>
  <tr><td><?php echo $distrito; ?></td><td><?php echo $nombre; ?></td></tr>
<?php } ?>
</table>
</div>


<?php
} // Final del GENERAR LISTADO
include 'footer.php';?>

