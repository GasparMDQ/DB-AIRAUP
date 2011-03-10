<?php
include 'header.php';

$esadmin=false;

if ($nivel_admin OR $nivel_evento OR $nivel_distrito OR $nivel_club) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

if (isset($_POST['user_id'])){
	$user_id=intval($_POST['user_id']);
} else if (isset($_GET['user_id'])) {
		$user_id=intval($_GET['user_id']);
	} else {
		$user_id=0;
}


//	COMIENZO DEL CUERPO

if ($user_id!=0) {

//	CHEQUEO SI TIENE PERMISO PARA VER LA FICHA (ADMIN, RDR, PRESIDENTE, COORDINADOR)

	$permiso=false;	//INICIALIZO LA VARIABLE PARA EVITAR FALSOS POSITIVOS
	
	if ($nivel_admin) {
		$permiso=true;
	}	//ES ADMIN


	$sql="SELECT * FROM rtc_usr_institucional WHERE rtc_usr_institucional.user_id='$user_id' AND rtc_usr_institucional.distrito='$nivel_distrito_id' LIMIT 1";
	$result=mysql_query($sql);
	if ($nivel_distrito AND mysql_num_rows($result)) {
		$permiso=true;
	}	//ES SU RDR
	
	$sql="SELECT * FROM rtc_usr_institucional WHERE rtc_usr_institucional.user_id='$user_id' AND rtc_usr_institucional.club='$nivel_club_id' LIMIT 1";
	$result=mysql_query($sql);
	if ($nivel_club AND mysql_num_rows($result)) {
		$permiso=true;
	}	//ES SU PRESIDENTE

	$sql="SELECT * FROM rtc_eventos_coordinadores, rtc_eventos_inscripciones WHERE rtc_eventos_inscripciones.user_id='$user_id' AND rtc_eventos_inscripciones.evento_id=rtc_eventos_coordinadores.evento_id AND rtc_eventos_coordinadores.user_id='$nivel_usuario_id' LIMIT 1";
	$result=mysql_query($sql);
	if ($nivel_evento AND mysql_num_rows($result)) {
		$permiso=true;
	}	//ES COORDINADOR

	if ($permiso) {

		setlocale(LC_ALL, 'es_ES');
	
//	Cargo las tablas del usuario a consultar
//		$sql = "SELECT rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_usr_personales.fecha_de_nacimiento, rtc_usr_personales.direccion, rtc_usr_personales.numero_de_documento, rtc_usr_personales.contacto_nombre, rtc_usr_personales.contacto_telefono, rtc_usr_personales.contacto_relacion, rtc_usr_personales.telefono, rtc_usr_personales.celular, rtc_ciudades.ciudad, rtc_provincias.provincia, rtc_paises.pais, rtc_cfg_tipos_de_documentos.tipo FROM rtc_usr_personales, rtc_ciudades, rtc_provincias, rtc_paises, rtc_cfg_tipos_de_documentos WHERE rtc_usr_personales.user_id = '$user_id' AND rtc_usr_personales.ciudad=rtc_ciudades.id_ciudades AND rtc_usr_personales.pais=rtc_paises.id_paises AND rtc_usr_personales.provincia=rtc_provincias.id_provincia AND rtc_usr_personales.tipo_de_documento=rtc_cfg_tipos_de_documentos.id LIMIT 1";
		$sql = "SELECT * FROM rtc_usr_personales WHERE user_id = '$user_id' LIMIT 1";
		$result = mysql_query($sql);
		$datos_personales = mysql_fetch_assoc($result);

		$sql = "SELECT * FROM rtc_usr_login WHERE uid = '$user_id' LIMIT 1";
		$result = mysql_query($sql);
		$datos_login = mysql_fetch_assoc($result);
	
//		$sql = "SELECT rtc_usr_institucional.fecha_de_modificacion, rtc_distritos.distrito, rtc_clubes.club, rtc_cfg_programas.programa FROM rtc_usr_institucional, rtc_distritos, rtc_clubes, rtc_cfg_programas WHERE rtc_usr_institucional.user_id = '$user_id' AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito AND rtc_usr_institucional.club=rtc_clubes.id_club AND rtc_usr_institucional.programa_ri=rtc_cfg_programas.id_programa LIMIT 1";
		$sql = "SELECT * FROM rtc_usr_institucional WHERE user_id = '$user_id' LIMIT 1";
		$result = mysql_query($sql);
		$datos_institucionales = mysql_fetch_assoc($result);

		$sql = "SELECT * FROM rtc_usr_salud WHERE user_id = '$user_id' LIMIT 1";
		$result = mysql_query($sql);
		$datos_salud = mysql_fetch_assoc($result);
?>
<div>
	<h1>Ficha de <?php echo $datos_personales['nombre']." ".$datos_personales['apellido']; ?></h1>
</div>
<div>
<?php 
//	Calculo la informacion extendida
	$sql="SELECT * FROM rtc_clubes WHERE id_club='".$datos_institucionales['club']."' LIMIT 1";
	$result = mysql_query($sql);
	$club = mysql_fetch_assoc($result);
	$sql="SELECT * FROM rtc_distritos WHERE id_distrito='".$datos_institucionales['distrito']."' LIMIT 1";
	$result = mysql_query($sql);
	$distrito = mysql_fetch_assoc($result);
	$sql="SELECT * FROM rtc_cfg_programas WHERE id_programa='".$datos_institucionales['prorgama_ri']."' LIMIT 1";
	$result = mysql_query($sql);
	$programa = mysql_fetch_assoc($result);
?>

	<h2>Institucional</h2>
<?php if ($datos_institucionales['fecha_de_modificacion']=="") { ?>
	<p>
    	<span class="muestra_alarma">Nunca ingresados</span>
	</p>
<?php } else { ?>
	<p>
		<?php if ($datos_institucionales['fecha_de_modificacion']=="0000-00-00 00:00:00") { echo "<span class=\"muestra_amarillo\">DATOS SIN ACTUALIZAR</span><br />"; } else { ?>
        Actualizados al <?php echo strftime ("%d de %B de %Y", strtotime($datos_institucionales['fecha_de_modificacion']));?><br />
		<?php } ?>
    	Edad: <?php echo getAge($datos_personales['fecha_de_nacimiento']);?><br />
		<?php echo $programa['programa'];?> Club <?php echo $club['club'];?>, Distrito <?php echo $distrito['distrito'];?>
	</p>
    <?php } ?>
</div>

<div>
<?php 
//	Calculo la informacion extendida
	$sql="SELECT * FROM rtc_ciudades WHERE id_ciudades='".$datos_personales['ciudad']."' LIMIT 1";
	$result = mysql_query($sql);
	$ciudad = mysql_fetch_assoc($result);
	$sql="SELECT * FROM rtc_provincias WHERE id_provincia='".$datos_personales['provincia']."' LIMIT 1";
	$result = mysql_query($sql);
	$provincia = mysql_fetch_assoc($result);
	$sql="SELECT * FROM rtc_paises WHERE id_paises='".$datos_personales['pais']."' LIMIT 1";
	$result = mysql_query($sql);
	$pais = mysql_fetch_assoc($result);
	$sql="SELECT * FROM rtc_cfg_tipos_de_documentos WHERE id='".$datos_personales['tipo_de_documento']."' LIMIT 1";
	$result = mysql_query($sql);
	$tipo_dni = mysql_fetch_assoc($result);
?>
	<h2>Personales</h2>
<?php if ($datos_personales['fecha_de_modificacion']=="") { ?>
	<p>
    	<span class="muestra_alarma">Nunca ingresados</span>
	</p>
<?php } else { ?>
	<p>
		<?php if ($datos_personales['fecha_de_modificacion']=="0000-00-00 00:00:00") { echo "<span class=\"muestra_amarillo\">DATOS SIN ACTUALIZAR</span><br />"; } else { ?>
        Actualizados al <?php echo strftime ("%d de %B de %Y", strtotime($datos_personales['fecha_de_modificacion']));?><br />
		<?php } ?>
    	Tel&eacute;fono: <?php echo $datos_personales['telefono'];?><br />
    	Celular: <?php echo $datos_personales['celular'];?><br />
    	Domicilio: <?php echo $datos_personales['direccion'].", ".$ciudad['ciudad'].", ".$provincia['provincia'].", ".$pais['pais'];?><br />
	    Email: <?php echo $datos_login['email'];?><br />
		<?php echo $tipo_dni['tipo'];?>: <?php echo $datos_personales['numero_de_documento'];?><br />
	    Fecha de Nacimiento: <?php echo strftime ("%d de %B de %Y", strtotime($datos_personales['fecha_de_nacimiento'])) ;?>
	</p>
    <?php } ?>
</div>

<div>
  <h2>Salud</h2>
<?php if ($datos_salud['fecha_de_modificacion']=="") { ?>
	<p>
    	<span class="muestra_alarma">Nunca ingresados</span>
	</p>
<?php } else { ?>
    <p>
		<?php if ($datos_salud['fecha_de_modificacion']=="0000-00-00 00:00:00") { echo "<span class=\"muestra_amarillo\">DATOS SIN ACTUALIZAR</span>"; } else { ?>
        Actualizados al <?php echo strftime ("%d de %B de %Y", strtotime($datos_salud['fecha_de_modificacion']));?>
		<?php } ?>
    <p>
  <p>
    	Obra Social: <?php echo $datos_salud['obrasocial'];?><br />
    	N&uacute;mero de Afiliado: <?php echo $datos_salud['nroobrasocial'];?>
  </p>
    <p>
  <h3>Otros</h3>
    	Grupo Sanguineo: <?php echo $datos_salud['gruposanguineo'];?><br />
    	Fuma: <?php if ($datos_salud['fuma']) { echo "Si<br />"; } else { echo "No<br />";} ?>
    	Lateralidad: <?php echo $datos_salud['lateralidad'];?><br />
	</p>
    <p>
  <h3>Dietas</h3>
    	Vegetariano: <?php if ($datos_salud['dietaveg']) { echo "Si<br />"; } else { echo "No<br />";} ?>
		<?php if ($datos_salud['dietaveg']) { echo "Detalle: ".$datos_salud['dietavegdesc']."<br />"; } ?>
    	Dieta Especial: <?php if ($datos_salud['dietaesp']) { echo "Si<br />"; } else { echo "No<br />";} ?>
		<?php if ($datos_salud['dietaesp']) { echo "Detalle: ".$datos_salud['dietaespdesc']."<br />"; } ?>
  </p>
    <p>
  <h3>Antecedentes de Enfermedad</h3>
    	&iquest;Tiene alguna enfermedad que requiera peri&oacute;dicamente tratamiento o control m&eacute;dico? <?php if ($datos_salud['enftpcm']) { echo "Si<br />"; } else { echo "No<br />";} ?>
		Durante los &uacute;ltimos 3 a&ntilde;os: &iquest;fue internado alguna vez?  <?php if ($datos_salud['intu3a']) { echo "Si<br />"; } else { echo "No<br />";}?>
		&iquest;Tiene alg&uacute;n tipo de alergia?  <?php if ($datos_salud['alergia']) { echo "Si<br />"; } else { echo "No<br />";} ?>
		<?php if ($datos_salud['alergia']) { echo "Detalle: ".$datos_salud['alergiadesc']."<br />"; } ?>
		<?php if ($datos_salud['alergia']) { echo "&iquest;Recibe tratamiento permanente para la alergia? "; if ($datos_salud['alergia'] && $datos_salud['alergiatratamiento']) { echo "Si<br />"; } else { echo "No<br />";} } ?>
		<?php if ($datos_salud['alergia'] && $datos_salud['alergiatratamiento']) { echo "Detalle: ".$datos_salud['alergiadesc']."<br />"; } ?>
	</p>
    <p>
  <h3>Tratamientos</h3>
    	&iquest;Recibe tratamiento m&eacute;dico? <?php if ($datos_salud['tratamiento']) { echo "Si<br />"; } else { echo "No<br />";} ?>
		<?php if ($datos_salud['tratamiento']) { echo "Detalle: ".$datos_salud['tratamientodesc']."<br />"; } ?>
		&iquest;Tuvo alg&uacute;n tipo de cirug&iacute;a? <?php if ($datos_salud['opera']) { echo "Si<br />"; } else { echo "No<br />";}?>
		<?php if ($datos_salud['opera']) { echo "Edad: ".$datos_salud['operadesc']."<br />Detalles: ".$datos_salud['operadesc']."<br />";  } ?>
		&iquest;Presenta alg&uacute;n tipo de limitaci&oacute;n f&iacute;sica? <?php if ($datos_salud['limitacionfisica']) { echo "Si<br />"; } else { echo "No<br />";} ?>
		Otros problemas de salud: <?php echo $datos_salud['otrossalud'];?><br />
	</p>
<?php } ?>
</div>


<div>
	<h2>Contacto de Emergencia</h2>
<?php if ($datos_personales['fecha_de_modificacion']=="") { ?>
	<p>
    	<span class="muestra_alarma">Nunca ingresados</span>
	</p>
<?php } else { ?>
	<p>
		<?php if ($datos_personales['fecha_de_modificacion']=="0000-00-00 00:00:00") { echo "<span class=\"muestra_amarillo\">DATOS SIN ACTUALIZAR</span><br />"; } else { ?>
        Actualizados al <?php echo strftime ("%d de %B de %Y", strtotime($datos_personales['fecha_de_modificacion']));?><br />
		<?php } ?>
    	Nombre: <?php echo $datos_personales['contacto_nombre'];?><br />
	    Telefono: <?php echo $datos_personales['contacto_telefono'];?><br />
	    Relacion: <?php echo $datos_personales['contacto_relacion'];?>
	</p>
    <?php } ?>
</div>


<?php
	setlocale(LC_ALL, '');
	} else {
		echo "Acceso no autorizado";
	}
} //	FIN DEL $user_id!=0
include 'footer.php';?>

