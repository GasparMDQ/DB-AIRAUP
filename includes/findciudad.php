<?php
$provincia=intval($_GET['prov']);

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';

$sql = "SELECT * FROM rtc_ciudades WHERE id_provincia = $provincia ORDER BY ciudad";
$result = mysql_query($sql);
echo "<select name=\"ciudad\" id=\"ciudad\">";
if ($provincia != '-1') {
	echo "<option value=\"0\">Seleccione</option>";
}
while($row = mysql_fetch_assoc($result))
{
	echo "<option value=\"{$row['id_ciudades']}\">{$row['ciudad']}</option>";
}
?>
	<option value="-1" <?php if ($ciudad['var']=='-1') {echo 'selected="selected"';}?> >Otra Ciudad</option></select>&nbsp;<span style="color:#FF0000"><?php echo $ciudad['error'];?></span></select>