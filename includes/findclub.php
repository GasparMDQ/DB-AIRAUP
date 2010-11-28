<?php
$iddist=intval($_GET['dist']);
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';

$sql = "SELECT * FROM rtc_clubes WHERE id_distrito = '$iddist' ORDER BY club";
$result = mysql_query($sql);
echo "<select name=\"club\" id=\"club\">";
if ($iddist != '-1') {
	echo "<option value=\"0\">Seleccione Club</option>";
}

while($row = mysql_fetch_assoc($result))
{
	echo "<option value=\"{$row['id_club']}\">{$row['club']}</option>";
}
?>
	<option value="-1" <?php if ($club['var']=='-1') {echo 'selected="selected"';}?> >Otro Club</option></select>&nbsp;<span style="color:#FF0000"><?php echo $club['error'];?></span></td>
<?php
echo "</select>";
?>