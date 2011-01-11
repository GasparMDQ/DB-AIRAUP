<?php
include 'header.php';

require_once '/home/gasparmdq/configDB/configuracion.php';
require_once 'includes/abredb.php';

$usuario['var']="0";
$usuario['var']=substr(htmlspecialchars($_POST['usuario']),0,40);
$usuario['id']=substr(htmlspecialchars($_POST['idusuario']),0,4);
$usr = mysql_real_escape_string($usuario['var']);
$usrid = mysql_real_escape_string($usuario['id']);

$usuario['error']="";

if (isset($_POST['usuario'])&& $_POST['submit']=='Modificar') {

		$dbpais = mysql_real_escape_string(substr(htmlspecialchars($_POST['pais']),0,40));
		$sql = sprintf("SELECT * FROM rtc_paises WHERE id_paises = '$dbpais' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_paises (id_paises, pais) VALUES ('', '$dbpais')");
			$result = mysql_query($sql);
			$dbpais = mysql_insert_id();
		}
		
		$dbprovincia = mysql_real_escape_string(substr(htmlspecialchars($_POST['provincia']),0,40));
		$sql = sprintf("SELECT * FROM rtc_provincias WHERE id_provincia = '$dbprovincia' AND id_pais = '$dbpais' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_provincias (id_provincia, id_pais, provincia) VALUES ('', '$dbpaisid', '$dbprovincia')");
			$result = mysql_query($sql);
			$dbprovincia = mysql_insert_id();
		}	
			
		$dbciudad = mysql_real_escape_string(substr(htmlspecialchars($_POST['ciudad']),0,40));
		$sql = sprintf("SELECT * FROM rtc_ciudades WHERE ciudad = '$dbciudad' AND id_provincia = '$dbprovincia' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_ciudades (id_ciudades, id_provincia, ciudad) VALUES ('', '$dbprovincia', '$dbciudad')");
			$result = mysql_query($sql);
			$dbciudad = mysql_insert_id();
		} else {
			$sql = sprintf("SELECT * FROM rtc_ciudades WHERE ciudad = '$dbciudad' AND id_provincia = '$dbprovincia' LIMIT 1");
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$dbciudad = $row['id_ciudades'];
		}
					
		$dbdistrito = mysql_real_escape_string(substr(htmlspecialchars($_POST['distrito']),0,40));
		$sql = sprintf("SELECT * FROM rtc_distritos WHERE id_distrito = '$dbdistrito' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_distritos (id_distrito, distrito, uid_rdr, uid_admin) VALUES ('', '$dbdistrito', '', '')");
			$result = mysql_query($sql);
			$dbdistrito = mysql_insert_id();
		}	
		
		$dbclub = mysql_real_escape_string(substr(htmlspecialchars($_POST['club']),0,40));
		$sql = sprintf("SELECT * FROM rtc_clubes WHERE id_club = '$dbclub' AND id_distrito = '$dbdistrito' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_clubes (id_club, id_distrito, id_ciudad, club, uid_presidente, uid_admin) VALUES ('', '$dbdistrito', '', '$dbclub', '', '')");
			$result = mysql_query($sql);
			$dbclub = mysql_insert_id();
		}

		$dbprograma = mysql_real_escape_string(substr(htmlspecialchars($_POST['programa']),0,40));
		$sql = sprintf("SELECT * FROM rtc_cfg_programas WHERE id_programa = '$dbprograma' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_cfg_programas (id_programa, programa) VALUES ('', '$dbprograma')");
			$result = mysql_query($sql);
			$dbprograma = mysql_insert_id();
		}	

//Modifica los datos del usuario
//Datos personales
		$sql = sprintf("UPDATE rtc_usr_personales SET pais='$dbpais', opais=NULL, provincia='$dbprovincia', oprovincia=NULL, ciudad='$dbciudad', ociudad=NULL WHERE user_id='$usrid' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$usuario['error']="Hubo un error al modificar el usuario";
	} else {
		$usuario['error']="Se modificaron los datos del usuario ".$usuario['var']." y se agregaron a las tablas correspondientes. (P)";
	}

//Datos institucionales
		$sql = sprintf("UPDATE rtc_usr_institucional SET distrito='$dbdistrito', odistrito=NULL, club='$dbclub', oclub=NULL, programa_ri='$dbprograma', oprograma=NULL WHERE user_id='$usrid' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$usuario['error']="Hubo un error al modificar el usuario";
	} else {
		$usuario['error']="Se modificaron los datos del usuario ".$usuario['var']." y se agregaron a las tablas correspondientes. (I)";
	}
}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="40">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><form id="form1" name="form1" method="post" action="usuario.php">
        Id. de Usuario:
            <input name="usuario" type="text" id="usuario" size="10" maxlength="40" />
                        <input type="submit" name="button" id="button" value="Enviar" />
      </form>
      </td>
      	<td><form id="form1" name="form1" method="post" action="usuario.php">Usuarios:
<?php
	$sql1 = "SELECT * FROM rtc_usr_personales ORDER BY apellido, nombre";
	$resultado = mysql_query($sql1);
	echo "<select name=\"usuario\" id=\"usuario\">";
	echo "<option value=\"0\" selected > </option>";
	$sel='';
	while ($rowtmp = mysql_fetch_assoc($resultado))
	{
		echo "<option value=\"{$rowtmp['user_id']}\" {$sel} >{$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
	}
	echo "</select>";
?>
	<input type="submit" name="button" id="button" value="Enviar" />
	</form>
	</td>
      
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
<?php
$user = mysql_real_escape_string($usuario['var']);
if ($usr != '') {
$sql = "SELECT * FROM rtc_usr_login WHERE uid = '$user' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$idusuario = $row['user_id'];
$uid = $row['uid'];

$sql = "SELECT * FROM rtc_usr_personales WHERE user_id = '$uid' LIMIT 1";
$result = mysql_query($sql);
$row_p = mysql_fetch_assoc($result);
$sql = "SELECT * FROM rtc_usr_institucional WHERE user_id = '$uid' LIMIT 1";
$result = mysql_query($sql);
$row_i = mysql_fetch_assoc($result);

$modi = false;
?> 
<form action="usuario.php" method="post">
<input name="idusuario" id="idusuario" type="hidden" value="<?php echo $uid;  ?>" /><input type="hidden" name="usuario" id="usuario" value="<?php echo $idusuario;  ?>" />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><p>&nbsp;</p></td>
		<td><?php echo $usuario['error']; ?></td>
		<td>&nbsp;</td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Nombre de Usuario</td>
	<td align="left"><?php echo $uid;?>| <?php echo $idusuario; ?></tr>
    <tr>
      <td>&nbsp;</td>
      <td>País</td>
    <td align="left"><?php
		// PAIS
			$sql = sprintf("SELECT * FROM rtc_usr_personales WHERE user_id = '$uid' AND pais = '-1' LIMIT 1");
			$result = mysql_query($sql);
			$rowtmp = mysql_fetch_object($result);
			if ( $rowtmp ) {
      			echo "<input name=\"pais\" type=\"text\" \"pais\" size=\"30\" maxlength=\"40\" value=\"{$row_p['opais']}\">";
				$modi = true;
			} else if ( $row_p['pais'] != '-1') {
				$tmp = $row_p['pais'];
				$sql = "SELECT * FROM rtc_paises WHERE id_paises='$tmp' ORDER BY pais";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['pais'];
				?><input name="pais" id="pais" type="hidden" value="<?php echo $tmp;  ?>" /><?php
			}
		?></tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Provincia / Departamento / Estado</td>
		<td align="left"><?php 
		// PROVINCIA
			$sql = sprintf("SELECT * FROM rtc_usr_personales WHERE user_id = '$uid' AND provincia = '-1' LIMIT 1");
			$result = mysql_query($sql);
			$rowtmp = mysql_fetch_object($result);
			if ( $rowtmp ) {
      			echo "<input name=\"provincia\" type=\"text\" \"provincia\" size=\"30\" maxlength=\"40\" value=\"{$row_p['oprovincia']}\">";
				$modi = true;
			} else if ( $row_p['provincia'] != '-1') {
				$tmp = $row_p['provincia'];
				$sql = "SELECT * FROM rtc_provincias WHERE id_provincia='$tmp' ORDER BY provincia";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['provincia'];
				?><input name="provincia" id="provincia" type="hidden" value="<?php echo $tmp;  ?>" /><?php
			}
		?></tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Ciudad</td>
		<td align="left"><?php 
		// CIUDAD
			$sql = sprintf("SELECT * FROM rtc_usr_personales WHERE user_id = '$uid' AND ciudad = '-1' LIMIT 1");
			$result = mysql_query($sql);
			$rowtmp = mysql_fetch_object($result);
			if ( $rowtmp ) {
      			echo "<input name=\"ciudad\" type=\"text\" \"ciudad\" size=\"30\" maxlength=\"40\" value=\"{$row_p['ociudad']}\">";
				$modi = true;
			} else if ( $row_p['ciudad'] != '-1') {
				$tmp = $row_p['ciudad'];
				$sql = "SELECT * FROM rtc_ciudades WHERE id_ciudades='$tmp' ORDER BY ciudad";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['ciudad'];
				?><input name="ciudad" id="ciudad" type="hidden" value="<?php echo $tmp;  ?>" /><?php
			}
		?></tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Distrito</td>
		<td align="left"><?php 
		// DISTRITO
			$sql = sprintf("SELECT * FROM rtc_usr_institucional WHERE user_id = '$user' AND distrito = '-1' LIMIT 1");
			$result = mysql_query($sql);
			$rowtmp = mysql_fetch_object($result);
			if ( $rowtmp ) {
      			echo "<input name=\"distrito\" type=\"text\" \"distrito\" size=\"30\" maxlength=\"40\" value=\"{$row_i['odistrito']}\">";
				$modi = true;
			} else if ( $row_i['distrito'] != '-1') {
				$tmp = $row_i['distrito'];
				$sql = "SELECT * FROM rtc_distritos WHERE id_distrito='$tmp' ORDER BY distrito";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['distrito'];
				?><input name="distrito" id="distrito" type="hidden" value="<?php echo $tmp;  ?>" /><?php
			}
		?></tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Club</td>
		<td align="left"><?php 
		// CLUB
			$sql = sprintf("SELECT * FROM rtc_usr_institucional WHERE user_id = '$user' AND club = '-1' LIMIT 1");
			$result = mysql_query($sql);
			$rowtmp = mysql_fetch_object($result);
			if ( $rowtmp ) {
      			echo "<input name=\"club\" type=\"text\" \"club\" size=\"30\" maxlength=\"40\" value=\"{$row_i['oclub']}\">";
				$modi = true;
			} else if ( $row_i['club'] != '-1') {
				$tmp = $row_i['club'];
				$sql = "SELECT * FROM rtc_clubes WHERE id_club='$tmp' ORDER BY club";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['club'];
				?><input name="club" id="club" type="hidden" value="<?php echo $tmp;  ?>" /><?php
			}
		?></tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Programa</td>
		<td align="left"><?php 
		// PROGRAMA
			$sql = sprintf("SELECT * FROM rtc_usr_institucional WHERE user_id = '$user' AND programa_ri = '-1' LIMIT 1");
			$result = mysql_query($sql);
			$rowtmp = mysql_fetch_object($result);
			if ( $rowtmp ) {
      			echo "<input name=\"programa\" type=\"text\" \"programa\" size=\"30\" maxlength=\"40\" value=\"{$row_i['oprograma']}\">";
				$modi = true;
			} else if ( $row_i['programa_ri'] != '-1') {
				$tmp = $row_i['programa_ri'];
				$sql = "SELECT * FROM rtc_cfg_programas WHERE id_programa='$tmp' ORDER BY programa";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['programa'];
				?><input name="programa" id="programa" type="hidden" value="<?php echo $tmp;  ?>" /><?php
			}
		?></tr>
	<tr>
		<td colspan="3">
		<div align="center">
			<?php if ($modi) { ?><input type="submit" name="submit" id="submit" value="Modificar" /> <?php }?>
		</div>        </td>
	</tr>
</table>
</form>
<?php
// fin del if que se fija si fue ingresado o no el UID
 } ?> 
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3" align="center"><form action="index.php" method="post">
        <input type="submit" name="submit" id="submit" value="Volver" />
      </form></tr>
  </table>

<?php include 'footer.php';?>

