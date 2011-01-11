<?php 
//Inicializar indicador de errores
$error=false;
$error_txt="";

// Leer datos del socios y cargarlos en las variables correspondientes.
if (!isset($_POST['enviar'])) {
	echo "Acceso Denegado";
//	echo "Reestrutcturando el sitio - finalización el 10-12-10 18hs gmt-3";
	die();
}
$user = $_SESSION['uid'];
$sql = "SELECT * FROM rtc_usr_personales WHERE user_id = '$user' LIMIT 1";
$result = mysql_query($sql);
$usuario = mysql_fetch_assoc($result);

$sql = "SELECT * FROM rtc_usr_salud  WHERE user_id= '$user' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
if ($row) {
	$sql = "SELECT * FROM rtc_usr_salud  WHERE user_id= '$user' LIMIT 1";
	$result = mysql_query($sql);
	$salud = mysql_fetch_assoc($result);
} else {
		$sql = sprintf("INSERT INTO rtc_usr_salud (user_id) VALUES ('$user')");
		$result = mysql_query($sql);
}

//Recupero de variables y verificacion de que esten todas. En caso de que alguna falte, se el indicador de error la marca.
//cargar en las variables los datos leidos de la DB

if (isset($_POST['enviar']) AND $_POST['enviar']=="Enviar") {
	$obrasocial['var']=substr(htmlspecialchars($_POST['obrasocial']),0,40);
	$numeroobra['var']=substr(htmlspecialchars($_POST['numeroobra']),0,40);
	$grupofactor['var']=substr(htmlspecialchars($_POST['grupofactor']),0,20);
	$enftpcm['var']=htmlspecialchars($_POST['enftpcm']);
	$intu3a['var']=htmlspecialchars($_POST['intu3a']);
	$alergia['var']=htmlspecialchars($_POST['alergia']);
	$alergiadesc['var']=htmlspecialchars($_POST['alergiadesc']);
	$alergiatratamiento['var']=htmlspecialchars($_POST['alergiatratamiento']);
	$alergiatratamientodesc['var']=htmlspecialchars($_POST['alergiatratamientodesc']);
	$tratamiento['var']=htmlspecialchars($_POST['tratamiento']);
	$tratamientodesc['var']=htmlspecialchars($_POST['tratamientodesc']);
	$opera['var']=htmlspecialchars($_POST['opera']);
	$operadesc['var']=htmlspecialchars($_POST['operadesc']);
	$operaedad['var']=htmlspecialchars($_POST['operaedad']);
	$limitacionfisica['var']=htmlspecialchars($_POST['limitacionfisica']);
	$otrossalud['var']=htmlspecialchars($_POST['otrossalud']);
	$dietaesp['var']=htmlspecialchars($_POST['dietaesp']);
	$dietaespdesc['var']=htmlspecialchars($_POST['dietaespdesc']);
	$dietaveg['var']=htmlspecialchars($_POST['dietaveg']);
	$dietavegdesc['var']=htmlspecialchars($_POST['dietavegdesc']);
	$fuma['var']=htmlspecialchars($_POST['fuma']);
	$lateralidad['var']=htmlspecialchars($_POST['lateralidad']);
} else {
	$obrasocial['var']=$salud['obrasocial'];
	$numeroobra['var']=$salud['nroobrasocial'];
	$grupofactor['var'] = $salud['gruposanguineo'];
	$enftpcm['var'] = $salud['enftpcm'];
	$intu3a['var'] = $salud['intu3a'];
	$alergia['var'] = $salud['alergia'];
	$alergiadesc['var'] = $salud['alergiadesc'];
	$alergiatratamiento['var'] = $salud['alergiatratamiento'];
	$alergiatratamientodesc['var'] = $salud['alergiatratamientodesc'];
	$tratamiento['var'] = $salud['tratamiento'];
	$tratamientodesc['var'] = $salud['tratamientodesc'];
	$opera['var'] = $salud['opera'];
	$operadesc['var'] = $salud['operadesc'];
	$operaedad['var'] = $salud['operaedad'];
	$limitacionfisica['var'] = $salud['limitacionfisica'];
	$otrossalud['var'] = $salud['otrossalud'];
	$dietaesp['var'] = $salud['dietaesp'];
	$dietaespdesc['var'] = $salud['dietaespdesc'];
	$dietaveg['var'] = $salud['dietaveg'];
	$dietavegdesc['var'] = $salud['dietavegdesc'];
	$fuma['var'] = $salud['fuma'];
	$lateralidad['var'] = $salud['lateralidad'];
}

//Si estan todas las variables, se procede a modificarlos datos ingresados.
if ($error==false AND isset($_POST['enviar']) AND $_POST['enviar']=="Enviar") {

//ACA VA SQL PARA AGREGAR EL REGISTRO

		$uid = mysql_real_escape_string($userid['var']); 
		$os = mysql_real_escape_string($obrasocial['var']); 
		$nos = mysql_real_escape_string($numeroobra['var']); 
		$gp = mysql_real_escape_string($grupofactor['var']); 
		$enf = mysql_real_escape_string($enftpcm['var']); 
		$inte = mysql_real_escape_string($intu3a['var']); 
		$ale = mysql_real_escape_string($alergia['var']); 
		$ald = mysql_real_escape_string($alergiadesc['var']); 
		$alt = mysql_real_escape_string($alergiatratamiento['var']); 
		$altd = mysql_real_escape_string($alergiatratamientodesc['var']); 
		$tra = mysql_real_escape_string($tratamiento['var']); 
		$trd = mysql_real_escape_string($tratamientodesc['var']); 
		$ope = mysql_real_escape_string($opera['var']); 
		$opd = mysql_real_escape_string($operadesc['var']); 
		$oped = mysql_real_escape_string($operaedad['var']); 
		$lim = mysql_real_escape_string($limitacionfisica['var']); 
		$osa = mysql_real_escape_string($otrossalud['var']); 
		$des = mysql_real_escape_string($dietaesp['var']); 
		$ded = mysql_real_escape_string($dietaespdesc['var']); 
		$dve = mysql_real_escape_string($dietaveg['var']); 
		$dvd = mysql_real_escape_string($dietavegdesc['var']); 
		$fum = mysql_real_escape_string($fuma['var']); 
		$lat = mysql_real_escape_string($lateralidad['var']); 
		
		$sql = sprintf("UPDATE rtc_usr_salud SET obrasocial = '$os', nroobrasocial = '$nos', gruposanguineo = '$gp', enftpcm = '$enf', intu3a = '$inte', alergia = '$ale', alergiadesc = '$ald', alergiatratamiento = '$alt', alergiatratamientodesc = '$altd', tratamiento = '$tra', tratamientodesc = '$trd', opera = '$ope', operadesc = '$opd', operaedad = '$oped', limitacionfisica = '$lim', otrossalud = '$osa', dietaesp = '$des', dietaespdesc = '$ded', dietaveg = '$dve', dietavegdesc = '$dvd', fuma = '$fum', lateralidad = '$lat' WHERE user_id='$user'");
		$result = mysql_query($sql);
		if ($result){
			$error_txt="Sus datos se modificaron correctamente.";
		} else {
			$error_txt="Hubo un error en la actualizaci&oacute;n de sus datos.";
		}
}
?>

<form action="socios_perfil.php" method="post">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><input name="seccion" type="hidden" id="seccion" value="2" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3"><h1>Perfil Médico de <?php echo $usuario['nombre']." ".$usuario['apellido'];?> </h1></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><span style="color:#FF0000"><?php echo $error_txt ;?></span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Obra Social:</td>
      <td align="left">        <input title="En caso de tener obra social, indique su nombre" name="obrasocial" type="text" id="obrasocial" size="30" maxlength="40" value="<?php echo $obrasocial['var'];  ?>" />&nbsp;<span style="color:#FF0000"><?php echo $obrasocial['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Número de Afiliado:</td>
      <td align="left"><input title="Ingrese su número de afiliado" name="numeroobra" type="text" id="numeroobra" size="30" maxlength="40" value="<?php echo $numeroobra['var'];  ?>" />        &nbsp;<span style="color:#FF0000"><?php echo $numeroobra['error'];?></span> </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Grupo Sanguíneo y Factor:</td>
      <td align="left"><select name="grupofactor" id="grupofactor" title="Seleccione su grupo sanguineo">
		<?php $sel = "selected=\"selected\""; ?>
        <option value="" <?php if ($grupofactor['var'] == "") { echo $sel; } ?>>Seleccione</option>
        <option value="A RH +" <?php if ($grupofactor['var'] == "A RH +") { echo $sel; } ?>>A RH +</option>
        <option value="A RH -" <?php if ($grupofactor['var'] == "A RH -") { echo $sel; } ?>>A RH -</option>
        <option value="B RH +" <?php if ($grupofactor['var'] == "B RH +") { echo $sel; } ?>>B RH +</option>
        <option value="B RH -" <?php if ($grupofactor['var'] == "B RH -") { echo $sel; } ?>>B RH -</option>
        <option value="AB RH +" <?php if ($grupofactor['var'] == "AB RH +") { echo $sel; } ?>>AB RH +</option>
        <option value="AB RH -" <?php if ($grupofactor['var'] == "AB RH -") { echo $sel; } ?>>AB RH -</option>
        <option value="0 RH +" <?php if ($grupofactor['var'] == "0 RH +") { echo $sel; } ?>>0 RH +</option>
        <option value="0 RH -" <?php if ($grupofactor['var'] == "0 RH -") { echo $sel; } ?>>0 RH -</option>
      </select>&nbsp;<span style="color:#FF0000"><?php echo $grupofactor['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><p>&nbsp;</p></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h2>Antecedentes de Enfermedad</h2></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>¿Tiene alguna enfermedad que requiera periódicamente tratamiento o control médico?</td>
      <td align="left"><input name="enftpcm" type="checkbox" id="enftpcm" value="1" <?php if ($enftpcm['var']=='1') { echo "checked=\"checked\""; }?> />&nbsp;<span style="color:#FF0000"><?php echo $enftpcm['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Durante los últimos 3 años: ¿fue internado alguna vez?</td>
      <td align="left"><input name="intu3a" type="checkbox" id="intu3a" value="1" <?php if ($intu3a['var']=='1') { echo "checked=\"checked\""; }?>/>&nbsp;<span style="color:#FF0000"><?php echo $intu3a['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>¿Tiene algún tipo de alergia?</td>
      <td align="left"><input name="alergia" type="checkbox" id="alergia" value="1" <?php if ($alergia['var']=='1') { echo "checked=\"checked\""; }?>/>&nbsp;<span style="color:#FF0000"><?php echo $alergia['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>En caso de respuesta afirmativa: ¿Cómo se manifiesta?</td>
      <td align="left"><textarea name="alergiadesc" id="alergiadesc" cols="45" rows="5"><?php echo $alergiadesc['var'];?></textarea>
        <span style="color:#FF0000"><?php echo $alergiadesc['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>¿Recibe tratamiento permanente para la alergia?</td>
      <td align="left"><input name="alergiatratamiento" type="checkbox" id="alergiatratamiento" value="1" <?php if ($alergiatratamiento['var']=='1') { echo "checked=\"checked\""; }?>/>&nbsp;<span style="color:#FF0000"><?php echo $alergiatratamiento['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Especifique:</td>
      <td align="left"><textarea name="alergiatratamientodesc" id="alergiatratamientodesc" cols="45" rows="5"><?php echo $alergiatratamientodesc['var'];?></textarea>
        <span style="color:#FF0000"><?php echo $alergiatratamientodesc['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><p>&nbsp;</p></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h2>Tratamientos</h2></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>¿Recibe tratamiento médico?</td>
      <td align="left"><input name="tratamiento" type="checkbox" id="tratamiento" value="1" <?php if ($tratamiento['var']=='1') { echo "checked=\"checked\""; }?>/>&nbsp;<span style="color:#FF0000"><?php echo $tratamiento['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Especifique:</td>
      <td align="left"><textarea name="tratamientodesc" id="tratamientodesc" cols="45" rows="5"><?php echo $tratamientodesc['var'];?></textarea>
      <span style="color:#FF0000"><?php echo $tratamientodesc['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>      
    	<td width="40"></td>
        <td>¿Tuvo algún tipo de cirugía?</td>
        <td align="left"><input name="opera" type="checkbox" id="opera" value="1" <?php if ($opera['var']=='1') { echo "checked=\"checked\""; }?>/>
      &nbsp;<span style="color:#FF0000"><?php echo $opera['error'];?></span></td>
        <td align="left">&nbsp;</td>
    </tr>
    <tr>      
     	<td width="40"></td>
        <td>¿A qué edad?</td>
        <td align="left"><input title="¿A qué edad?" name="operaedad" type="text" id="operaedad" value="<?php echo $operaedad['var'];  ?>" size="30" maxlength="40"/>
      &nbsp;<span style="color:#FF0000"><?php echo $operaedad['error'];?></span></td>
        <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>¿De qué tipo?</td>
      <td align="left"><textarea name="operadesc" id="operadesc" cols="45" rows="5"><?php echo $operadesc['var'];?></textarea>
      <span style="color:#FF0000"><?php echo $operadesc['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>¿Presenta algún tipo de limitación física?</td>
      <td align="left"><input name="limitacionfisica" type="checkbox" id="limitacionfisica" value="1" <?php if ($limitacionfisica['var']=='1') { echo "checked=\"checked\""; }?>/>
      &nbsp;<span style="color:#FF0000"><?php echo $limitacionfisica['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Otros problemas de salud:</td>
      <td align="left"><textarea name="otrossalud" id="otrossalud" cols="45" rows="5"><?php echo $otrossalud['var'];?></textarea>
      <span style="color:#FF0000"><?php echo $otrossalud['error'];?></span> </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><p>&nbsp;</p></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><h2>Dietas y otros</h2></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Dieta especial:</td>
      <td align="left"><input name="dietaesp" type="checkbox" id="dietaesp" value="1" <?php if ($dietaesp['var']=='1') { echo "checked=\"checked\""; }?>/>
      &nbsp;<span style="color:#FF0000"><?php echo $dietaesp['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
      <tr>
        <td width="40">&nbsp;</td>
        <td>Especifique:</td>
      <td align="left"><textarea name="dietaespdesc" id="dietaespdesc" cols="45" rows="5"><?php echo $dietaespdesc['var'];?></textarea>
      <span style="color:#FF0000"><?php echo $dietaespdesc['error'];?></span> </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Dieta Vegetariana</td>
      <td align="left"><input name="dietaveg" type="checkbox" id="dietaveg" value="1" <?php if ($dietaveg['var']=='1') { echo "checked=\"checked\""; }?>/>
      &nbsp;<span style="color:#FF0000"><?php echo $dietaveg['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Especifique:</td>
      <td align="left"><textarea name="dietavegdesc" id="dietavegdesc" cols="45" rows="5"><?php echo $dietavegdesc['var'];?></textarea>
      <span style="color:#FF0000"><?php echo $dietavegdesc['error'];?></span> </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Fuma:</td>
      <td align="left"><input name="fuma" type="checkbox" id="fuma" value="1" <?php if ($fuma['var']=='1') { echo "checked=\"checked\""; }?>/>
      &nbsp;<span style="color:#FF0000"><?php echo $fuma['error'];?></span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="40">&nbsp;</td>
      <td>Lateralidad:</td>
      <td align="left"><select name="lateralidad" id="lateralidad" title="Seleccione su lateralidad">
		<?php $sel = "selected=\"selected\""; ?>
        <option value="" <?php if ($lateralidad['var'] == "") { echo $sel; } ?>>Seleccione</option>
        <option value="Diestro" <?php if ($lateralidad['var'] == "Diestro") { echo $sel; } ?>>Diestro</option>
        <option value="Zurdo" <?php if ($lateralidad['var'] == "Zurdo") { echo $sel; } ?>>Zurdo</option>
        <option value="Ambidiestro" <?php if ($lateralidad['var'] == "Ambidiestro") { echo $sel; } ?>>Ambidiestro</option>
      </select>&nbsp;<span style="color:#FF0000"><?php echo $lateralidad['error'];?></span>      </td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><p>&nbsp;</p></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><p>&nbsp;</p></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
        <input type="submit" name="enviar" id="enviar" value="Enviar" />
        <input type="reset" name="cancelar" id="cancelar" value="Cancelar" onclick="location.href='index.php';" />		</td>
    </tr>
  </table>
</form>
