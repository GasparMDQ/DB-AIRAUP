<?php
//Al cargar este archivo se generan variables que permiten saber el nivel de permiso de un usuario
//
// $nivel_admin= TRUE|FALSE				Posee nivel de administracion del sitio
//
// $nivel_distrito= TRUE|FALSE			Posee nivel de administracion distrital
// $nivel_distrito_id= INT				[id_distrito] del que tiene administracion
// $nivel_distrito_rdr= TRUE|FALSE		Es el RDR del distrito
//
// $nivel_club= TRUE|FALSE				Posee nivel de administracion de club
// $nivel_club_id= INT					[id_club] del que tiene administracion
// $nivel_club_presidente= TRUE|FALSE	Es el Presidente del club
//
// $nivel_usuario= TRUE|FALSE			Similar a revisar la variable de session $_SESSION['logged']
// $nivel_usuario_club_id= INT			[id_club] al que el usuario pertenece
// $nivel_usuario_distrito_id= INT		[id_distrito] al que el usuario pertenece
// $nivel_usuario_programa= INT			[id_programa] al que el usuario pertenece
//
// $nivel_rrhh= TRUE|FALSE				Posee nivel de administracion de RRHH
//
// $nivel_evento= TRUE|FALSE			Posee nivel de coordinador del evento
// $nivel_evento_id= INT				[id_evento] del que tiene permisos || <DEPRECATED> Como un usuario puede tener varios roles en distintos eventos, el ID se debe calcular en el momento de consultar por un evento determinado
// $nivel_evento_tesoreria= TRUE|FALSE	Posee nivel de tesorero del evento
//
// Hay que tomar en cuenta las siguientes restricciones:
// 	-Solo se puede presidir un club al que se pertenece
//	-Solo se puede administrar un club al que se pertenece
// 	-Solo se puede representar un distrito al que se pertenece
//	-Solo se puede administrar un distrito al que se pertenece
//	
// Ver la posibilidade agregar un nivel de permiso de Moderador, que tenga los mismos accesos que un RDR y un Presidente pero que aplique a cualquier club y/o distrito
//

session_start();

// Inicializo las variables
$nivel_admin=false;
$nivel_distrito=false;
$nivel_distrito_id=0;
$nivel_distrito_rdr=false;
$nivel_club=false;
$nivel_club_id=0;
$nivel_club_presidente=false;
$nivel_usuario=false;
$nivel_rrhh=false;
$nivel_evento=false;
$nivel_evento_tesoreria=false;


if ($_SESSION['logged']) {
	$nivel_usuario=true;

	//Verifica si el user es ADMIN del Sitio
	$uid_c = $_SESSION['uid'];
	$sql_p = "SELECT * FROM rtc_admin WHERE uid = '$uid_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_admin=true;
	}

	$sql_u = "SELECT * FROM rtc_usr_institucional WHERE user_id = '$uid_c' LIMIT 1";
	$result_u = mysql_query($sql_u);
	$row_u = mysql_fetch_assoc($result_u);
	$club_c=$row_u['club'];
	$distrito_c=$row_u['distrito'];
	
	//Cargo el ID del club y del distrito al que pertenece el socio
	$nivel_usuario_club_id=$club_c;
	$nivel_usuario_distrito_id=$distrito_c;
	$nivel_usuario_programa=$row_u['programa_ri'];

	//Verifica si el user es RDR o ADMIN DISTRITAL
	$sql_p = "SELECT * FROM rtc_distritos WHERE (uid_rdr = '$uid_c' OR uid_admin = '$uid_c') AND id_distrito = '$distrito_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_distrito=true;
		$nivel_distrito_id=$distrito_c;
		if ($row_p['uid_rdr']==$uid_c) {
			$nivel_distrito_rdr=true;
		} else {
			$nivel_distrito_rdr=false;
		}
	}
	//Verifica si el user es MIEMBRO DEL CLUB
	$sql_p = "SELECT * FROM rtc_clubes WHERE (uid_presidente = '$uid_c' OR uid_admin = '$uid_c') AND id_club = '$club_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	$nivel_club_id=$club_c;
	if ($row_p) {
		$nivel_club=true;
		$nivel_club_id=$club_c;
		if ($row_p['uid_presidente']==$uid_c) {
			$nivel_club_presidente=true;
		} else {
			$nivel_club_presidente=false;
		}
	}
	
	//Verifica si el user es ADMIN de RRHH
	$uid_c = $_SESSION['uid'];
	$sql_p = "SELECT * FROM rtc_rrhh_admin WHERE user_id = '$uid_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_rrhh=true;
	}

	//Verifica si el user es Coordinador de algun Evento
	$uid_c = $_SESSION['uid'];
	$sql_p = "SELECT * FROM rtc_eventos_coordinadores WHERE user_id = '$uid_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_evento=true;
	}

	//Verifica si el user es Tesorero del algun Evento
	$uid_c = $_SESSION['uid'];
	$sql_p = "SELECT * FROM rtc_eventos_tesoreria WHERE user_id = '$uid_c' LIMIT 1";
	$result_p = mysql_query($sql_p);
	$row_p = mysql_num_rows($result_p);
	if ($row_p) {
		$nivel_evento_tesoreria=true;
	}

} 
?>