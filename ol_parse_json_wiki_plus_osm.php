<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>OpenLayers: Vector Features</title>
    <link rel="stylesheet" href="http://openlayers.org/dev/theme/default/style.css" type="text/css">
    <link rel="stylesheet" href="http://openlayers.org/dev/examples/style.css" type="text/css">
    <script src="http://openlayers.org/dev/OpenLayers.js" type="text/javascript"></script>
    <script type="text/javascript">

var map;

function init() {
    map = new OpenLayers.Map("map",{projection:"EPSG:3857"});

    var osm = new OpenLayers.Layer.OSM();
    var toMercator = OpenLayers.Projection.transforms['EPSG:4326']['EPSG:3857'];
    var center = toMercator({x:8.93317, y:45.60132});
  
    var features = [];    


<?php
           $url="http://overpass-api.de/api/interpreter?data=[out:json];node(45.5907,8.9077,45.617,8.9511);out;";
$json = file_get_contents($url);
$data = json_decode($json, TRUE);



  
$i=0;
foreach($data['elements']  as $obj) {
    if (isset($obj['tags']["wikipedia"])) {
    $lat=$obj['lat'];
    $lon=$obj['lon'];
    
	$wiki=$obj['tags']["wikipedia"];
    $image=$obj['tags']["image"];

  echo <<<EOD

        features[$i] = new OpenLayers.Feature.Vector(
                toMercator(new OpenLayers.Geometry.Point(
                    $lon,
                    $lat)), 
                {
                    name : "$wiki",
                    image : "$image"
                }, {
                    fillColor : '#008040',
                    fillOpacity : 0.8,                    
                    strokeColor : "#ee9900",
                    strokeOpacity : 1,
                    strokeWidth : 1,
                    pointRadius : 8
                });


EOD;
/*
new OpenLayers.Geometry.Point($lon,$lat),
            {
            foo : 100 * Math.random() | 0
            }, {
                    fillColor : '#008040',
                    fillOpacity : 0.8,                    
                    strokeOpacity : 1,
                    strokeWidth : 1,
                    pointRadius : 8
                }*/

    $i++;
    }        
    else {}
}

/**/
?>  

        
        // create the layer with listeners to create and destroy popups
    var vector = new OpenLayers.Layer.Vector("Points",{
        eventListeners:{
            'featureselected':function(evt){
                var feature = evt.feature;
                var popup = new OpenLayers.Popup.FramedCloud("popup",
                    OpenLayers.LonLat.fromString(feature.geometry.toShortString()),
                    null,
                    "<div style='font-size:.8em;text-align:center;'>"
                        +"Feature: " + feature.id +"<br>"
                        +"<img src='" + feature.attributes.image+"' width='160' height='120' /><br>"
                        +"<a href='" + feature.attributes.name+"' target='_blank'>Voce Wikipedia</a>"
                    +"</div>",
                    null,
                    true
                );
                feature.popup = popup;
                map.addPopup(popup);
            },
            'featureunselected':function(evt){
                var feature = evt.feature;
                map.removePopup(feature.popup);
                feature.popup.destroy();
                feature.popup = null;
            }
        }
    });
    
    vector.addFeatures(features);

    // create the select feature control
    var selector = new OpenLayers.Control.SelectFeature(vector,{
        click:true,
        autoActivate:true
    }); 
    
    map.addLayers([osm, vector]);
    map.addControl(selector);
    map.setCenter(new OpenLayers.LonLat(center.x,center.y), 13);

}

    
    </script>
  </head>
  <body onload="init()">
<h1 id="title">OSM e Wikipedia: integrazione perfetta!</h1>

<div id="tags">
    vector, feature, light
</div>
<p id="shortdesc">
    Mediante questo esempio è possibile catturare i nodi su OSM che contengono il tag Wikipedia
    e nel popup verrà visualizzata la foto (sempre nel tag) e il link alla voce di Wikipedia corrispondente.
</p>
<div id="map" class="smallmap" style="width:100%;height:400px;"></div>
<div id="docs">
    <p>Prova a cliccare su uno dei punti verdi per visualizzare la foto associata e il link all'articolo di Wikipedia</p>
</div>

  </body>
</html>
