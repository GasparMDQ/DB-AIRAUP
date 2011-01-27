<?php 
include 'includes/header.php';
require_once 'includes/permisos.php';

function user_detalle($uid,$ev,$me) {
	$sql="SELECT * FROM rtc_eventos_mesa_modulos WHERE mesa_id='$me' AND fin!='0' ORDER BY modulo";
	$result = mysql_query($sql);
	$modulos = mysql_num_rows($result); //CANTIDAD DE MODULOS FINALIZADOS EN LA MESA

	$sql="SELECT nombre, apellido FROM rtc_usr_personales WHERE user_id='$uid' LIMIT 1";
	$result_m=mysql_query($sql);
	$row=mysql_fetch_assoc($result_m);

	$sql="SELECT * FROM rtc_eventos_mesa WHERE id='$me' LIMIT 1";
	$result_m=mysql_query($sql);
	$mesa=mysql_fetch_assoc($result_m);

	echo "<h2>".$row['nombre']." ".$row['apellido']."</h2>";
	echo "<h3>".$mesa['mesa']."</h3><p>";
	while ($row = mysql_fetch_assoc($result)) { //EDITAR PARA MOSTRAR LOS MODULOS ASISTIDAS Y LOS QUE NO
		echo "Modulo: ";
		
		$modulo=$row['id'];
		$sql_u="SELECT * FROM rtc_eventos_asistencia WHERE user_id='$uid' AND modulo_id='$modulo' LIMIT 1";
		$result_u=mysql_query($sql_u);
		$row_u=mysql_fetch_assoc($result_u);

		if ($row_u) {
			echo "<span class=\"muestra_verde\">";
		} else {
			echo "<span class=\"muestra_alarma\">";
		}
		echo $row['modulo']."</span> <span class=\"muestra_alarma\">".$row_u['notas']."</span><br />";
	}
	echo "</p>";	
}

if ($nivel_rrhh OR $nivel_distrito OR $nivel_admin OR $nivel_usuario) { // DEFINIR QUE TIPOS DE ACCESO PERMITEN VISUALIZAR LA INFORMACION
?>
<div>
  <p>Detalle de Asistencia Individual</p>
<?php
	$evento=1; //ESTO ES PARA EL ERAUP 2011 || REEMPLAZAR CON SELECTOR DE EVENTOS

	if (isset($_GET['user'])) {$user_var=intval($_GET['user']);} else {$user_var=0;}
	if (isset($_GET['mesa'])) {$mesa_var=intval($_GET['mesa']);} else {$mesa_var=0;}

	if ($user_var && $mesa_var) {

		if ($nivel_admin OR $nivel_rrhh) { // SI SOLO ES RDR, LE MUESTRO LA INFO DE SU DISTRITO SOLAMENTE
			user_detalle($user_var,$evento,$mesa_var);
		} else if ($nivel_distrito) {
			$u=$_SESSION['uid'];
			$sql="SELECT distrito FROM rtc_usr_institucional WHERE user_id='$u' LIMIT 1";
			$result=mysql_query($sql);
			$row_1=mysql_fetch_assoc($result);

			$sql="SELECT distrito FROM rtc_usr_institucional WHERE user_id='$user_var' LIMIT 1";
			$result=mysql_query($sql);
			$row_2=mysql_fetch_assoc($result);
			if ($row_1['distrito']==$row_2['distrito']) {
				user_detalle($user_var,$evento,$mesa_var);
			}
		}
	}
?>  
</div>

<?php 
} else { // SI NO TIENE ACCESO A ESTA AREA
?>
<div>Permisos insuficientes</div>
<?php 
}
include 'includes/footer.php';
?>
