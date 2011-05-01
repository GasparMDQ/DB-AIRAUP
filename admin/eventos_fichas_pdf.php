<?php
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';
require_once '../includes/permisos.php';
require_once '../includes/funciones.php';
require('../includes/mysql_table.php');


class PDF extends FPDF
{

function Header()
{
	global $titulo_evento;

    //Logo
//    $this->Image('../images/logos/'.$logo,10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    //Movernos a la derecha
//    $this->Cell(80);
    //Título
    $this->Cell(0,10,$titulo_evento,0,1,'L');
    //Salto de línea
    $this->Ln(10);
}

function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,10,'Ficha '.$this->PageNo().'/{nb}',0,0,'C');
}
}







$esadmin=false;

if ($nivel_admin OR $nivel_evento) {
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


//	COMIENZO DEL CUERPO

if ($evento!=0) {

//	CHEQUEO SI TIENE PERMISO PARA VER LA FICHA (ADMIN, RDR, PRESIDENTE, COORDINADOR)

	$permiso=false;	//INICIALIZO LA VARIABLE PARA EVITAR FALSOS POSITIVOS
	
	if ($nivel_admin) {
		$permiso=true;
	}	//ES ADMIN

	$sql="SELECT * FROM rtc_eventos_coordinadores, rtc_eventos_inscripciones WHERE rtc_eventos_inscripciones.evento_id=rtc_eventos_coordinadores.evento_id AND rtc_eventos_coordinadores.user_id='$nivel_usuario_id' LIMIT 1";
	$result=mysql_query($sql);
	if ($nivel_evento AND mysql_num_rows($result)) {
		$permiso=true;
	}	//ES COORDINADOR

	if ($permiso) {

		$sql_loop="SELECT rtc_eventos_inscripciones.user_id FROM rtc_eventos_inscripciones, rtc_usr_personales WHERE rtc_eventos_inscripciones.evento_id='$evento' AND rtc_eventos_inscripciones.user_id=rtc_usr_personales.user_id ORDER BY rtc_usr_personales.nombre, rtc_usr_personales.apellido";
		$result_loop=mysql_query($sql_loop);


	$sql="SELECT * FROM rtc_eventos WHERE id='$evento' LIMIT 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$titulo_evento=$row['nombre'];

// ARRANCA EL PDF CON DEFINICIONES PREVIAS AL LOOP DE DATOS

	$pdf=new PDF('P','mm','A4');
	$pdf->AliasNbPages();

		while ($row=mysql_fetch_assoc($result_loop)) {
		
			$user_id=$row['user_id'];

			setlocale(LC_ALL, 'es_ES');
	
//	Cargo las tablas del usuario a consultar
			$sql = "SELECT * FROM rtc_usr_personales WHERE user_id = '$user_id' LIMIT 1";
			$result = mysql_query($sql);
			$datos_personales = mysql_fetch_assoc($result);

			$sql = "SELECT * FROM rtc_usr_login WHERE uid = '$user_id' LIMIT 1";
			$result = mysql_query($sql);
			$datos_login = mysql_fetch_assoc($result);
	
			$sql = "SELECT * FROM rtc_usr_institucional WHERE user_id = '$user_id' LIMIT 1";
			$result = mysql_query($sql);
			$datos_institucionales = mysql_fetch_assoc($result);

			$sql = "SELECT * FROM rtc_usr_salud WHERE user_id = '$user_id' LIMIT 1";
			$result = mysql_query($sql);
			$datos_salud = mysql_fetch_assoc($result);


			$sql="SELECT * FROM rtc_clubes WHERE id_club='".$datos_institucionales['club']."' LIMIT 1";
			$result = mysql_query($sql);
			$club = mysql_fetch_assoc($result);
			$sql="SELECT * FROM rtc_distritos WHERE id_distrito='".$datos_institucionales['distrito']."' LIMIT 1";
			$result = mysql_query($sql);
			$distrito = mysql_fetch_assoc($result);
			$sql="SELECT * FROM rtc_cfg_programas WHERE id_programa='".$datos_institucionales['programa_ri']."' LIMIT 1";
			$result = mysql_query($sql);
			$programa = mysql_fetch_assoc($result);

			$encabezado="Ficha de ".$datos_personales['nombre']." ".$datos_personales['apellido'];
			//	$logo=;
	
			$institucional="Informacion Institucional\n";
			if ($datos_institucionales['fecha_de_modificacion']=="") {
				$institucional=$institucional. "Nunca ingresados\n";
			} else { 
		    	$institucional=$institucional. "Edad: ".getAge($datos_personales['fecha_de_nacimiento'])."\n";
				$institucional=$institucional. $programa['programa']." Club ".$club['club'].", Distrito ".$distrito['distrito']."\n";
				if ($datos_institucionales['fecha_de_modificacion']=="0000-00-00 00:00:00") {
					$institucional=$institucional."DATOS SIN ACTUALIZAR";
				} else { 
			        $institucional=$institucional. "Actualizados al ".strftime ("%d de %B de %Y", strtotime($datos_institucionales['fecha_de_modificacion']));
				}
			}


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

			$personal="Informacion Personal\n";
			if ($datos_personales['fecha_de_modificacion']=="") {
				$personal=$personal. "Nunca ingresados\n";
			} else { 
		    	$personal=$personal. "Telefono: ".$datos_personales['telefono']."\n";
		    	$personal=$personal. "Celular: ".$datos_personales['celular']."\n";
		    	$personal=$personal. "Domicilio: ".$datos_personales['direccion'].", ".$ciudad['ciudad'].", ".$provincia['provincia'].", ".$pais['pais']."\n";
		    	$personal=$personal. "Email: ".$datos_login['email']."\n";
		    	$personal=$personal. $tipo_dni['tipo'].": ".$datos_personales['numero_de_documento']."\n";
		    	$personal=$personal. "Fecha de Nacimiento: ".strftime ("%d de %B de %Y", strtotime($datos_personales['fecha_de_nacimiento']))."\n";
				if ($datos_personales['fecha_de_modificacion']=="0000-00-00 00:00:00") {
					$personal=$personal."DATOS SIN ACTUALIZAR";
				} else { 
			        $personal=$personal. "Actualizados al ".strftime ("%d de %B de %Y", strtotime($datos_personales['fecha_de_modificacion']));
				}
			}


			$salud="Informacion de Salud\n";
			if ($datos_salud['fecha_de_modificacion']=="") {
				$salud=$salud. "Nunca ingresados\n";
			} else { 
		    	$salud=$salud. "Obra Social: ".$datos_salud['obrasocial']."\n";
		    	$salud=$salud. "Numero de Afiliado: ".$datos_salud['nroobrasocial']."\n\n";

		    	$salud=$salud. "Grupo Sanguineo: ".$datos_salud['gruposanguineo']."\n";
		    	$salud=$salud. "Fuma: ";
				if ($datos_salud['fuma']) {
						$salud=$salud. "Si\n";
					} else {
						$salud=$salud. "No\n";
					}
		    	$salud=$salud. "Lateralidad: ".$datos_salud['lateralidad']."\n\n";

		    	$salud=$salud. "Vegetariano: ";
					if ($datos_salud['dietaveg']) {
						$salud=$salud. "Si\nDetalle: ".$datos_salud['dietavegdesc']."\n";
					} else {
						$salud=$salud. "No\n";
					}
		    	$salud=$salud. "Dieta Especial: ";
					if ($datos_salud['dietaesp']) {
						$salud=$salud. "Si\nDetalle: ".$datos_salud['dietaespdesc']."\n\n";
					} else {
						$salud=$salud. "No\n\n";
					}
		
		    	$salud=$salud. "¿Tiene alguna enfermedad que requiera periódicamente tratamiento o control médico?: ";
					if ($datos_salud['enftpcm']) {
						$salud=$salud. "Si\n";
					} else {
						$salud=$salud. "No\n";
					}
	    		$salud=$salud. "Durante los últimos 3 años: ¿fue internado alguna vez?: ";
					if ($datos_salud['intu3a']) {
						$salud=$salud. "Si\n";
					} else {
						$salud=$salud. "No\n";
					}
		    	$salud=$salud. "¿Tiene algún tipo de alergia?: ";
					if ($datos_salud['alergia']) {
						$salud=$salud. "Si\nDetalle: ".$datos_salud['alergiadesc']."\n";
						$salud=$salud. "¿Recibe tratamiento permanente para la alergia?: ";
						if ($datos_salud['alergiatratamiento']) {
							$salud=$salud. "Si\nDetalle: ".$datos_salud['alergiadesc']."\n\n";
						} else {
							$salud=$salud. "No\n\n";
						}
					} else {
						$salud=$salud. "No\n\n";
					}
	
    			$salud=$salud. "¿Recibe tratamiento médico?: ";
					if ($datos_salud['tratamiento']) {
						$salud=$salud. "Si\nDetalle: ".$datos_salud['tratamientodesc']."\n";
					} else {
						$salud=$salud. "No\n";
					}
		    	$salud=$salud. "¿Tuvo algún tipo de cirugía?: ";
					if ($datos_salud['opera']) {
						$salud=$salud. "Si\nDetalle: ".$datos_salud['operadesc']."\n";
					} else {
						$salud=$salud. "No\n";
					}
		    	$salud=$salud. "¿Presenta algún tipo de limitación física?: ";
					if ($datos_salud['limitacionfisica']) {
						$salud=$salud. "Si\n";
					} else {
						$salud=$salud. "No\n";
					}
		    	$salud=$salud. "Otros problemas de salud: ".$datos_salud['otrossalud']."\n\n";

		
				if ($datos_salud['fecha_de_modificacion']=="0000-00-00 00:00:00") {
					$salud=$salud."DATOS SIN ACTUALIZAR";
				} else { 
			        $salud=$salud. "Actualizados al ".strftime ("%d de %B de %Y", strtotime($datos_salud['fecha_de_modificacion']));
				}
			}
	

			$emergencia="Contacto de Emergencia\n";
			if ($datos_personales['fecha_de_modificacion']=="") {
				$emergencia=$emergencia. "Nunca ingresados\n";
			} else { 
		    	$emergencia=$emergencia. "Nombre: ".$datos_personales['contacto_nombre']."\n";
		    	$emergencia=$emergencia. "Telefono: ".$datos_personales['contacto_telefono']."\n";
		    	$emergencia=$emergencia. "Relacion: ".$datos_personales['contacto_relacion']."\n";
				if ($datos_personales['fecha_de_modificacion']=="0000-00-00 00:00:00") {
					$emergencia=$emergencia."DATOS SIN ACTUALIZAR";
				} else { 
			        $emergencia=$emergencia. "Actualizados al ".strftime ("%d de %B de %Y", strtotime($datos_personales['fecha_de_modificacion']));
				}
			}

	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,6,$encabezado,0,1,'L');
    $pdf->Ln(5);
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(0,4,$institucional);
    $pdf->Ln(4);
	$pdf->MultiCell(0,4,$personal);
    $pdf->Ln(4);
	$pdf->MultiCell(0,4,$salud);
    $pdf->Ln(4);
	$pdf->MultiCell(0,4,$emergencia);

	setlocale(LC_ALL, '');
		} // FIN DEL WHILE QUE PASA POR LOS USUARIOS
	$pdf->Output(); // IMPRIMO EL PDF
	} else {
		echo "Acceso no autorizado";
	}
} //	FIN DEL $evento!=0
include 'footer.php';?>

