<?php 
include 'includes/header.php';
require_once 'includes/permisos.php';

if ($nivel_rrhh) {
?>
<div>Sección en desarrollo</div>
<?php 
} else {
?>
<div>Permisos insuficientes</div>
<?php 
}
include 'includes/footer.php';
?>