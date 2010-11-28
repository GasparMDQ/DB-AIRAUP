<?php 
//Poner IF que si el import= xls cargue un header para exportar, sino el comun

if (isset($_POST['export']) && $_POST['export'] == 'Excel') {
	include 'includes/header_excel.php';
} else {
	include 'includes/header.php';
}

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

?>
<table id="main" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="5" align="center"><p></p></td>
      </tr>
      <tr>
        <td width="300" align="center"><p><img src="images/base.png" alt="Bases de Datos" width="128" height="128" /></p></td>
        <td colspan="4"><h1>Detalle de Clubes</h1></td>
  </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td width="20">&nbsp;</td>
        <td><div align="left"></div></td>
        <td width="40" align="center"></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center">
		<?php 
		if (isset($_GET['club'])) {
			$club['var']=$_GET['club'];
		}
		echo "<select name=\"club\" id=\"clubdetalle\" onchange=\"getClubDetalle(this.value)\">";
		echo "<option value=\"0\">Seleccione Club</option>";
		$sqld = "SELECT * FROM rtc_distritos ORDER BY distrito";
		$resultd = mysql_query($sqld);
		while($rowd = mysql_fetch_assoc($resultd))
		{
			$distmp = $rowd['id_distrito'];
			$sqlc = "SELECT * FROM rtc_clubes WHERE id_distrito = '$distmp' ORDER BY club";
			$resultc = mysql_query($sqlc);
			while($rowc = mysql_fetch_assoc($resultc))
			{
				if ($rowc['id_club']==$club['var']) { $sel = 'selected="selected"';} else {$sel = '';}
				echo "<option value=\"{$rowc['id_club']}\" {$sel} >{$rowd['distrito']} - {$rowc['club']}</option>";
			}
		}
		?>        </td>
	</tr>
</table>
<div id="detalleclubes"></div>
<?php 
if (isset($_GET['club'])) {
	echo "<script language=\"javascript\">getClubDetalle(".$_GET['club'].")</script>"; 
}

include 'includes/footer.php';
?>