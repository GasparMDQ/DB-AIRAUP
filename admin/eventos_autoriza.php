<?php
include 'header.php';

$esadmin=false;
$nivel_evento=false;

if ($nivel_admin OR $nivel_evento OR $nivel_distrito OR $nivel_club) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}

if (isset($_POST['evento'])){
	$evento=intval($_POST['evento']);
} else if (isset($_GET['evento'])) {
		$evento=intval($_GET['evento']);
	} else {
		$evento=0;
}

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Autoriza RDR") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));

// VERIFICAR QUE EL USUARIO CUENTA CON LOS PERMISOS NECESARIOS PARA ESTE TIPO DE AUTORIZACION
	$sql="SELECT * FROM rtc_usr_institucional WHERE rtc_usr_institucional.user_id='$user_act' AND rtc_usr_institucional.distrito='$nivel_distrito_id' LIMIT 1";
	$result=mysql_query($sql);

	if ($nivel_admin OR ($nivel_distrito AND mysql_num_rows($result))){
		$sql="UPDATE rtc_eventos_preinscripciones SET ok_distrito='1' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
		$result=mysql_query($sql);
	} else {
		echo "No esta autorizado a realizar esta accion";
	}
}

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Desautoriza RDR") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));

// VERIFICAR QUE EL USUARIO CUENTA CON LOS PERMISOS NECESARIOS PARA ESTE TIPO DE AUTORIZACION
	$sql="SELECT * FROM rtc_usr_institucional WHERE rtc_usr_institucional.user_id='$user_act' AND rtc_usr_institucional.distrito='$nivel_distrito_id' LIMIT 1";
	$result=mysql_query($sql);

	if ($nivel_admin OR ($nivel_distrito AND mysql_num_rows($result))){
		$sql="UPDATE rtc_eventos_preinscripciones SET ok_distrito='0' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
		$result=mysql_query($sql);
	} else {
		echo "No esta autorizado a realizar esta accion";
	}

}
 // FIN OK DISTRITAL 

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Autoriza Club") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));

// VERIFICAR QUE EL USUARIO CUENTA CON LOS PERMISOS NECESARIOS PARA ESTE TIPO DE AUTORIZACION
	$sql="SELECT * FROM rtc_usr_institucional WHERE rtc_usr_institucional.user_id='$user_act' AND rtc_usr_institucional.club='$nivel_club_id' LIMIT 1";
	$result=mysql_query($sql);

	if ($nivel_admin OR ($nivel_club AND mysql_num_rows($result))){
		$sql="UPDATE rtc_eventos_preinscripciones SET ok_club='1' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
		$result=mysql_query($sql);
	} else {
		echo "No esta autorizado a realizar esta accion";
	}
}

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Desautoriza Club") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));

// VERIFICAR QUE EL USUARIO CUENTA CON LOS PERMISOS NECESARIOS PARA ESTE TIPO DE AUTORIZACION
	$sql="SELECT * FROM rtc_usr_institucional WHERE rtc_usr_institucional.user_id='$user_act' AND rtc_usr_institucional.club='$nivel_club_id' LIMIT 1";
	$result=mysql_query($sql);

	if ($nivel_admin OR ($nivel_club AND mysql_num_rows($result))){
		$sql="UPDATE rtc_eventos_preinscripciones SET ok_club='0' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
		$result=mysql_query($sql);
	} else {
		echo "No esta autorizado a realizar esta accion";
	}

}
 // FIN OK CLUB

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Autoriza Pago") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));

// VERIFICAR QUE EL USUARIO CUENTA CON LOS PERMISOS NECESARIOS PARA ESTE TIPO DE AUTORIZACION
	$sql="SELECT * FROM rtc_eventos_tesoreria WHERE rtc_eventos_tesoreria.user_id='$nivel_usuario_id' AND rtc_eventos_tesoreria.evento_id='$evento_act' LIMIT 1";
	$result=mysql_query($sql);

	if ($nivel_admin OR ($nivel_evento_tesoreria AND mysql_num_rows($result))){
		$sql="UPDATE rtc_eventos_preinscripciones SET ok_tesoreria='1' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
		$result=mysql_query($sql);
	} else {
		echo "No esta autorizado a realizar esta accion";
	}
}

if (isset($_POST['user']) && isset($_POST['evento']) && isset($_POST['button']) && $_POST['button']=="Desautoriza Pago") {

	$user_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['user']),0,10))); 
	$evento_act = mysql_real_escape_string(intval(substr(htmlspecialchars($_POST['evento']),0,10)));

// VERIFICAR QUE EL USUARIO CUENTA CON LOS PERMISOS NECESARIOS PARA ESTE TIPO DE AUTORIZACION
	$sql="SELECT * FROM rtc_eventos_tesoreria WHERE rtc_eventos_tesoreria.user_id='$nivel_usuario_id' AND rtc_eventos_tesoreria.evento_id='$evento_act' LIMIT 1";
	$result=mysql_query($sql);

	if ($nivel_admin OR ($nivel_evento_tesoreria AND mysql_num_rows($result))){
		$sql="UPDATE rtc_eventos_preinscripciones SET ok_tesoreria='0' WHERE user_id='$user_act' AND evento_id='$evento_act' ";
		$result=mysql_query($sql);
	} else {
		echo "No esta autorizado a realizar esta accion";
	}

}
 // FIN OK TESORERIA




?>
<div>
  <h2>Autorizaciones</h2>
</div>
<?php
	$hoy=date("c");
	$sql="SELECT * FROM rtc_eventos WHERE fecha_fin>='$hoy'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
	$evento=$row['id'];
	$requiere_club=$row['ok_club'];
	$requiere_distrito=$row['ok_distrito'];
	$requiere_tesoreria=$row['ok_tesoreria'];
	
?>

<h2>Estado de Autorizaciones a <?php echo $row['nombre'];?></h2>
<?php
		//SI ES ADMIN VE TODAS LAS AUTORIZACIONES, SINO SOLO LAS DE SU DISTRITO Y/O CLUB
//		if ($nivel_admin) {
			$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito AS distrito_nombre, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria, rtc_usr_institucional.distrito, rtc_usr_institucional.club FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
//		} else if ($nivel_tesoreria) {
//			$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito AS distrito_nombre, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria, rtc_usr_institucional.distrito, rtc_usr_institucional.club FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales, rtc_eventos_tesoreria WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club AND rtc_usr_institucional.distrito='$nivel_distrito_id' ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
//		} else if ($nivel_distrito) {
//			$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito AS distrito_nombre, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria, rtc_usr_institucional.distrito, rtc_usr_institucional.club FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club AND rtc_usr_institucional.distrito='$nivel_distrito_id' ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
//		} else if ($nivel_club) {
//			$sql_p = "SELECT rtc_eventos_preinscripciones.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito AS distrito_nombre, rtc_eventos_preinscripciones.ok_distrito, rtc_eventos_preinscripciones.ok_club, rtc_eventos_preinscripciones.ok_tesoreria, rtc_usr_institucional.distrito, rtc_usr_institucional.club FROM rtc_clubes, rtc_eventos_preinscripciones, rtc_usr_institucional, rtc_distritos, rtc_usr_personales WHERE rtc_eventos_preinscripciones.evento_id='$evento' AND rtc_eventos_preinscripciones.user_id = rtc_usr_institucional.user_id AND rtc_usr_institucional.distrito = rtc_distritos.id_distrito AND rtc_usr_personales.user_id = rtc_eventos_preinscripciones.user_id AND rtc_usr_institucional.club=rtc_clubes.id_club AND rtc_usr_institucional.club='$nivel_club_id' ORDER BY rtc_distritos.distrito, rtc_clubes.club, rtc_usr_personales.apellido, rtc_usr_personales.nombre";
//		} else {
			//VER QUE HACER ACA
//		}
		
		$result_p = mysql_query($sql_p);
		$cantidad_inscriptos = mysql_num_rows($result_p);
		echo "Total de Preinscriptos: ".$cantidad_inscriptos." <br />";
		while($rows = mysql_fetch_assoc($result_p))
		{
			$muestra_codigo=false;
			$linea_de_codigo="";
			$user_id = mysql_real_escape_string($rows['user_id']); 
			$nombre = mysql_real_escape_string($rows['nombre'])." ".mysql_real_escape_string($rows['apellido']); 
			$distrito = mysql_real_escape_string($rows['distrito_nombre']); 

			$linea_de_codigo=$linea_de_codigo."<form id=\"form\" name=\"form\" method=\"POST\" action=\"eventos_autoriza.php\">";

			$linea_de_codigo=$linea_de_codigo."<div class=\"enlinea\"><a href=\"eventos_fichas.php?user_id=".$rows['user_id']."\">".$nombre."</a> (".$distrito.")</div>";
			
			$disabled="disabled";
			if ($nivel_admin OR ($nivel_club AND $nivel_club_id==$rows['club'])) {
				$disabled="";
				$muestra_codigo=true;
			}

			if(!$requiere_club){
				if($rows['ok_club']) {
					$linea_de_codigo=$linea_de_codigo."<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Desautoriza Club\"/>";
				} else {
					$linea_de_codigo=$linea_de_codigo."<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Autoriza Club\"/>";
				}
			} else {
				$linea_de_codigo=$linea_de_codigo."<div class=\"enlinea\">No requiere autorizacion del club </div>";
			}
			
			$disabled="disabled";
			if ($nivel_admin OR ($nivel_distrito AND $nivel_distrito_id==$rows['distrito'])) {
				$disabled="";
				$muestra_codigo=true;
			}
			if(!$requiere_distrito){
				if($rows['ok_distrito']) {
					$linea_de_codigo=$linea_de_codigo."<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Desautoriza RDR\"/>";
				} else {
					$linea_de_codigo=$linea_de_codigo."<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Autoriza RDR\"/>";
				}
			} else {
				$linea_de_codigo=$linea_de_codigo."<div class=\"enlinea\">No requiere autorizacion del distrito </div>";
			}

			$disabled="disabled";
			$user_tesoreria=$_SESSION['uid'];
			$sql_tesoreria="SELECT * FROM rtc_eventos_tesoreria WHERE evento_id='$evento' AND user_id='$user_tesoreria' LIMIT 1";
			$result_tesoreria=mysql_query($sql_tesoreria);
			$num_tesoreria=mysql_num_rows($result_tesoreria);
			if ($nivel_admin OR ($nivel_evento_tesoreria AND $num_tesoreria)) {
				$disabled="";
				$muestra_codigo=true;
			}
			if(!$requiere_tesoreria){
				if($rows['ok_tesoreria']) {
					$linea_de_codigo=$linea_de_codigo."<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Desautoriza Pago\"/>";
				} else {
					$linea_de_codigo=$linea_de_codigo."<input ".$disabled." type=\"submit\" name=\"button\" id=\"button\" value=\"Autoriza Pago\"/>";
				}
			} else {
				$linea_de_codigo=$linea_de_codigo."<div class=\"enlinea\">No requiere pago de se&ntilde;a </div>";
			}
			
			if ($rows['ok_club'] AND $rows['ok_distrito'] AND $rows['ok_tesoreria']) {
				$linea_de_codigo=$linea_de_codigo."<div class=\"enlinea\"><span class=\"muestra_amarillo\">Esperando confirmacion del organizador</span></div>";
			}
			
			$linea_de_codigo=$linea_de_codigo."<input name=\"user\" type=\"hidden\" value=\"".$user_id."\" /><input name=\"evento\" type=\"hidden\" value=\"".$evento."\"/></form>";
			if ($muestra_codigo) {
				echo $linea_de_codigo;
			}
		}
	} // Final del WHILE
include 'footer.php';?>

