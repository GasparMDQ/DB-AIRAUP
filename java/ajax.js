var RecaptchaOptions = {
theme: 'blackglass' 
};

function getProv(idpais){
	var dataString = "includes/findprov.php?pais="+idpais;
	$("#provinciadiv").load(dataString);
}

function getCiudad(idprov){
	var dataString = "includes/findciudad.php?prov="+idprov;
	$("#ciudaddiv").load(dataString);
}

function getClub(iddistrito){
	var dataString = "includes/findclub.php?dist="+iddistrito;
	$("#clubdiv").load(dataString);
}

function getClubDetalle(idclub){
	var dataString = 'includes/detalleclub.php?club='+idclub;
	$("#detalleclubes").load(dataString);
}
