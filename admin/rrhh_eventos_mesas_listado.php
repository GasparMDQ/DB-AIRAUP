<?php
include 'header.php';

$esadmin=false;

if ($nivel_rrhh OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

if (isset($_POST['evento'])){
	$evento=intval($_POST['evento']);
} else if (isset($_GET['evento'])) {
		$evento=intval($_GET['evento']);
	} else {
		$evento=0;
}

if (isset($_POST['mesa'])){
	$mesa=intval($_POST['mesa']);
} else {
	$mesa=0;
}

//GENERA LISTADO

?>
<div><h2>Listados de Participantes</h2></div>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas.php">Seleccione un evento:
<?php
	$sql1 = "SELECT * FROM rtc_eventos ORDER BY nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"evento\" id=\"evento\" onchange=\"location.href='rrhh_eventos_mesas_listado.php?evento='+this.value\" >";
	echo "<option value=\"0\" selected > </option>";
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['id']}\">{$rowtmp['nombre']}</option>";	
	}
	echo "</select>";
?>
</form>
</div>
<?php if ($evento!='0') {?>
<div>
<?php 
	$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	
	$evento_nombre=$row['nombre'];
?>
<h2>Encuentro: <?php echo $evento_nombre; ?></h2>

</div>
<div>
<h2>Mesas</h2>
<?php 
	$sql="SELECT * FROM rtc_eventos_mesa WHERE evento_id='$evento' ORDER BY mesa";
	$result=mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$mesa_id=$row['id'];
		$mesa_nombre=$row['mesa'];
		$coordinador_1=$row['coord_1_id'];
		$coordinador_2=$row['coord_2_id'];
			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_1' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord1= $row_c['nombre']." ".$row_c['apellido'];

			$sql_c="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$coordinador_2' LIMIT 1";
			$result_c=mysql_query($sql_c);
			$row_c = mysql_fetch_assoc($result_c);
			$coord2= $row_c['nombre']." ".$row_c['apellido'];

?>
<p>
Mesa: <?php echo $mesa_nombre; ?><br />
<form id="form" name="form" method="POST" action="rrhh_eventos_mesas_listado_pdf.php">
  <input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
  <input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
  <input type="submit" name="button" id="button" value="Generar Listado" />
</form>
</p>
<?php } // Final del WHILE ?>
</div>
<?php } // Final del IF EVENTO != 0
include 'footer.php';?>

