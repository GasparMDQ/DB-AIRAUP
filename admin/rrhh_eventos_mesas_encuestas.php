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
// AGREGAR RESPUESTAS
if (isset($_POST['mesa_id']) && isset($_POST['button']) && $_POST['button']=="Agregar" ) {



	$numero = count($_POST); // COMPARAR CON UN MYSQL_NUM_ROWS PARA VER SI ESTAN TODAS LAS VARIABLES DE LA ENCUESTA
	$tags = array_keys($_POST); // obtiene los nombres de las varibles que coinciden con 'pregunta_id'
	$valores = array_values($_POST);// obtiene los valores de las varibles que coinciden con 'respuesta'

	$variables_sql="(";
	$valores_sql="(";
	// crea las variables y les asigna el valor
	for($i=0;$i<$numero;$i++){ 
		$variables_sql=$variables_sql."(".$tags[$i].")";
		$valores_sql=$valores_sql."'".$valores[$i]."'";
		if ($i!=numero) {
			$variables_sql=$variables_sql.",";
			$valores_sql=$valores_sql.",";
		}
		$variables_sql=$variables_sql.")";
		$valores_sql=$valores_sql.")";
	}






//	$m_nombre = mysql_real_escape_string(substr(htmlspecialchars($_POST['mesa_nombre']),0,40));
//	$m_c = mysql_real_escape_string(substr(htmlspecialchars($_POST['coordinador']),0,10));
//	$m_ev = mysql_real_escape_string(substr(htmlspecialchars($_POST['evento']),0,10));
	
//	$sql = "INSERT INTO rtc_eventos_mesa (mesa, evento_id) VALUES ('$m_nombre', '$m_ev')";
//	$result = mysql_query($sql);
//	$sql_tmp = "SELECT id FROM rtc_eventos_mesa WHERE evento_id='$m_ev' AND mesa='$m_nombre' LIMIT 1";
//	$result_tmp = mysql_query($sql_tmp);
//	$row_tmp = mysql_fetch_assoc($result_tmp);
//	$mesa = $row_tmp['id'];
//	if ($m_c <> 0) {
//		$sql = "INSERT INTO rtc_eventos_mesa_coordinadores (mesa_id, user_id) VALUES ('$mesa','$m_c')";
//		$result = mysql_query($sql);
//	}
//	$mesa=0;
}

?>
<div><h2>Carga de Encuestas de Mesas</h2></div>
<div>
<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas_encuestas.php">Seleccione un evento:
<?php
	$sql1 = "SELECT * FROM rtc_eventos ORDER BY nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"evento\" id=\"evento\" onchange=\"location.href='rrhh_eventos_mesas_encuestas.php?evento='+this.value\" >";
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
	<?php if ($mesa!=""){ 
		// INGRESAR CODIGO QUE MUESTRE LAS PREGUNTAS DE ESTA MESA
		// BOTONES (AGREGAR) (FINALIZAR CARGA)
	} else {
 		$sql="SELECT * FROM rtc_eventos_mesa WHERE evento_id='$evento' ORDER BY mesa";
		$result=mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$mesa_id=$row['id'];
			$mesa_nombre=$row['mesa'];
			?>
			<p>
			Mesa: <?php echo $mesa_nombre; ?><br />
			<form id="form1" name="form1" method="POST" action="rrhh_eventos_mesas_encuestas.php">
				<input name="mesa" type="hidden" id="mesa" value="<?php echo $mesa_id; ?>" />
				<input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
				<input type="submit" name="button" id="button" value="Cargar encuestas" />
				<hr />
			</form>
			</p>
			<?php 
		} // Final del WHILE
	} // Final del IF MESA != "" ?>
	</div>
	<?php
} // Final del IF EVENTO != 0
include 'footer.php';?>

