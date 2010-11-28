<?php
$pais=intval($_GET['pais']);

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';

$sql = "SELECT * FROM rtc_provincias WHERE id_pais = $pais ORDER BY provincia";
$result = mysql_query($sql);
echo "<select name=\"provincia\" id=\"provincia\" onchange=\"getCiudad(this.value)\">";
if ($pais == '') {
	echo "<option value=\"0\">Elija Pais</option>";
} else if ($pais != '0') {
		echo "<option value=\"0\">Seleccione</option>";
}

while($row = mysql_fetch_assoc($result))
{
echo "<option value=\"{$row['id_provincia']}\"> {$row['provincia']}</option>";
}
?>
	<option value="-1" <?php if ($provincia['var']=='-1') {echo 'selected="selected"';}?> > Otra </option></select>&nbsp;<span style="color:#FF0000"><?php echo $provincia['error'];?></span></select>