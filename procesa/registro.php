<?php
	session_start();

require '../includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
}
if ($_SESSION['logged']) {

	echo "ERROR. PARA REGISTRAR DEBE ESTAR DESLOGUEADO";

} else {

	echo "LISTO PARA REGISTRAR";

}
?>