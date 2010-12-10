<?php
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';

function session_defaults() {
	$_SESSION['logged'] = false;
	$_SESSION['uid'] = 0;
	$_SESSION['username'] = '';
	$_SESSION['nombre'] = '';
	$_SESSION['failed'] = false;
//	if ($_SESSION['seccion']!='asocios'){
//		$_SESSION['clave'] = '';
//		$_SESSION['clave2'] = '';
//	}	
//	$_SESSION['seccion'] = 'main';
}

class Usuario {
	
	var $date; // fecha
	var $uid = 0; // uid logueado

function _logout() {
	session_defaults();
}

function User() {
//	$this->date = $GLOBALS['date'];
	if ($_SESSION['logged']) {
		$this->_checkSession();
	}
}

function _checkLogin($username, $password) {
	$username = mysql_real_escape_string(htmlspecialchars($username));
	$password = hash('sha512', $username.$password.'1s3a3l7t');
	$sql = sprintf("SELECT * FROM rtc_usr_login WHERE " . "user_id = \"$username\" AND " . "clave = \"$password\" ");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ( $row ) {
		$this->_setSession($row);
		return true;
	} else {
		$this->_logout();
		$_SESSION['failed'] = true;
		return false;
	}
} 

function _setSession($valores, $init = true) {
	$this->uid = $valores->uid;
	$_SESSION['uid'] = $this->uid;
	$_SESSION['username'] = mysql_real_escape_string($valores->email);
	$_SESSION['nombre'] = htmlspecialchars($valores->nombre);
	$_SESSION['logged'] = true;
	$_SESSION['failed'] = false;
	if ($init) {
		$sql = sprintf("SELECT * FROM rtc_usr_login WHERE " . "uid = \"$this->uid\" ");
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$session = mysql_real_escape_string(session_id());
		$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		$fechaold = $row['fecha_acceso_actual'];
		$fecha = date("c");
		$sql = sprintf("UPDATE rtc_usr_login SET session = \"$session\", ip = \"$ip\", fecha_ultimo_acceso = \"$fechaold\", fecha_acceso_actual = \"$fecha\" WHERE " . "uid = \"$this->uid\" ");
		mysql_query($sql);
//		$_SESSION['seccion'] = 'main';
//		$_SESSION['clave'] = '';
//		$_SESSION['clave2'] = '';
	}
} 

function _checkSession() {
	$username = mysql_real_escape_string($_SESSION['username']);
	$session = mysql_real_escape_string(session_id());
	$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
	$sql = sprintf("SELECT * FROM rtc_usr_login WHERE " . "(email = \"$username\") AND " . "(session = \"$session\") AND (ip = \"$ip\")");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row ) {
		$this->_setSession($row, false);
	} else {
		$this->_logout();
	}
} 
} 

?>
