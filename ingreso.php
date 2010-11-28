<?php
	session_start();

require 'includes/class.php';
if (!isset($_SESSION['uid']) ) {
	session_defaults();
}

$usuario = new Usuario();
$usuario->_checkSession();

if ($_SESSION['logged']) {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ingreso de Usuarios</title>
</head>

<body>

<form action="procesa/registro.php" method="post">
  <table width="350" border="0" align="center">
    <tr>
      <td>Nombre de Usuario:</td>
      <td align="right">        <input type="text" name="user_id" id="user_id" />      </td>
    </tr>
    <tr>
      <td>Clave:</td>
      <td align="right">        <input type="text" name="clave" id="clave" />      </td>
    </tr>
    <tr>
      <td>Email:</td>
      <td align="right">        <input type="text" name="email" id="email" />      </td>
    </tr>
    <tr>
      <td>Nombre:</td>
      <td align="right">        <input type="text" name="nombre" id="nombre" />      </td>
    </tr>
    <tr>
      <td>Apellido:</td>
      <td align="right">        <input type="text" name="apellido" id="apellido" />      </td>
    </tr>
    <tr>
      <td>Fecha de Naciemiento:</td>
      <td align="right">        <input type="text" name="fecha_de_nacimiento" id="fecha_de_nacimiento" />      </td>
    </tr>
    <tr>
      <td>Tipo de Documento:</td>
      <td align="right">        <input type="text" name="tipo_de_documento" id="tipo_de_documento" />      </td>
    </tr>
    <tr>
      <td>Número de documento:</td>
      <td align="right">        <input type="text" name="numero_de_documento" id="numero_de_documento" />      </td>
    </tr>
    <tr>
      <td>Ocupación:</td>
      <td align="right">
        <select name="ocupacion" id="ocupacion">
        </select>      </td>
    </tr>
    <tr>
      <td>Dirección:</td>
      <td align="right">        <input type="text" name="direccion" id="direccion" />      </td>
    </tr>
    <tr>
      <td>Ciudad:</td>
      <td align="right">
        <select name="ciudad" id="ciudad">
        </select>      </td>
    </tr>
    <tr>
      <td>Código Postal:</td>
      <td align="right">        <input type="text" name="codigo_postal" id="codigo_postal" />      </td>
    </tr>
    <tr>
      <td>Provincia / Departamento:</td>
      <td align="right">
        <select name="provincia" id="provincia">
        </select>      </td>
    </tr>
    <tr>
      <td>País:</td>
      <td align="right">
        <select name="pais" id="pais">
        </select>      </td>
    </tr>
    <tr>
      <td>Número de Teléfono:</td>
      <td align="right">        <input type="text" name="telefono" id="telefono" />      </td>
    </tr>
    <tr>
      <td>Número de Celular:</td>
      <td align="right">        <input type="text" name="celular" id="celular" />      </td>
    </tr>
    <tr>
      <td>Programa de RI:</td>
      <td align="right">
        <select name="programa_ri" id="programa_ri">
        </select>      </td>
    </tr>
    <tr>
      <td>Distrito:</td>
      <td align="right">
        <select name="distrito" id="distrito">
        </select>      </td>
    </tr>
    <tr>
      <td>Club:</td>
      <td align="right">
        <select name="club" id="club">
        </select>      </td>
    </tr>
    <tr>
      <td>Perfil público:</td>
      <td align="right">        <input type="checkbox" name="perfil_publico" id="perfil_publico" />      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="submit" id="submit" value="Enviar" />
        <a href="/index.php">index</a></td>
    </tr>
  </table>

</form>
</body>
</html>

<?php 
} else {
	header("Location: index.php");
}
?>

