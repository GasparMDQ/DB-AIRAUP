<?
//Archivo de funciones para la base de datos
//Las funciones requieren que primero se cargue el header ya que necesitan que se establezcan las conexiones con la base de datos


function user_porcen($uid,$ev,$me,$op) { // (User_id, Evento_id, Mesa_id, Opcion) Opcion=A: Asistencia REAL; Opcion=J: Asistencia JUSTIFICADA
	$sql="SELECT * FROM rtc_eventos_mesa_modulos WHERE mesa_id='$me' AND fin!='0'";
	$result_m = mysql_query($sql);
	$modulos = mysql_num_rows($result_m); //CANTIDAD DE MODULOS FINALIZADOS EN LA MESA
		
//	echo "<p>UID:".$uid." T:".$modulos." A:".$asiste."</p>"; //VERIFICA POR USUARIO LOS MODULOS TOTALES Y LOS ASISTIDOS

	if ($modulos==0) {
		$modulos = 100; // SI NO HAY MODULOS EN LA MESA SE LO TOMA COMO QUE TIENE 100%
		return $modulos;
	} else {
		if ($op=="A") {
			$sql="SELECT DISTINCT rtc_eventos_mesa_modulos.id FROM rtc_eventos_asistencia, rtc_eventos_mesa_modulos WHERE rtc_eventos_asistencia.evento_id='$ev' AND rtc_eventos_asistencia.user_id='$uid' AND rtc_eventos_asistencia.mesa_id='$me' AND rtc_eventos_asistencia.modulo_id=rtc_eventos_mesa_modulos.id AND rtc_eventos_mesa_modulos.fin!='0' AND (rtc_eventos_asistencia.notas='' OR rtc_eventos_asistencia.notas='NULL') ";
			$result = mysql_query($sql);
			$asiste = mysql_num_rows($result); //CANTIDAD DE MODULOS ASISTIDOS POR EL USUARIO
			return $asiste / $modulos * 100;
		} else if ($op=="J"){
			$sql="SELECT DISTINCT rtc_eventos_mesa_modulos.id FROM rtc_eventos_asistencia, rtc_eventos_mesa_modulos WHERE rtc_eventos_asistencia.evento_id='$ev' AND rtc_eventos_asistencia.user_id='$uid' AND rtc_eventos_asistencia.mesa_id='$me' AND rtc_eventos_asistencia.modulo_id=rtc_eventos_mesa_modulos.id AND rtc_eventos_mesa_modulos.fin!='0'";
			$result = mysql_query($sql);
			$asiste = mysql_num_rows($result); //CANTIDAD DE MODULOS ASISTIDOS POR EL USUARIO
			return $asiste / $modulos * 100;
		} else {
			return 0;
		}
	}
}

function getAge($mysql_date) {
	list($y,$m,$d) = explode("-",$mysql_date);
    $age = date('Y')-$y;
	date('md')<$m.$d ? $age--:null;
    return $age;
}

?>