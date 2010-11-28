<?php 
include 'includes/header.php';
@$usuario = new Usuario();
@$usuario->_checkSession();
?>
<table id="main" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="4" align="center"><p></p></td>
      </tr>
      <tr>
        <td width="300" align="center"><p><img src="images/inscripciones.png" alt="Inscripciones" width="128" height="128" /><br />
          Inscripciones</p></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td width="150" align="center" valign="middle">
<?php
if ($_SESSION['logged']) {
?>
        <form id="nuevoevento" name="nuevoevento" method="post" action="inscripciones.php">
          <input type="submit" name="nuevo" id="nuevo" value="Nuevo Evento" />
        </form>
<?php
	}
?>
        </td>
  </tr>
      <tr>
        <td colspan="4" align="center">
<?php
	if (isset($_POST['nuevo'])) {
		include 'includes/inscripciones/nuevo.php';
	} else {
		echo "&lt;Listado de Eventos&gt;";
		include 'includes/inscripciones/listado.php';
	}
?>
        </td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
<?php 
include 'includes/footer.php';
?>