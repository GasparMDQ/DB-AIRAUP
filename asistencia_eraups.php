<?php 
include 'includes/header.php';
require_once 'includes/permisos.php';

function user_porcen($uid,$ev,$me,$op) {
	$sql="SELECT * FROM rtc_eventos_mesa_modulos WHERE mesa_id='$me' AND fin!='0'";
	$result_m = mysql_query($sql);
	$modulos = mysql_num_rows($result_m); //CANTIDAD DE MODULOS FINALIZADOS EN LA MESA
		
	$sqlj="SELECT DISTINCT rtc_eventos_mesa_modulos.id FROM rtc_eventos_asistencia, rtc_eventos_mesa_modulos WHERE rtc_eventos_asistencia.evento_id='$ev' AND rtc_eventos_asistencia.user_id='$uid' AND rtc_eventos_asistencia.mesa_id='$me' AND rtc_eventos_asistencia.modulo_id=rtc_eventos_mesa_modulos.id AND rtc_eventos_mesa_modulos.fin!='0'";

	$sqlr="SELECT DISTINCT rtc_eventos_mesa_modulos.id FROM rtc_eventos_asistencia, rtc_eventos_mesa_modulos WHERE rtc_eventos_asistencia.evento_id='$ev' AND rtc_eventos_asistencia.user_id='$uid' AND rtc_eventos_asistencia.mesa_id='$me' AND rtc_eventos_asistencia.modulo_id=rtc_eventos_mesa_modulos.id AND rtc_eventos_mesa_modulos.fin!='0' AND (rtc_eventos_asistencia.notas='' OR rtc_eventos_asistencia.notas='NULL') ";

//	echo $sql;
	$result_p = mysql_query($sqlr);
	$asiste = mysql_num_rows($result_p); //CANTIDAD DE MODULOS ASISTIDOS POR EL USUARIO

	$result_p = mysql_query($sqlj);
	$asiste_j = mysql_num_rows($result_p); //CANTIDAD DE MODULOS ASISTIDOS POR EL USUARIO


//	echo "<p>UID:".$uid." T:".$modulos." A:".$asiste."</p>"; //VERIFICA POR USUARIO LOS MODULOS TOTALES Y LOS ASISTIDOS

	while ($row_p = mysql_fetch_assoc($result_p)) { //EDITAR PARA MOSTRAR LOS MODULOS ASISTIDAS Y LOS QUE NO
	}
	if ($modulos==0) {
		$modulos = 100; // SI NO HAY MODULOS EN LA MESA SE LO TOMA COMO QUE TIENE 100%
		return $modulos;
	} else {
		if ($op=="A") {
			return $asiste / $modulos * 100;
		} else if ($op=="J"){
			return $asiste_j / $modulos * 100;
		} else {
			return 0;
		}
	}
}

if ($nivel_rrhh OR $nivel_distrito OR $nivel_admin OR $nivel_usuario) { // DEFINIR QUE TIPOS DE ACCESO PERMITEN VISUALIZAR LA INFORMACION
?>
<div class="muestra_alarma">
  <p>Sección en desarrollo</p>
</div>
<div>
  <p>Asistencia por distrito</p>
<?php
	$evento=1; //ESTO ES PARA EL ERAUP 2011 || REEMPLAZAR CON SELECTOR DE EVENTOS

	if (isset($_GET['detail'])) {$detalles=1;} else {$detalles=0;}
	if (isset($_GET['district'])) {$distrito_var=intval($_GET['district']);} else {$distrito_var=0;}

	if ($nivel_admin OR $nivel_rrhh) { // SI SOLO ES RDR, LE MUESTRO LA INFO DE SU DISTRITO SOLAMENTE
		$sql="SELECT DISTINCT rtc_usr_institucional.distrito FROM rtc_eventos_inscripciones, rtc_usr_institucional WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_eventos_inscripciones.user_id=rtc_usr_institucional.user_id ORDER BY rtc_usr_institucional.distrito";
	} else {
		$u=$_SESSION['uid'];
		$sql="SELECT distrito FROM rtc_usr_institucional WHERE rtc_usr_institucional.user_id='$u' LIMIT 1";
	}

	$result=mysql_query($sql);
	while ($row=mysql_fetch_assoc($result)) {
		$distrito_id=$row['distrito'];	//ID DEL DISTRITO
		$sql="SELECT distrito FROM rtc_distritos WHERE id_distrito='$distrito_id' LIMIT 1";
		$result_tmp=mysql_query($sql);
		$row_tmp=mysql_fetch_assoc($result_tmp);
		$distrito_nombre=$row_tmp['distrito']; //NOMBRE DEL DISTRITO
		
//		$sql="SELECT DISTINCT user_id FROM rtc_eventos_inscripciones, rtc_eventos_asistencia WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_eventos_asistencia.distrito_id='$distrito_id' ORDER BY mesa_id";
		
		$sql="SELECT * FROM rtc_eventos_inscripciones, rtc_usr_institucional, rtc_usr_personales, rtc_eventos_mesa WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_usr_institucional.distrito='$distrito_id' AND rtc_usr_institucional.user_id=rtc_eventos_inscripciones.user_id AND rtc_usr_personales.user_id=rtc_eventos_inscripciones.user_id AND rtc_eventos_inscripciones.mesa_id=rtc_eventos_mesa.id ORDER BY rtc_usr_personales.apellido, rtc_usr_personales.nombre";
		$result_distrito=mysql_query($sql);
		$total_distrito = mysql_num_rows($result_distrito); //CANTIDAD DE SOCIOS DEL DISTRITO
		$porcentaje_tmp=0;
		echo "<p><a href=\"asistencia_eraups.php?detail=1&district=".$distrito_id."\">Distrito ".$distrito_nombre."</a><br />";
		while ($row_distrito=mysql_fetch_assoc($result_distrito)) {
			$user_id=$row_distrito['user_id'];
			$mesa_id=$row_distrito['mesa_id'];
			$user_nombre=$row_distrito['nombre']." ".$row_distrito['apellido'];
			$user_por=user_porcen($user_id,$evento,$mesa_id,"A");
			$porcentaje_tmp=$porcentaje_tmp+$user_por;
			if ($detalles && $distrito_id==$distrito_var && ($nivel_rrhh OR $nivel_distrito OR $nivel_admin)) { 
				if ($user_por==100) {
					echo "<span class=\"muestra_verde\">";
				} else {
					echo "<span class=\"muestra_alarma\">";
				}
				echo "<a href=\"asistencia_eraups_detalle.php?user=".$user_id."&mesa=".$mesa_id."\">".$user_nombre." (".$row_distrito['mesa'].")</a>: ".number_format($user_por,2)."%</span><br />"; 
			}
		} //FINAL DE WHILE DE USUARIOS POR DISTRITO
		echo "Inscriptos: ".$total_distrito."<br />Asistencia Real: ";
						if ($porcentaje_tmp/$total_distrito==100) {
					echo "<span class=\"muestra_verde\">";
				} else {
					echo "<span class=\"muestra_alarma\">";
				}
		echo number_format($porcentaje_tmp/$total_distrito,2)."%</span><br />";
		$porcentaje_tmp=0;

		$sql="SELECT * FROM rtc_eventos_inscripciones, rtc_usr_institucional, rtc_usr_personales, rtc_eventos_mesa WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_usr_institucional.distrito='$distrito_id' AND rtc_usr_institucional.user_id=rtc_eventos_inscripciones.user_id AND rtc_usr_personales.user_id=rtc_eventos_inscripciones.user_id AND rtc_eventos_inscripciones.mesa_id=rtc_eventos_mesa.id ORDER BY rtc_usr_personales.apellido, rtc_usr_personales.nombre";
		$result_distrito=mysql_query($sql);
		while ($row_distrito=mysql_fetch_assoc($result_distrito)) {
			$user_id=$row_distrito['user_id'];
			$mesa_id=$row_distrito['mesa_id'];
			$user_nombre=$row_distrito['nombre']." ".$row_distrito['apellido'];
			$user_por=user_porcen($user_id,$evento,$mesa_id,"J");
			$porcentaje_tmp=$porcentaje_tmp+$user_por;
		} //FINAL DE WHILE DE USUARIOS POR DISTRITO
		echo "Asistencia Justificada: ";
						if ($porcentaje_tmp/$total_distrito==100) {
					echo "<span class=\"muestra_verde\">";
				} else {
					echo "<span class=\"muestra_alarma\">";
				}
		echo number_format($porcentaje_tmp/$total_distrito,2)."%</span></p>";

	} //FINAL DEL WHILE POR DISTRITO 

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
