// Copyright 2007 - Michael Uyttersprot / eMich.be
// v0.1b - 07.03.01

var gmlb_width=640;
var gmlb_height=480;
var gmOverlay;
var gmContainer;
var gmMap;
var gmLabel;
var gmMapObject = null;
var gm_zoom;
var gm_mapType;
var gm_close;
var gm_marker = null;

function gmLb_init(){
	var linksArr=document.getElementsByTagName("a");
	for(i = 0;i<linksArr.length;i++){
		if(linksArr[i].getAttribute("rel")=="gmap"){
			//linksArr[i].onclick=function(){gm_ShowMap(this);return false;};
			gmLbAddClickEvent(linksArr[i],function(){gm_ShowMap(this);return false;});
		}
	}

	gmOverlay = document.createElement("div");
	gmOverlay.setAttribute("id","gmlb_overlay");
	gmOverlay.style.display="none";
	gmOverlay.style.position=document.all?"absolute":"fixed";
	gmOverlay.style.top="0px";
	gmOverlay.style.left="0px";
	
	gmOverlay.style.width=gmlb_width+"px";
	gmOverlay.style.height=gmlb_height+"px";
	gmOverlay.style.width = getFrameWidth();
	gmOverlay.style.height = getFrameHeight();

	gmContainer = document.createElement("div");
	gmContainer.setAttribute("id","gmlb_container");
	gmContainer.style.width=gmlb_width+"px";
	gmContainer.style.position="absolute";
	gmContainer.style.display="inline";

	gmLabel = document.createElement("div");
	gmLabel.setAttribute("id","gmlb_label");
	gmLabel.setAttribute("class","gmlb_label");
	gmLabel.style.display="block";
	gmLabel.innerHTML="test";

	gmClose = document.createElement("div");
	gmClose.setAttribute("id","gmlb_close");
	gmClose.setAttribute("class","gmlb_close");
	gmClose.style.display="block";
	gmClose.onclick=function(){gmOverlay.style.display='none';gmLbOnClose();};

	gmMap = document.createElement("div");
	gmMap.setAttribute("id","gmlb_map");
	gmMap.style.width=gmlb_width+"px";
	gmMap.style.height=gmlb_height+"px";
	gmMap.style.display="block";
	gmMap.style.float="left";
	gmMap.onclick=function(){return false;};
	
	gmContainer.appendChild(gmMap);
	gmContainer.appendChild(gmLabel);
	gmContainer.appendChild(gmClose);
	gmOverlay.appendChild(gmContainer);
	document.documentElement.ownerDocument.body.appendChild(gmOverlay);
}

function gm_ShowMap(obj){
	if(document.all){
		gmOverlay.style.top = getScrollHeight()+"px";
		gmOverlay.style.left = getScrollWidth()+"px";
	}
	gmOverlay.style.width=getFrameWidth()+"px";
	gmOverlay.style.height=getFrameHeight()+"px";

	if(obj.title){
		gmLabel.innerHTML=obj.title;
	}
	else{
		gmLabel.innerHTML="Google Map";
	}

	gmContainer.style.visibility="hidden";
	document.getElementById("gmlb_overlay").style.display="";
	gmContainer.style.left=((getFrameWidth()-gmContainer.offsetWidth)/2)+"px";
	gmContainer.style.top=((getFrameHeight()-gmContainer.offsetHeight)/2)+"px";
	gmContainer.style.visibility="";
	var params = parseGmapLink(obj.href);
	gm_setMap(params);
	gmLbOnShow();
}

function gmLbOnShow(){
	;
}

function gmLbOnClose(){
	;
}

function getFrameWidth(){
	if (self.innerWidth)
	{
		return self.innerWidth;
	}
	else if (document.documentElement && document.documentElement.clientWidth)
	{
		return document.documentElement.clientWidth;
	}
	else if (document.body)
	{
		return document.body.clientWidth;
	}
	else return;
}

function getFrameHeight(){
	if (self.innerWidth)
	{
		return self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientWidth)
	{
		return document.documentElement.clientHeight;
	}
	else if (document.body)
	{
		return document.body.clientHeight;
	}
	else return;
}

function getScrollWidth()
{
   var w = window.pageXOffset ||
           document.body.scrollLeft ||
           document.documentElement.scrollLeft;
           
   return w ? w : 0;
}

function getScrollHeight()
{
   var h = window.pageYOffset ||
           document.body.scrollTop ||
           document.documentElement.scrollTop;
           
   return h ? h : 0;
}

function gm_setMap(params) {
	if (GBrowserIsCompatible()) {
		if(!gmMapObject)
		{
			gmMapObject = new GMap2(gmMap);
			gmMapObject.addControl(new GLargeMapControl());
			gmMapObject.addControl(new GMapTypeControl());
		}
		gm_mapType=G_NORMAL_MAP;
		gm_zoom=10;
		if(params["t"]=="k"){gm_mapType=G_SATELLITE_MAP;gm_zoom=parseInt(params["z"]);}
		else if(params["t"]=="" || params["t"]==null){gm_mapType=G_NORMAL_MAP;gm_zoom=parseInt(params["z"]);}
		else if(params["t"]=="h"){gm_mapType=G_HYBRID_MAP;gm_zoom=parseInt(params["z"]);}

		if(params["lat"]==null && params["long"]==null && params["q"]!=null && params["q"].indexOf("http://")==-1){

			  var geocoder = new GClientGeocoder();
  
			  geocoder.getLatLng(
				unescape(params["q"].replace(/\+/g,  " ")),
				function(point){
					gmMapObject.setCenter(point, gm_zoom,gm_mapType);
					gm_addMarker(point);
				}
			  );

		}
		else if(params["q"]!=null && params["q"].indexOf('http://')!=-1){
			//alert(params["q"]);
			//var geoxml = new GGeoXml(params["q"]);
			//var geoxml = new GGeoXml("http://www.emich.be/fr/kml");
			//gm_addXMLMarker(geoxml);
		}
		else{
			var point = new GLatLng(params["lat"],params["long"]);
			gmMapObject.setCenter(point, gm_zoom,gm_mapType);
			gm_addMarker(point);
		}
	}
}

function gm_addMarker(point){
	if(gm_marker!=null){gmMapObject.removeOverlay(gm_marker);}
	gm_marker = new GMarker(point);
	gmMapObject.addOverlay(gm_marker);
}

function gm_addXMLMarker(geoxml){
	if(gm_marker!=null){gmMapObject.removeOverlay(gm_marker);}
	gm_marker = geoxml;
	gmMapObject.addOverlay(gm_marker);
}

function parseGmapLink(url){
	var parts = url.split("?");
	if(parts.length<2)return new Array();
	var params = parts[1].split("&");
	var gMapParams=new Array();
	for(i = 0 ; i < params.length ; i++){
		keyValue=params[i].split("=");
		if(keyValue[0]!="ll"){
			gMapParams[keyValue[0]]=keyValue[1];
		}
		else{
			var latlong=keyValue[1].split(",");
			gMapParams["lat"]=latlong[0];
			gMapParams["long"]=latlong[1];
		}
	}
	return gMapParams;
}

function showAddress(address) {
  var geocoder = new GClientGeocoder();
  
  geocoder.getLatLng(
    address,
    function(point) {
      if (!point) {
        alert(address + " not found");
      } else {
        gmSetCenter(point);
      }
    }
  );
}

function gmSetCenter(point){
	alert(point);
	document["gmCenter"]=point;
}

function gmLbAddLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	}
	else {
		window.onload = function() {
			oldonload();
			func();
		}
	}
}

function gmLbAddClickEvent(obj,func) {
	var oldonclick = obj.onclick;
	if (typeof obj.onclick != 'function') {
		obj.onclick = func;
	}
	else {
		obj.onclick = function() {
			oldonclick();
			func();
		}
	}
}

gmLbAddLoadEvent(gmLb_init);