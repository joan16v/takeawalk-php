<?php

// -----------------------------------------
// ----- take a walk -----------------------
// -----------------------------------------

include('include/application_top.php');

if( !isset($_GET['id']) ) {
    header("Location: index.php");
    exit(0);
}
$id=$_GET['id'];
if( mysql_num_rows(mysql_query("select * from rutas where id='".$id."'"))==0 ) {
    header("Location: index.php");
    exit(0);    
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>

<title>Takeawalk.es - Ruta <? echo $id; ?></title> 

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
        
            <?            
                //RUTA
                $sqlruta="select * from rutas where id='".$id."'";
                $exruta=mysql_query($sqlruta);
                $row2=mysql_fetch_object($exruta);
            	$xml=simplexml_load_string($row2->ruta);
            	$i=0; $n=0; $intervaloCaptura=1;
                $geo="";
                $start_lat=""; $start_lon=""; $end_lat=""; $end_lon="";
            	foreach ($xml->trk->trkseg->trkpt as $posicion) {
            		if($i==0) {
            			$attr=$posicion->attributes();
                        if( $attr['lat']!=-1 && $attr['lon']!=-1 && $attr['lat']!=999 && $attr['lon']!=999 && $attr['lat']!=0 && $attr['lon']!=0 ) {
                            $geo.="new GLatLng(".$attr['lat'].", ".$attr['lon']."),";
                            if( $start_lat=="" ) $start_lat=$attr['lat'];
                            if( $start_lon=="" ) $start_lon=$attr['lon'];
                            $end_lat=$attr['lat'];
                            $end_lon=$attr['lon'];                            
                        }
            			$n++;
            		}
            		$i++;
            		if($i==$intervaloCaptura){ $i=0; }
            	}
            	$geo=substr($geo,0,-1);            
                
                $start_lat_bbdd=mysql_result(mysql_query("select start_lat from rutas where id='".$id."'"),0,"start_lat");
                if( $start_lat_bbdd==0 ) {
                    mysql_query("update rutas set start_lat='".$start_lat."' where id='".$id."'");                    
                }
                $start_lon_bbdd=mysql_result(mysql_query("select start_lon from rutas where id='".$id."'"),0,"start_lon");
                if( $start_lon_bbdd==0 ) {
                    mysql_query("update rutas set start_lon='".$start_lon."' where id='".$id."'");                    
                }                                
                
            ?>
            <script type="text/javascript">
                function paint_map() {
                    var map = new GMap2(document.getElementById("ruta"));
                    map.setMapType(G_HYBRID_MAP);
                    map.enableScrollWheelZoom();
                    var mapControl = new GMapTypeControl();
                    map.addControl(mapControl);
                    var mapControl = new GLargeMapControl();
                    map.addControl(mapControl);     
                    
                    //iconos de start y end
                    var miIcono = new GIcon(G_DEFAULT_ICON);
                    miIcono.image = "/images/marker_rounded_green.png";
                    var tamanoIcono = new GSize(16,16);
                    miIcono.iconSize = tamanoIcono;
                    miIcono.iconAnchor = new GPoint(5,5);
                    var tamanoSombra = new GSize(0,0);
                    miIcono.shadowSize = tamanoSombra;
                    var miIcono2 = new GIcon(G_DEFAULT_ICON);
                    miIcono2.image = "/images/marker_rounded_red.png";
                    var tamanoIcono2 = new GSize(16,16);
                    miIcono2.iconSize = tamanoIcono2;
                    miIcono2.iconAnchor = new GPoint(5,5);
                    var tamanoSombra2 = new GSize(0,0);
                    miIcono2.shadowSize = tamanoSombra2;            
                    
                    markerStart=new GMarker(new GLatLng(<? echo $start_lat; ?>,<? echo $start_lon; ?>),miIcono); map.addOverlay(markerStart);
                    markerEnd=new GMarker(new GLatLng(<? echo $end_lat; ?>,<? echo $end_lon; ?>),miIcono2); map.addOverlay(markerEnd);        
                                   
                    eval("markerRuta = new GPolyline([<? echo $geo; ?>], '#0000ff', 3);");
                    map.addOverlay(markerRuta);
                    var bounds = markerRuta.getBounds();
                    var centro = bounds.getCenter();
                    map.setCenter(centro);
                    var zoom = map.getBoundsZoomLevel(bounds);
                    map.setZoom(zoom);  
                    longitud=markerRuta.getLength();
                    var longitud_mostrar;
                    if( longitud>999 ) {
                        var num=(longitud/1000);
                        num=num.toFixed(2);
                        longitud_mostrar=num+" km";
                    } else {
                        longitud=longitud.toFixed(0);
                        longitud_mostrar=longitud+" m";
                    }
                    document.getElementById('longitud_ruta').innerHTML=longitud_mostrar;    
                    
                    //update de longitud en bbdd
                    var ajax_url='ajax/update_longitud.php';
                    $.ajax({
                            url:ajax_url, 
                            type:'POST', 
                            data: 'id=<? echo $id; ?>&long='+longitud, 
                            beforeSend: function(){ },
                            success:function(datos){ }
                    });                       
                              
                }
                $(document).ready(function() {
                    paint_map();
                });        
            </script>           

            <? $mail_usuario=mysql_result(mysql_query("select email from usuarios where id='".$row2->id_usuario."'"),0,"email"); ?>
            
            <? 
            
                $lugar=mysql_result(mysql_query("select ciudad from rutas where id='".$id."'"),0,"ciudad");
                if( trim($lugar)=="" ) {
                    $codificacion_geografica_inversa = file_get_contents("http://maps.google.com/maps/geo?q=".$start_lat.",".$start_lon); 
                    if( strpos($codificacion_geografica_inversa,"LocalityName")!=FALSE ) {
                        $pos=strpos($codificacion_geografica_inversa,'"LocalityName" : "');
                        $sub=substr($codificacion_geografica_inversa,$pos+18); //echo $sub;
                        $pos2=strpos($sub,'"');
                        $ciudad=substr($sub,0,$pos2);
                        $ciudad=utf8_decode($ciudad);
                        mysql_query("update rutas set ciudad='".addslashes($ciudad)."' where id='".$id."'");                                
                    } else {
                        $ciudad="N/D";
                        mysql_query("update rutas set ciudad='".addslashes($ciudad)."' where id='".$id."'");       
                    }                                    
                } else {
                    $ciudad=$lugar;
                }            
            
            ?>
            
            <div style="position: absolute; top:160px; left:15px;">Ruta en <b><? echo $ciudad; ?></b> | Ruta creada por: <b><? echo $mail_usuario; ?></b></div>
            
            <div id="ruta" style="position: absolute; top: 180px; left:15px; width:650px; height:400px; border:3px solid #333;">  
            </div>
            <div style="position: absolute; top:600px; left:20px;">
                Longitud de la ruta: <span id="longitud_ruta" style="font-weight: bold; font-size:14px;"></span>
            </div>
            
            <script type="text/javascript">
                function cargar_fotos() {
                    var ajax_url='ajax/fotos_ruta.php';
                    $.ajax({
                            url:ajax_url, 
                            type:'POST', 
                            data: 'id=<? echo $id; ?>', 
                            beforeSend: function(){  
                                $('#fotos_ruta').html('<div style="margin-top:15px; margin-left:15px; font-size:10px;">Cargando fotos...<br><img src="images/ajax.gif"></div>');
                            },
                            success:function(datos){
                                $('#fotos_ruta').html(datos);
                            }
                    });              
                }
                $(document).ready(function() {
                    cargar_fotos();
                });            
            </script>
            <div style="position: absolute; top:180px; left:685px; width:115px; height:400px; border:3px solid #333; overflow:auto;" id="fotos_ruta">
            </div>   
            
            <div style="position: absolute; top:630px; left:20px;">
                <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.takeawalk.es%2Fruta.php%3Fid%3D<? echo $id; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>            
            </div>
            <div style="position: absolute; top:660px; left:20px;">
                <a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="http://www.takeawalk.es/ruta.php?id=<? echo $id; ?>" data-lang="es">Tweet</a>
                <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>                
            </div>      
            
            <div style="position: absolute; top:600px; right:150px;">
                <a href="/ajax/gpx.php?id=<? echo $id; ?>">Exportar ruta en GPX</a>
            </div>
            
            <div style="position: absolute; top:620px; right:150px; text-align:right; font-weight:bold;">
                Valorar la ruta: 
                <? if( isset( $_SESSION['id_usuario'] ) ) {
                    ?><script type="text/javascript">
                        function valorar_ruta() {
                            var ajax_url='ajax/valorar_ruta.php';
                            $.ajax({
                                    url:ajax_url, 
                                    type:'POST', 
                                    data: 'id=<? echo $id; ?>&voto='+document.getElementById('valorar_ruta').value,
                                    beforeSend: function(){ },
                                    success:function(datos){ }
                            });                             
                        }
                    </script>
                    <?                    
                        $sqlr="select * from valoraciones_rutas where id_usuario='".$_SESSION['id_usuario']."' and id_ruta='".$id."'";
                        if( mysql_num_rows(mysql_query($sqlr))>0 ) {
                            $rowr=mysql_fetch_object(mysql_query($sqlr));
                            $valoracion=$rowr->valoracion; //echo "valoracion: ".$valoracion;
                        }                    
                    ?>
                    <select id="valorar_ruta" onchange="valorar_ruta()" style="font-size: 10px;">
                        <option value="0" <? if($valoracion==0) echo "selected"; ?>>0</option>
                        <option value="1" <? if($valoracion==1) echo "selected"; ?>>1</option>
                        <option value="2" <? if($valoracion==2) echo "selected"; ?>>2</option>
                        <option value="3" <? if($valoracion==3) echo "selected"; ?>>3</option>
                        <option value="4" <? if($valoracion==4) echo "selected"; ?>>4</option>
                        <option value="5" <? if($valoracion==5) echo "selected"; ?>>5</option>
                        <option value="6" <? if($valoracion==6) echo "selected"; ?>>6</option>
                        <option value="7" <? if($valoracion==7) echo "selected"; ?>>7</option>
                        <option value="8" <? if($valoracion==8) echo "selected"; ?>>8</option>
                        <option value="9" <? if($valoracion==9) echo "selected"; ?>>9</option>
                        <option value="10" <? if($valoracion==10) echo "selected"; ?>>10</option>
                    </select><?
                } else {
                    ?><br /><span style="font-size: 10px; font-weight:normal;">Inicia sesión para valorar la ruta.</span><?
                } ?>
            </div>                  
        
		<div style="clear:both"><!-- separador --></div>
		
		<div id="pie">
             <? include("pie.php"); ?>
        </div>

</div>

</body>
</html>