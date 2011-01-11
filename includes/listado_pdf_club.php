<?php
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';
require_once 'permisos.php';
require('mysql_table.php');

$idc=intval($_GET['club']);

$sql = "SELECT * FROM rtc_clubes WHERE id_club = '$idc'";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);

$idd= $row['id_distrito'];
$sqld = "SELECT * FROM rtc_distritos WHERE id_distrito = '$idd'";
$resultd = mysql_query($sqld);
$rowd = mysql_fetch_assoc($resultd);



if (($nivel_admin OR ($nivel_distrito_id==$idd) OR ($nivel_usuario_club_id==$idc)) AND ($idc!='0')){

	class PDF extends PDF_MySQL_Table {}

	//Preparo las variables para armar el PDF
		$nombre_club = "Rotaract Club ".$row['club']."";
		$numero_distrito = "Distrito ".$rowd['distrito']." \n ";
	
		$presi= $row['uid_presidente'];
		$sqls = "SELECT * FROM rtc_usr_personales WHERE user_id = '$presi' LIMIT 1";
		$results = mysql_query($sqls);
		$rows = mysql_fetch_assoc($results);
		if ($rows) {
			$nombre_presi ="Presidente: ".$rows['nombre']." ".$rows['apellido']."\n";
		} else {
			$nombre_presi ="Presidente no informado"."\n";
		}
	
		$dia_hora="Dia y hora de reunion ";
		$direccion="Direccion: ";
			if ($row['direccion']!='') {
				$direccion.= $row['direccion']."\n";
			} else {
				$direccion.= "no informada"."\n";
			}
	
		$email="E-mail: ";
			if ($row['email']!='') {
				$email.= $row['email']."\n";
			} else {
				$email.= "no informada"."\n";
			}
	
		$pagina_web="Pagina web: ";
			if ($row['url']!='') {
				$pagina_web.= $row['url']."\n";
			} else {
				$pagina_web.= "no informada"."\n";
			}
	
		$clubtmp = $row['id_club'];

	$pdf=new PDF('L','mm','A4');
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(0,6,$nombre_club,0,1,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,6,$numero_distrito,0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,5,$nombre_presi,0,1,'L');
	$pdf->Cell(0,5,$direccion,0,1,'L');
	$pdf->Cell(0,5,$email,0,1,'L');
	$pdf->Cell(0,5,$pagina_web,0,1,'L');
	$pdf->Ln(6);
	//First table: put all columns automatically
	$pdf->Table("SELECT apellido, nombre, direccion, telefono, celular, fecha_de_nacimiento, email FROM rtc_usr_personales, rtc_usr_institucional, rtc_usr_login WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id AND rtc_usr_personales.user_id = rtc_usr_login.uid AND rtc_usr_institucional.club = '$clubtmp' ORDER BY rtc_usr_personales.apellido, rtc_usr_personales.nombre");
	$pdf->Output();
} else {
	die();
}?>