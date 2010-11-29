<div id="acceso">

<?php
if ($_SESSION['logged']) { ?>
	<div id="admindiv">
		<form id="admin" name="admin" method="post" action="#">
		  Administrar  
		  <input type="button" name="dpbutton" id="dpbutton" value="Datos Personales" onclick="window.location = '/socios_perfil.php';">

	
<?php 
	//Verifica si es administrador del sitio
	$consulta = $_SESSION['uid'];
	$sql = sprintf("SELECT * FROM rtc_admin WHERE " . "uid = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) { ?>
	        	<input type="button" name="bdbutton" id="bdbutton" value="Base de Datos" onclick="window.location = '/admin/index.php';">
	<?php }

	//Verifica si es administrador o rdr de algun distrito
	$consulta = $_SESSION['uid'];
	$sql = sprintf("SELECT * FROM rtc_distritos WHERE " . "uid_rdr = \"$consulta\" OR uid_admin = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) { ?>
	        	<input type="button" name="bdbutton" id="bdbutton" value="Distrito" onclick="window.location = '/admin/index.php';">
	<?php }

	//Verifica si es administrador o presidente de algun club
	$consulta = $_SESSION['uid'];
	$sql = sprintf("SELECT * FROM rtc_clubes WHERE " . "uid_presidente = \"$consulta\" OR uid_admin = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) { ?>
	        	<input type="button" name="bdbutton" id="bdbutton" value="Club" onclick="window.location = '/admin/index.php';">
	<?php } ?>

		</form>
	</div> <!--Fin del div #admindiv -->

		<div id="userdiv"><form id="logout" name="formulario_logout" method="post" action="/procesa/logout.php">
			Bienvenid@ <?php echo $_SESSION['nombre']?>
			<input type="submit" name="salir" id="salir" value="Salir" /></form>
		</div>
<?php } else { ?>
	<div id="userdiv">
		<form id="login" name="formulario_login" method="post" action="/procesa/login.php">
        	Usuario:<input name="user" type="text" id="user" size="10" maxlength="20" />
	        Contraseña:<input name="pass" type="password" id="pass" size="10" maxlength="16" />
	        <input type="submit" name="entrar" id="entrar" value="Entrar" />
	        <input type="button" name="registro" id="registro"  value="Registrarse" onclick="window.location = '/socios_alta.php';">
		</form>
	</div>
	<?php if ($_SESSION['failed']) { ?>
    	<div id="errordiv">
			<span style='color: #FF0000'>Nombre de Usuario o Contrase&ntilde;a Incorrectos</span>
        </div>
	<?php } ?>
<?php }?>
<!-- Fin del div #acceso -->
</div> 