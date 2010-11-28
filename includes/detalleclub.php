<?php
$idclub=intval($_GET['club']);
require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'abredb.php';

$sql = "SELECT * FROM rtc_clubes WHERE id_club = '$idclub'";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result)
?>
<table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<th>Rotaract Club <?php echo $row['club'];?></th>
        <th>&nbsp;</th>
	</tr>
	<tr>
    	<td>Presidente <?php
			$presi= $row['uid_presidente'];
			$sqls = "SELECT * FROM rtc_usuarios WHERE uid = '$presi' LIMIT 1";
			$results = mysql_query($sqls);
			$rows = mysql_fetch_assoc($results);
			if ($rows) {
				echo $rows['nombre']." ".$rows['apellido'];
			} else {
				echo "no informado";
			}
			?></td>
        <td>&nbsp;</td>
	</tr>
	<tr>
    	<td valign="top">Socios</td>
    	<td align="right">
		<?php
			$clubtmp = $row['id_club'];
			$sqls = "SELECT * FROM rtc_usuarios WHERE club = '$clubtmp' ORDER BY nombre, apellido";
			$results = mysql_query($sqls);
			$ocultos = 0;
			while($rows = mysql_fetch_assoc($results))
			{
				if ($rows['perfil_publico']){
					echo $rows['nombre']." ".$rows['apellido']."<br />";
				} else {
					$ocultos = $ocultos +1;
				}
			}
			if ($ocultos) {
				echo "y ".$ocultos." socios con perfil privado";
			}
		?>		</td>
	</tr>
    
</table>
