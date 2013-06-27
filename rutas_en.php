<?php

// -----------------------------------------
// ----- take a walk -----------------------
// -----------------------------------------

include('include/application_top.php');

if( !isset($_GET['ciudad']) ) {
    header("Location: index.php");
    exit(0);
}
$ciudad=$_GET['ciudad'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>

<title>Takeawalk.es - Rutas en <? echo $ciudad; ?></title> 

<?php

//inclusion de metatags
include('metas.php');

include('include/header.php');

?>

<script type="text/javascript" src="/js/lightbox/js/jquery.lightbox-0.5.js"></script> 
<link rel="stylesheet" type="text/css" href="/js/lightbox/css/jquery.lightbox-0.5.css" media="screen" /> 

</head>

<body>    

<div id="container" style="height: 700px;">
           
        <div id="logo">
            <? include('include/logo.php'); ?>
        </div>
		
		<div id="login">
            <? include('include/login_header.php'); ?>
		</div>	
        
            <script type="text/javascript">
                var geocoder;
                function showAddress(address) {
                      if (geocoder) {
                        geocoder.getLatLng(
                          address,
                          function(point) {
                            if (!point) {
                              alert(address + " not found");
                            } else {
                              map.setCenter(point, 13);
                            }
                          }
                        );
                      }
                }         
                function create_marker(lat, lng, html) {
                    var marker = new GMarker( new GLatLng(lat,lng));
                    marker.bindInfoWindow(html);
                    map.addOverlay(marker);
                }                   
                function paint_map() {
                    map = new GMap2(document.getElementById("mapa_ciudad"));     
                    geocoder = new GClientGeocoder();                       
                    map.setMapType(G_HYBRID_MAP);
                    map.enableScrollWheelZoom();
                    var mapControl = new GMapTypeControl();
                    map.addControl(mapControl);
                    var mapControl = new GLargeMapControl();
                    map.addControl(mapControl);                      
                    map.clearOverlays();
                    showAddress('<? echo $ciudad; ?>');        
                    
                    <? 
                    
                    //add markers
                    $sqlm="select * from rutas where ciudad='".$ciudad."'";
                    $exm=mysql_query($sqlm);
                    while( $rowm=mysql_fetch_object($exm) ) {
                        $longitud=number_format($rowm->longitud/1000,2)." km";
                        $lugar=$rowm->ciudad;
                        $descripcion=stripslashes($rowm->descripcion);
                        $mail_us=mysql_result(mysql_query("select email from usuarios where id='".$rowm->id_usuario."'"),0,"email");
                        ?>create_marker(<? echo $rowm->start_lat; ?>,<? echo $rowm->start_lon; ?>,'<div style="font-family:arial; font-size:10px; color:#333;">Ruta en <b><? echo $lugar; ?></b><br><? echo $longitud; ?><br><b><? echo $mail_us; ?></b><br><? echo $descripcion; ?><br>[ <a href="/ruta.php?id=<? echo $rowm->id; ?>">Ver la ruta</a> ]</div>');<?
                    }
                    
                    ?>
                                        
                }
                $(document).ready(function() {
                    paint_map();                    
                });        
            </script>           
            
            <div style="position: absolute; top: 160px; left:15px;">
                Rutas en <b><? echo $ciudad; ?></b> 
            </div>
            
            <div id="mapa_ciudad" style="position: absolute; top: 180px; left:15px; width:780px; height:450px; border:3px solid #333;">  
            </div>            
        
		<div style="clear:both"><!-- separador --></div>
		
		<div id="pie">
             <? include("pie.php"); ?>
        </div>

</div>

</body>
</html>