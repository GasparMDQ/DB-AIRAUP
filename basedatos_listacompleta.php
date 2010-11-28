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
        <td colspan="4"><h1>Listado Completo de Socios por Distrito y Club</h1></td>
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
<?php 
	$sxtotal=0;
	$cxtotal=0;
	$sql= sprintf("SELECT * FROM rtc_distritos ORDER BY distrito");
	$resultadodistrito = mysql_query($sql);
	while($rowdistrito = mysql_fetch_assoc($resultadodistrito))
	{
		$sxdistrito=0;
		$cantclub=0;
		$distrito=$rowdistrito['distrito'];
		$iddistrito=$rowdistrito['id_distrito'];
		$sql = sprintf("SELECT * FROM rtc_usuarios WHERE distrito = $iddistrito");
		$result = mysql_query($sql);
		if ($result) {
			$sxdistrito = mysql_num_rows($result);
		}
		$sql = sprintf("SELECT * FROM rtc_clubes WHERE id_distrito = $iddistrito");
		$result = mysql_query($sql);
		if ($result) {
			$cantclub = mysql_num_rows($result);
		}
		$sxtotal = $sxtotal+$sxdistrito;
		$cxtotal = $cxtotal+$cantclub;
?>
<tr>
        <td align="center" bgcolor="#555555"><h2>Distrito <?php echo $distrito; ?></h2></td>
        <td bgcolor="#555555"><h2>Clubes: <?php echo $cantclub; ?></h2></td>
    <td width="40" bgcolor="#555555">&nbsp;</td>
        <td bgcolor="#555555"><div align="left">
          <h2>Socios en el Distrito: <?php echo $sxdistrito;?></h2>
        </div></td>
        <td width="40" align="center" bgcolor="#555555">&nbsp;</td>
  </tr>

<?php	
	$sql= sprintf("SELECT * FROM rtc_clubes WHERE id_distrito=$iddistrito ORDER BY club");
	$resultadoclub = mysql_query($sql);
	$bg=0;
	while($rowclub = mysql_fetch_assoc($resultadoclub))
	{
		$bg = $bg+1;
		if ($bg % 2) {
			$colorbg = "000000";
		} else {
			$colorbg = "222222";
		}
		$sxclub=0;
		$club = $rowclub['club'];
		$idclub = $rowclub['id_club'];
		$sql = sprintf("SELECT * FROM rtc_usuarios WHERE club = $idclub");
		$result = mysql_query($sql);
		if ($result) {
			$sxclub = mysql_num_rows($result);
		}
?>
      <tr>
        <td align="center" bgcolor="#<?php echo $colorbg;?>">&nbsp;</td>

        <td bgcolor="#<?php echo $colorbg;?>"><a href="basedatos_detalleclubes.php?club=<?php echo $rowclub['id_club'];?>"><?php echo $rowclub['club'];?></a></td>

        <td width="40" bgcolor="#<?php echo $colorbg;?>">&nbsp;</td>
        <td bgcolor="#<?php echo $colorbg;?>"><div align="left">Socios: <?php echo $sxclub;?></div></td>
        <td align="center" bgcolor="#<?php echo $colorbg;?>">&nbsp;</td>
      </tr>
<?php }} //while de clubes y principal?>
      <tr>
        <td align="center" bgcolor="#333333">&nbsp;</td>
        <td bgcolor="#333333"><h2>Total de Clubes: <?php echo $cxtotal; ?></h2></td>
        <td width="40" bgcolor="#333333">&nbsp;</td>
        <td bgcolor="#333333"><div align="left">
          <h2>Total de Socios: <?php echo $sxtotal; ?></h2>
        </div></td>
        <td align="center" bgcolor="#333333">&nbsp;</td>
      </tr>

      <tr>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td width="40">&nbsp;</td>
        <td><div align="left"></div></td>
        <td align="center">&nbsp;</td>
      </tr>
</table>
<?php 
include 'includes/footer.php';
?>