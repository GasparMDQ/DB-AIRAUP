<?php
include 'header.php';

$esadmin=false;
if ($nivel_distrito OR $nivel_admin) {
		$esadmin=true;
}

if (!$_SESSION['logged'] || !$esadmin) {
	header("Location: index.php");
}


$user="";
$user = mysql_real_escape_string(substr(htmlspecialchars($_POST['usuario']),0,40));

if (isset($_POST['user_id'])) {
	$user_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['user_id']),0,4));
} else {
	$user_id='';
}

$usuario_error="";

if (isset($_POST['user_id'])&& isset($_POST['submit'])&& $_POST['submit']=='Modificar') {

	//RECUPERO VARIABLES
	$pais = mysql_real_escape_string(substr(htmlspecialchars($_POST['pais']),0,40));
	$pais_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['pais_id']),0,10));
	$provincia = mysql_real_escape_string(substr(htmlspecialchars($_POST['provincia']),0,40));
	$provincia_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['provincia_id']),0,10));
	$ciudad = mysql_real_escape_string(substr(htmlspecialchars($_POST['ciudad']),0,40));
	$ciudad_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['ciudad_id']),0,10));
	$distrito = mysql_real_escape_string(substr(htmlspecialchars($_POST['distrito']),0,10));
	$distrito_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['distrito_id']),0,10));
	$club = mysql_real_escape_string(substr(htmlspecialchars($_POST['club']),0,40));
	$club_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['club_id']),0,10));
	$programa = mysql_real_escape_string(substr(htmlspecialchars($_POST['programa']),0,40));
	$programa_id = mysql_real_escape_string(substr(htmlspecialchars($_POST['programa_id']),0,10));


	if ($pais_id == "-1" ) {
		$sql = sprintf("SELECT * FROM rtc_paises WHERE pais = '$pais' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_paises (id_paises, pais) VALUES ('', '$pais')");
			$result = mysql_query($sql);
			$pais_id = mysql_insert_id();
		}
	}
			
	if ($provincia_id == "-1" ) {
		$sql = sprintf("SELECT * FROM rtc_provincias WHERE provincia = '$provincia' AND id_pais = '$pais_id' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_provincias (id_provincia, id_pais, provincia) VALUES ('', '$pais_id', '$provincia')");
			$result = mysql_query($sql);
			$provincia_id = mysql_insert_id();
		}	
	}			
	if ($ciudad_id == "-1" ) {
		$sql = sprintf("SELECT * FROM rtc_ciudades WHERE ciudad = '$ciudad' AND id_provincia = '$provincia_id' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_ciudades (id_ciudades, id_provincia, ciudad) VALUES ('', '$provincia_id', '$ciudad')");
			$result = mysql_query($sql);
			$ciudad_id = mysql_insert_id();
		} 
	}
					
	if ($distrito_id == "-1" ) {
		$sql = sprintf("SELECT * FROM rtc_distritos WHERE distrito = '$distrito' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_distritos (id_distrito, distrito, uid_rdr, uid_admin) VALUES ('', '$distrito', '', '')");
			$result = mysql_query($sql);
			$distrito_id = mysql_insert_id();
		}
	}	
		
	if ($club_id == "-1" ) {
		$sql = sprintf("SELECT * FROM rtc_clubes WHERE club = '$club' AND id_distrito = '$distrito_id' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_clubes (id_club, id_distrito, id_ciudad, club, uid_presidente, uid_admin) VALUES ('', '$dbdistrito', '', '$club', '', '')");
			$result = mysql_query($sql);
			$club_id = mysql_insert_id();
		}
	}

	if ($programa_id == "-1" ) {
		$sql = sprintf("SELECT * FROM rtc_cfg_programas WHERE programa = '$programa' LIMIT 1");
		$result = mysql_query($sql);
		$row = mysql_fetch_object($result);
		if ( !$row ) {
			$sql = sprintf("INSERT INTO rtc_cfg_programas (id_programa, programa, imagen) VALUES ('', '$programa', '')");
			$result = mysql_query($sql);
			$programa_id = mysql_insert_id();
		}
	}	

//Modifica los datos del usuario
//Datos personales
		$sql = sprintf("UPDATE rtc_usr_personales SET pais='$pais_id', opais=NULL, provincia='$provincia_id', oprovincia=NULL, ciudad='$ciudad_id', ociudad=NULL WHERE user_id='$user_id' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$usuario['error']="Hubo un error al modificar el usuario";
	} else {
		$usuario['error']="Se modificaron los datos del usuario ".$user_id." y se agregaron a las tablas correspondientes. (P)";
	}

//Datos institucionales
		$sql = sprintf("UPDATE rtc_usr_institucional SET distrito='$distrito_id', odistrito=NULL, club='$club_id', oclub=NULL, programa_ri='$programa_id', oprograma=NULL WHERE user_id='$user_id' LIMIT 1 ");
	$result = mysql_query($sql);
	if ( $result == false ) {
		$usuario['error']="Hubo un error al modificar el usuario";
	} else {
		$usuario['error']="Se modificaron los datos del usuario ".$user_id." y se agregaron a las tablas correspondientes. (I)";
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
            <input name="user_id" type="text" id="user_id" size="10" maxlength="40" />
                        <input type="submit" name="button" id="button" value="Enviar" />
      </form>
      </td>
      	<td><form id="form1" name="form1" method="post" action="usuario.php">Usuarios:
<?php
	$sql = "SELECT rtc_usr_personales.user_id, rtc_usr_personales.nombre, rtc_usr_personales.apellido, rtc_distritos.distrito FROM rtc_usr_personales, rtc_usr_institucional, rtc_distritos WHERE rtc_usr_personales.user_id=rtc_usr_institucional.user_id  AND rtc_usr_institucional.distrito=rtc_distritos.id_distrito ORDER BY rtc_distritos.distrito, rtc_usr_personales.nombre, rtc_usr_personales.apellido";
	$result = mysql_query($sql);
	echo "<select name=\"user_id\" id=\"user_id\">";
	echo "<option value=\"0\" selected > </option>";
	$sel='';
	while ($rowtmp = mysql_fetch_assoc($result))
	{
		echo "<option value=\"{$rowtmp['user_id']}\" >({$rowtmp['distrito']})- {$rowtmp['nombre']} {$rowtmp['apellido']}</option>";	
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
if ($user_id != '') {
	$sql = "SELECT * FROM rtc_usr_login WHERE uid = '$user_id' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$user_name = $row['user_id'];

	$sql = "SELECT * FROM rtc_usr_personales WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql);
	$row_p = mysql_fetch_assoc($result);
	
	$sql = "SELECT * FROM rtc_usr_institucional WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql);
	$row_i = mysql_fetch_assoc($result);

	$modi = false;
?> 
<form action="usuario.php" method="post">
<input name="user_id" id="user_id" type="hidden" value="<?php echo $user_id; ?>" />
<input type="hidden" name="usuario" id="usuario" value="<?php echo $user_name; ?>" />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td><p>&nbsp;</p></td>
		<td><?php echo $usuario['error']; ?></td>
		<td>&nbsp;</td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Nombre y Apellido</td>
		<td align="left"><?php echo $row_p['nombre'];?> <?php echo $row_p['apellido']; ?></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Id | Nombre de Usuario</td>
		<td align="left"><?php echo $user_id;?>| <?php echo $user_name; ?></td>
    </tr>
    <tr>
		<td>&nbsp;</td>
		<td>País</td>
    	<td align="left"><?php
		// PAIS
			$pais_id = $row_p['pais'];
			if ( $pais_id == "-1" ) {
      			echo "<input name=\"pais\" type=\"text\" id=\"pais\" size=\"30\" maxlength=\"40\" value=\"{$row_p['opais']}\">";
				$modi = true;
			} else {
				$sql = "SELECT * FROM rtc_paises WHERE id_paises='$pais_id' ORDER BY pais";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['pais'];
			}
			?><input name="pais_id" id="pais_id" type="hidden" value="<?php echo $pais_id; ?>" />		</td>
        </td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Provincia / Departamento / Estado</td>
		<td align="left"><?php 
		// PROVINCIA
			$provincia_id = $row_p['provincia'];
			if ( $provincia_id == "-1" ) {
      			echo "<input name=\"provincia\" type=\"text\" \"provincia\" size=\"30\" maxlength=\"40\" value=\"{$row_p['oprovincia']}\">";
				$modi = true;
			} else {
				$sql = "SELECT * FROM rtc_provincias WHERE id_provincia='$provincia_id' ORDER BY provincia";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['provincia'];
			}
			?><input name="provincia_id" id="provincia_id" type="hidden" value="<?php echo $provincia_id; ?>" />
        </td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Ciudad</td>
		<td align="left"><?php 
		// CIUDAD
			$ciudad_id = $row_p['ciudad'];
			if ( $ciudad_id== "-1" ) {
      			echo "<input name=\"ciudad\" type=\"text\" id=\"ciudad\" size=\"30\" maxlength=\"40\" value=\"{$row_p['ociudad']}\">";
				$modi = true;
			} else {
				$sql = "SELECT * FROM rtc_ciudades WHERE id_ciudades='$ciudad_id' ORDER BY ciudad";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['ciudad'];
			}
			?><input name="ciudad_id" id="ciudad_id" type="hidden" value="<?php echo $ciudad_id; ?>" />
        </td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Distrito</td>
		<td align="left"><?php 
		// DISTRITO
			$distrito_id = $row_i['distrito'];
			if ( $distrito_id == "-1" ) {
      			echo "<input name=\"distrito\" type=\"text\" \"distrito\" size=\"30\" maxlength=\"40\" value=\"{$row_i['odistrito']}\">";
				$modi = true;
			} else {
				$sql = "SELECT * FROM rtc_distritos WHERE id_distrito='$distrito_id' ORDER BY distrito";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['distrito'];
			}
			?><input name="distrito_id" id="distrito_id" type="hidden" value="<?php echo $distrito_id;  ?>" />
        </td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Club</td>
		<td align="left"><?php 
		// CLUB
			$club_id = $row_i['club'];
			if ( $club_id == "-1" ) {
      			echo "<input name=\"club\" type=\"text\" \"club\" size=\"30\" maxlength=\"40\" value=\"{$row_i['oclub']}\">";
				$modi = true;
			} else {
				$sql = "SELECT * FROM rtc_clubes WHERE id_club='$club_id' ORDER BY club";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['club'];
			}
			?><input name="club_id" id="club_id" type="hidden" value="<?php echo $club_id;  ?>" />
        </td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
		<td>Programa</td>
		<td align="left"><?php 
		// PROGRAMA
			$programa_id = $row_i['programa_ri'];
			if ( $programa_id == "-1" ) {
      			echo "<input name=\"programa\" type=\"text\" \"programa\" size=\"30\" maxlength=\"40\" value=\"{$row_i['oprograma']}\">";
				$modi = true;
			} else if ( $row_i['programa_ri'] != '-1') {
				$sql = "SELECT * FROM rtc_cfg_programas WHERE id_programa='$programa_id' ORDER BY programa";
				$result = mysql_query($sql);
				$rowtmp = mysql_fetch_assoc($result);
				echo $rowtmp['programa'];
			}
			?><input name="programa_id" id="programa_id" type="hidden" value="<?php echo $programa_id;  ?>" />
		</td>
	</tr>
	<tr>
		<td colspan="3">
		<div align="center"><?php if ($modi) { ?><input type="submit" name="submit" id="submit" value="Modificar" /> <?php }?></div>
        </td>
	</tr>
</table>
</form>

<?php
} // fin del if que se fija si fue ingresado o no el user_id

include 'footer.php';?>

