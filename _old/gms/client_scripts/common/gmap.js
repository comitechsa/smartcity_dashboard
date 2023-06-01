window.OverlapMapCav = null;
var GmapMap = null;

function showMap (objid)
{
	initializeMap(document.getElementById(objid));
}

function showMapV3(objid)
{
	initializeMapV3(document.getElementById(objid));
}

function initializeMapV3(obj) 
{	
	//var geocoder = new google.maps.Geocoder();
	
	var Zoom  = 5;
	var LatLng = new google.maps.LatLng( 39.1130, 24.0313 );
	if(obj.value != "")
	{
		Zoom  = 12;
		var LatLngAr = obj.value.split(",");
		LatLng = new google.maps.LatLng( LatLngAr[0], LatLngAr[1] );
	}
		
	var mcav=document.getElementById('map_canvas');
	if(!mcav)
	{
		var mcav = document.createElement('div');
		mcav.setAttribute('id',"map_canvas");
		mcav.className = "m_tb";
		mcav.innerHTML = 'Element Number';
		var body = document.getElementsByTagName("body").item(0);
		body.appendChild(mcav);
	}
	
	mcav.style.position = "absolute";
	mcav.style.height = "240px";
	mcav.style.width = "360px";
	mcav.style.top = (cmGetY(obj)+cmGetHeight(obj))+"px";
	mcav.style.left = cmGetX(obj)+"px";
	mcav.style.display="block";
	window.OverlapMapCav = mcav;
	
	var map = new google.maps.Map(document.getElementById('map_canvas'), {zoom: Zoom, center: LatLng, mapTypeId: google.maps.MapTypeId.ROADMAP});
	var marker = new google.maps.Marker({position: LatLng, map: map,draggable: true});
  
  	google.maps.event.addListener(marker, 'drag', function() {		
		obj.value = [
			marker.getPosition().lat().toFixed(6),
			marker.getPosition().lng().toFixed(6)
		].join(', ');
	});
	
}

function initializeMap(obj) 
{
  if (GBrowserIsCompatible())
  {
	  	var Zoom  = 5;
		var LatLng = new GLatLng( 39.1130, 24.0313 );
		if(obj.value != "")
		{
	  		Zoom  = 16;
			var LatLngAr = obj.value.split(",");
			LatLng = new GLatLng( LatLngAr[0], LatLngAr[1] );
		}
		
		var mcav=document.getElementById('map_canvas');
		if(!mcav)
		{
			var mcav = document.createElement('div');
			mcav.setAttribute('id',"map_canvas");
			mcav.className = "m_tb";
			mcav.innerHTML = 'Element Number';
			var body = document.getElementsByTagName("body").item(0);
			body.appendChild(mcav);
		}
		
		mcav.style.position = "absolute";
		mcav.style.height = "240px";
		mcav.style.width = "360px";
		mcav.style.top = (cmGetY(obj)+cmGetHeight(obj))+"px";
		mcav.style.left = cmGetX(obj)+"px";
		mcav.style.display="block";
		window.OverlapMapCav = mcav;
	
		if(GmapMap == null)
		{
			GmapMap = new GMap2(mcav);
			GmapMap.addControl(new GSmallMapControl());
			GmapMap.addControl(new GMapTypeControl());
			GEvent.addListener(GmapMap, "click", function(overlay, latLng)
			{
				RecreateMarker(latLng, obj);
				obj.value = latLng.lat().toFixed(6) + "," + latLng.lng().toFixed(6);
			});
		}

		GmapMap.setCenter(LatLng, Zoom);
		RecreateMarker(LatLng, obj);
  	}
}

function RecreateMarker(LatLng, obj)
{
	GmapMap.clearOverlays(); 
	var GmapMarker = new GMarker(LatLng, {draggable: true});
	GEvent.addListener(GmapMarker, "dragstart", function() { GmapMap.closeInfoWindow(); });
	GEvent.addListener(GmapMarker, "dragend", function() {  obj.value = GmapMarker.getPoint().lat().toFixed(6) + "," + GmapMarker.getPoint().lng().toFixed(6);});		
	GmapMap.addOverlay(GmapMarker);
}

CheckMapCav = function(ev) {
	if (window.OverlapMapCav) {
		var el = Browser.isIE ? window.event.srcElement : ev.target;
		for (; el != null && el.id != (window.OverlapMapCav.id); el = el.parentNode);
		if (el == null) {
			window.OverlapMapCav.style.display = "none";
		}
	}
};

addEvent(document, "mousedown", CheckMapCav);
window.onunload = function()
{
	if(GmapMap) GUnload();
}    

