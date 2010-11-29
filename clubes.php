<?php 
include 'includes/header.php';
?>
<table id="tabla_clubes">
  <tr><td>
<h1>Clubes</h1>
</td></tr>
<tr><td>
<?php
		echo "<select name=\"club\" id=\"clubdetalle\" onchange=\"getClubDetalle(this.value)\">";
		echo "<option value=\"0\">Seleccione el Club para ver los detalles</option>";
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
		?>
</td></tr>        
<tr><td></table>
<div id="detalleclubes"></div>


<?php 
if (isset($_GET['club'])) {
	echo "<script language=\"javascript\">getClubDetalle(".$_GET['club'].")</script>"; 
}
?>

<?php 
include 'includes/footer.php';
?>