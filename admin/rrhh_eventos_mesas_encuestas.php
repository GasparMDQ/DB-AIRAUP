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

	$mesa=$_POST['mesa_id'];
	$sql="SELECT * FROM rtc_rrhh_encuestas, rtc_rrhh_encuestas_preguntas WHERE rtc_rrhh_encuestas.mesa='1' AND rtc_rrhh_encuestas.id=rtc_rrhh_encuestas_preguntas.encuesta_id";
	$result=mysql_query($sql);
	$numero_sql=mysql_num_rows($result);

	$numero = count($_POST); // COMPARAR CON UN MYSQL_NUM_ROWS PARA VER SI ESTAN TODAS LAS VARIABLES DE LA ENCUESTA

	if ($numero_sql==($numero-2)) { //Se restan 2 valores por las variables 'button' y 'mesa_id'

		$tags = array_keys($_POST); // obtiene los nombres de las varibles que coinciden con 'pregunta_id'
		$valores = array_values($_POST);// obtiene los valores de las varibles que coinciden con 'respuesta'

		// crea las variables y les asigna el valor
		for($i=0;$i<$numero;$i++){ //eliminar las claves 'button' y 'mesa_id'
			$sql="INSERT INTO rtc_rrhh_encuestas_respuestas (pregunta_id, respuesta, destino_id) VALUES ('".$tags[$i]."', '".$valores[$i]."','".$mesa."')";
			if($tags[$i]!="button" AND $tags[$i]!="mesa_id" AND $valores[$i]!="") {
				$result=mysql_query($sql);
//				echo $sql."<br />"; //ACA SE AGREGA LA INFO A LA BASE
			}
		}
	} else {
		echo "Faltan preguntas";
	}

	$sql="SELECT evento_id FROM rtc_eventos_mesa WHERE id='$mesa' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$evento=$row['evento_id'];
	
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

if (isset($_POST['mesa_id']) && isset($_POST['button']) && $_POST['button']=="Finalizar Carga" ) {
	$mesa=$_POST['mesa_id'];
	$sql="SELECT evento_id FROM rtc_eventos_mesa WHERE id='$mesa' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$evento=$row['evento_id'];
	$mesa="";
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
	<?php if ($mesa!=""){ 

		if ($_POST['button']!='Ver Resultados') {


 		$sql="SELECT mesa FROM rtc_eventos_mesa WHERE id='$mesa' ";
		$result=mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$mesa_nombre=$row['mesa'];
		?>
			<h2>Mesa: <?php echo $mesa_nombre; ?></h2>
		<?php 
		$sql="SELECT rtc_rrhh_encuestas_preguntas.pregunta, rtc_rrhh_encuestas_preguntas.id, rtc_rrhh_encuestas_preguntas.numerica FROM rtc_rrhh_encuestas, rtc_rrhh_encuestas_preguntas WHERE rtc_rrhh_encuestas.mesa='1' AND rtc_rrhh_encuestas.id=rtc_rrhh_encuestas_preguntas.encuesta_id";
		$result=mysql_query($sql);
		?>
		<form id="form" name="form" method="POST" action="rrhh_eventos_mesas_encuestas.php">
		<table id="tabla_clubes">
        <?php
		while($row = mysql_fetch_assoc($result)) {
			$pregunta=$row['pregunta'];
			$id=$row['id'];
			$numerica=$row['numerica'];
			if($numerica) {
				$opciones="size=\"2\" maxlength=\"1\"";
			} else {
				$opciones="size=\"30\" maxlength=\"240\"";
			}
			?>
			<tr><td><label for="<?php echo $id; ?>"><?php echo $pregunta; ?></label></td>
			<td><input name="<?php echo $id; ?>" type="text" id="<?php echo $id; ?>" <?php echo $opciones; ?> /></td></tr>
	        <?php
		}
		?>
        </table>
		<input name="mesa_id" type="hidden" id="mesa_id" value="<?php echo $mesa; ?>" />
		<input type="submit" name="button" id="button" value="Agregar" />
		<input type="submit" name="button" id="button" value="Finalizar Carga" />
		</form>
  <?php
		} else {

		 		$sql="SELECT mesa FROM rtc_eventos_mesa WHERE id='$mesa' ";
				$result=mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				$mesa_nombre=$row['mesa'];
				?>
					<h2>Mesa: <?php echo $mesa_nombre; ?></h2>
				<?php 
				$sql="SELECT rtc_rrhh_encuestas_preguntas.pregunta, rtc_rrhh_encuestas_preguntas.id, rtc_rrhh_encuestas_preguntas.numerica FROM rtc_rrhh_encuestas, rtc_rrhh_encuestas_preguntas WHERE rtc_rrhh_encuestas.mesa='1' AND rtc_rrhh_encuestas.id=rtc_rrhh_encuestas_preguntas.encuesta_id";
				$result=mysql_query($sql);
				?>
				<table id="tabla_clubes">
		        <?php
				while($row = mysql_fetch_assoc($result)) {
					$promedio=0;
					$pregunta=$row['pregunta'];
					$pregunta_id=$row['id'];
					$id=$row['id'];
					$numerica=$row['numerica'];
					if($numerica) {
						$sql="SELECT AVG(respuesta) as promedio FROM rtc_rrhh_encuestas_respuestas WHERE pregunta_id='$pregunta_id' AND destino_id='$mesa'";
						$result_prom=mysql_query($sql);
						$promedio=mysql_fetch_assoc($result_prom);						
					?>
					<tr><td><?php echo $pregunta; ?></td>
					<td><?php echo number_format($promedio['promedio'], 2, '.', ''); ?></td></tr>
			        <?php
					} else {
						$sql="SELECT respuesta FROM rtc_rrhh_encuestas_respuestas WHERE pregunta_id='$pregunta_id' AND destino_id='$mesa'";
						$result_prom=mysql_query($sql);
					?>
					<tr><td><?php echo $pregunta; ?></td>
					<td><?php while ($respuestas=mysql_fetch_assoc($result_prom)) { echo $respuestas['respuesta']." | ";} ?></td></tr>
			        <?php

					}
				}
				?>
		        </table>
				<form id="form" name="form" method="POST" action="rrhh_eventos_mesas_encuestas.php">
				<input name="evento" type="hidden" id="evento" value="<?php echo $evento; ?>" />
				<input type="submit" name="button" id="button" value="Volver" />
				</form>
		  <?php
		}
	} else {
 		$sql="SELECT * FROM rtc_eventos_mesa WHERE evento_id='$evento' ORDER BY mesa";
		$result=mysql_query($sql);
		?><h2>Mesas</h2><?php
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
				<input type="submit" name="button" id="button" value="Ver Resultados" />
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

