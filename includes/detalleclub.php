<?php
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';
require_once 'permisos.php';

$idc=intval($_GET['club']);

$sql = "SELECT * FROM rtc_clubes WHERE id_club = '$idc'";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);

$idd= $row['id_distrito'];
$sqld = "SELECT * FROM rtc_distritos WHERE id_distrito = '$idd'";
$resultd = mysql_query($sqld);
$rowd = mysql_fetch_assoc($resultd);

?>
<table class="tabla_clubes_detalles">
<tr>
    	<th valign="bottom"><h1>Rotaract Club <?php echo $row['club'];?></h1></th>
    <th valign="bottom" align="right"><h2>Distrito <?php echo $rowd['distrito'];?></h2></th>
  </tr>
	<tr>
    	<td><h3>Presidente</h3></td>
        <td align="right"><?php
			$presi= $row['uid_presidente'];
			$sqls = "SELECT * FROM rtc_usr_personales WHERE uid = '$presi' LIMIT 1";
			$results = mysql_query($sqls);
			$rows = mysql_fetch_assoc($results);
			if ($rows) {
				echo "<h3>".$rows['nombre']." ".$rows['apellido']."</h3>";
			} else {
				echo "no informado";
			}
			?></td>
	</tr>
	<tr bgcolor="#555555">
	  <td valign="top">D&iacute;a y hora de reuni&oacute;n</td>
	  <td align="right" valign="top">&nbsp;</td>
  </tr>
	<tr>
	  <td valign="top">Direcci&oacute;n</td>
	  <td align="right" valign="top"><?php
			if ($row['direccion']!='') {
				echo $row['direccion'];
			} else {
				echo "no informada";
			}
			?></td>
  </tr>
	<tr  bgcolor="#555555">
	  <td valign="top">E-mail:</td>
	  <td align="right" valign="top"><?php
			if ($row['email']!='') {
				echo $row['email'];
			} else {
				echo "no informado";
			}
			?></td>
  </tr>
	<tr>
	  <td valign="top">P&aacute;gina web</td>
	  <td align="right" valign="top"><?php
			if ($row['url']!='') {
				echo $row['url'];
			} else {
				echo "no informada";
			}
			?></td>
  </tr>
</table>
<table class="tabla_clubes_detalles_socios">
	<tr>
    	<td colspan="2"><h2>Directorio</h2></td>
    </tr>
	<tr>
    	<td>Socios</td>
        <td>Ficha</td>
	</tr>
	<tr>
		<td>
		<?php
			$clubtmp = $row['id_club'];
			$sqls = "SELECT * FROM rtc_usr_personales, rtc_usr_institucional WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id AND rtc_usr_institucional.club = '$clubtmp' ORDER BY rtc_usr_personales.apellido, rtc_usr_personales.nombre";
			$results = mysql_query($sqls);
			$ocultos = 0;
			while($rows = mysql_fetch_assoc($results))
			{
				if ($rows['perfil_publico'] OR $nivel_admin OR ($nivel_distrito AND $nivel_distrito_id==$idd) OR ($nivel_club AND $nivel_club_id==$idc)){
					if ($rows['perfil_publico']) {
						echo "<a href=\"javascript:fichaSocio(".$rows['uid'].")\">".$rows['nombre']." ".$rows['apellido']."</a><br />";
					} else {
						echo "<a class=\"datoprivado\" href=\"javascript:fichaSocio(".$rows['uid'].")\">".$rows['nombre']." ".$rows['apellido']."</a><br />";
					}
				} else {
					$ocultos = $ocultos +1;
				}
			} ?>
		</td>
		<td><div id="ficha_socio"></div></td>
	</tr>
	<?php if ($ocultos) { ?>
	<tr>
		<td colspan="2"><?php echo "y ".$ocultos." socios con datos privados"; ?></td>
		</tr>
	<?php }	?>
</table>
