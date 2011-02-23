<div id="acceso">

<?php
if ($_SESSION['logged']) { ?>
	<div id="admindiv">
	<ul><li>Administrar</li>
	<li><a href="/socios_perfil.php">Datos Personales</a></li>
<?php 
	//Verifica si es administrador del sitio
	$consulta = $_SESSION['uid'];
	$sql = sprintf("SELECT * FROM rtc_admin WHERE uid = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) { ?>
		<li><a href="/admin/index.php">Base de Datos</a></li>
	<?php }

	//Verifica si es administrador o rdr de algun distrito
	$consulta = $_SESSION['uid'];
	$sql = sprintf("SELECT * FROM rtc_distritos WHERE uid_rdr = \"$consulta\" OR uid_admin = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) { ?>
		<li><a href="/admin/clubes.php">Distrito</a></li>
	<?php }

	//Verifica si es administrador o presidente de algun club
	$consulta = $_SESSION['uid'];
	$sql = sprintf("SELECT * FROM rtc_clubes WHERE uid_presidente = \"$consulta\" OR uid_admin = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) { ?>
		<li><a href="/admin/socios.php">Club</a></li>
	<?php }
	
	//Verifica si es administrador de RRHH
	$consulta = $_SESSION['uid'];
	$sql = sprintf("SELECT * FROM rtc_rrhh_admin WHERE user_id = \"$consulta\" LIMIT 1");
	$result = mysql_query($sql);
	$row = mysql_fetch_object($result);
	if ($row) { ?>
		<li><a href="/admin/index.php">RRHH</a></li>
	<?php } ?>

	</ul>
</div> <!--Fin del div #admindiv -->

		<div id="userdiv"><form id="logout" name="formulario_logout" method="post" action="/procesa/logout.php"><?php echo $_SESSION['username']?>
			<input type="submit" name="salir" id="salir" value="Salir" /></form>
		</div>
<?php } else { ?>
	<div id="userdiv">
		<form id="login" name="formulario_login" method="post" action="/procesa/login.php">
        	Email / Usuario:
        	  <input name="user" type="text" id="user" size="10" maxlength="64" />
	        Contraseña:<input name="pass" type="password" id="pass" size="10" maxlength="32" /><input type="submit" name="entrar" id="entrar" value="Entrar" /><br /><input type="button" name="registro" id="registro"  value="Registrarse" onclick="window.location = '/socios_alta.php';">
		</form>
	</div>
	<?php if ($_SESSION['failed']) { ?>
    	<div id="errordiv"><span style='color: #FF0000'>Nombre de Usuario o Contrase&ntilde;a Incorrectos</span></div>
    	<div id="recupera"><a href="recupera.php">recupera tu contraseña</a></div>
  <?php } ?>
<?php }?>
<!-- Fin del div #acceso -->
</div> 