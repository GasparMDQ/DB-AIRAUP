<?php
if ($_SESSION['logged']) { ?>

<table class="tacceso"><tr>

<?php 
$consulta = $_SESSION['uid'];
$sql = sprintf("SELECT * FROM rtc_admin WHERE " . "uid = \"$consulta\" LIMIT 1");
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
if ($row) { ?>
<td id="extrasacceso">
<form id="form1" name="form1" method="post" action="/admin/index.php"><input type="submit" name="button" id="button" value="Administración" /></form>
</td>
<?php } ?>

<td>
<form id="logout" name="formulario_logout" method="post" action="/procesa/logout.php">
	Bienvenid@ <?php echo $_SESSION['nombre']?>
  	<input type="submit" name="enviar" id="enviar" value="Salir" />
</form>
</td>

</tr></table>

<?php } else { ?>
<table class="tacceso"><tr>

<?php if ($_SESSION['failed']) { ?> <td id="extracceso"> <?php echo "<span style='color: #FF0000'>Nombre de Usuario o Contrase&ntilde;a Incorrectos</span>";?> </td> <?php } ?>

<td>
<form id="login" name="formulario_login" method="post" action="/procesa/login.php">

  Usuario: 
<input name="user" type="text" id="user" size="10" maxlength="20" />
	Contraseña: <input name="pass" type="password" id="pass" size="10" maxlength="16" />
  	<input type="submit" name="enviar" id="enviar" value="Entrar" />
	<input type="button" value="Registrarse" onclick="window.location = '/socios_alta.php';">
</form>
</td>

</tr></table>
<?php }?>