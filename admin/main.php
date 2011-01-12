<ul id="nav" class="dropdown dropdown-horizontal">
	<li><a href="../index.php">Volver a la BD</a></li>
	<li><span class="dir">Regional</span>
        <!-- submenu-->
		<ul>
			<?php if ($nivel_admin) {?>	<li><a href="paises.php">Paises</a></li><?php } ?>
			<?php if ($nivel_admin) {?>	<li><a href="provincias.php">Provincias</a></li><?php } ?>
			<?php if ($nivel_admin) {?>	<li><a href="ciudades.php">Ciudades</a></li><?php } ?>
		</ul>
	</li>
	<li><span class="dir">Institucional</span>
		<ul>
       	<!-- submenu de submenu-->
		<li><span class="dir">Configuracion</span>
    	   	<!-- submenu de submenu-->
			<ul>
				<?php if ($nivel_admin) {?>	<li><a href="periodos.php">Periodos</a></li><?php } ?>
				<?php if ($nivel_admin) {?>	<li><a href="programas.php">Programas</a></li><?php } ?>
				<?php if ($nivel_admin) {?>	<li><a href="ambito.php">Ambitos</a></li><?php } ?>
				<?php if ($nivel_admin) {?>	<li><a href="cargos.php">Cargos</a></li><?php } ?>
			</ul>
		</li>
			<?php if ($nivel_admin) {?>	<li><a href="distritos.php">Distritos</a></li><?php } ?>
			<?php if ($nivel_admin OR $nivel_distrito) {?>	<li><a href="clubes.php">Clubes</a></li><?php } ?>
			<?php if ($nivel_admin OR $nivel_club) {?>	<li><a href="socios.php">Socios</a></li><?php } ?>
			<?php if ($nivel_admin) {?>	<li><a href="usuario.php">Usuarios</a></li><?php } ?>
		</ul>
	</li>
	<li><span class="dir">RRHH</span>
       	<!-- submenu de submenu-->
		<ul>
		<li><span class="dir">Importar</span>
    	   	<!-- submenu de submenu-->
			<ul>
			<?php if ($nivel_admin) {?>	<li><a href="rrhh_imp_login.php">Zoho - Login</a></li><?php } ?>
			<?php if ($nivel_admin) {?>	<li><a href="rrhh_imp_inst.php">Zoho - Institucional</a></li><?php } ?>
			</ul>
		</li>
		<li><span class="dir">Mesas</span>
    	   	<!-- submenu de submenu-->
			<ul>
			<?php if ($nivel_admin OR $nivel_rrhh) {?>	<li><a href="rrhh_eventos_mesas.php">Mesas</a></li><?php } ?>
			<?php if ($nivel_admin OR $nivel_rrhh) {?><li><a href="rrhh_eventos_modulos.php">Modulos</a></li><?php } ?>
			<?php if ($nivel_admin OR $nivel_rrhh) {?><li><a href="rrhh_eventos_asistencia.php">Asistencia</a></li><?php } ?>
			</ul>
		</li>
		</ul>
	</li>
</ul>