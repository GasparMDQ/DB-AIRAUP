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

function fichaSocio(idsocio){
	var dataString = 'includes/fichasocio.php?socio='+idsocio;
	$("#ficha_socio").load(dataString);
}

//FUNCIONES PARA MAPAS DE GOOGLE

var geocoder;
var map;

function initialize() {
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(0,0);
	var myOptions = {
		zoom: 14,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	codeAddress(true)
}


function codeAddress(flag) {
	var address = document.getElementById("direccion").value;
	var lat = document.getElementById("lat").value;
	var long = document.getElementById("long").value;
	if (lat!='' && long!='') {
 		var myLatlng = new google.maps.LatLng(lat,long);
		map.setCenter(myLatlng);
 		if (flag) {
			var marker = new google.maps.Marker({
				map: map, 
				position: myLatlng
			});
		}
	} else if (geocoder) {
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				if (flag) {
					var marker = new google.maps.Marker({
						map: map, 
						position: results[0].geometry.location
					});
				}
			}
		});
	}
}
