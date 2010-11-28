<?php
include 'includes/header.php';
@$usuario = new Usuario();
@$usuario->_checkSession();

if ($_SESSION['logged']) {
	include 'includes/perfil_secciones.php';
	if (isset($_POST['seccion'])) {
		include 'includes/perfil/'.$_POST['seccion'].'.php';
	}
} else {
?>
   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="40">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h1>Perfil de Socios</h1></td>
      <td align="left"><img src="../images/socios_perfil.png" alt="Socios" width="48" height="48" hspace="0" vspace="0" border="0" align="right" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Para ingresar al perfil de Socio primero deber ingresar al sistema. En caso de no estar registrado en el mismo lo puede hacer desde <a href="socios_alta.php"><strong>aqui</strong></a>.</td>
      <td>&nbsp;</td>
    </tr>
  </table>

<?php
}

include 'includes/footer.php';
?>