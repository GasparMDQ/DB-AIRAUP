<div id="menudivadmin">
<ul>
<li><a href="../index.php">Volver a la BD</a></li>
<?php 
if ($nivel_admin) {echo "<li><a href=\"paises.php\">Paises</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"provincias.php\">Provincias</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"ciudades.php\">Ciudades</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"periodos.php\">Periodos</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"programas.php\">Programas</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"ambito.php\">Ambitos</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"cargos.php\">Cargos</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"distritos.php\">Distritos</a></li>";}
if ($nivel_admin OR $nivel_distrito) {echo "<li><a href=\"clubes.php\">Clubes</a></li>";}
if ($nivel_admin OR $nivel_club) {echo "<li><a href=\"socios.php\">Socios</a></li>";}
if ($nivel_admin) {echo "<li><a href=\"usuario.php\">Usuarios</a></li>";}
?>
</ul>
</div>
