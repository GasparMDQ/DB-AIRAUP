<div id="menudiv">
<form id="form1" name="form1" method="post" action="../index.php">
<input type="submit" name="button" id="button" value="Volver a la BD" />
</form>
<?php 
if ($nivel_admin) {echo "<a href=\"paises.php\">Paises</a><br />";}
if ($nivel_admin) {echo "<a href=\"provincias.php\">Provincias - Departamentos - Estados</a><br />";}
if ($nivel_admin) {echo "<a href=\"ciudades.php\">Ciudades</a><br />";}
if ($nivel_admin) {echo "<a href=\"programas.php\">Programas</a><br />";}
if ($nivel_admin) {echo "<a href=\"cargos.php\">Cargos</a><br />";}
if ($nivel_admin) {echo "<a href=\"distritos.php\">Distritos</a><br />";}
if ($nivel_admin OR $nivel_distrito) {echo "<a href=\"clubes.php\">Clubes</a><br />";}
if ($nivel_admin OR $nivel_club) {echo "<a href=\"socios.php\">Socios</a><br />";}
if ($nivel_admin) {echo "<a href=\"usuario.php\">Usuarios</a><br />";}
?>
</div>
