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
		if ($row_p['uid_presidente']==$uid_c) {
			$nivel_club_presidente=true;
		} else {
			$nivel_club_presidente=false;
		}
	}

} 
?>