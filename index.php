<?php

// -----------------------------------------
// ----- take a walk -----------------------
// -----------------------------------------

include('include/application_top.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>

<title>Take awalk</title> 

<?php

//inclusion de metatags
include('metas.php');

include('include/header.php');

?>

</head>

<body>    

<div id="container" style="height: 650px;">
           
        <div id="logo">
            <? include('include/logo.php'); ?>
        </div>
		
		<div id="login">
            <? include('include/login_header.php'); ?>
		</div>	
        
            <?            
                //ULTIMA RUTA
                $sqlruta="select * from rutas order by id desc";
                $exruta=mysql_query($sqlruta);
                $row2=mysql_fetch_object($exruta);
                $id_ultima_ruta=$row2->id;
            	$xml=simplexml_load_string($row2->ruta);
            	$i=0; $n=0; $intervaloCaptura=1;
                $geo="";
            	foreach ($xml->trk->trkseg->trkpt as $posicion) {
            		if($i==0) {
            			$attr=$posicion->attributes();
                        if( $attr['lat']!=-1 && $attr['lon']!=-1 && $attr['lat']!=999 && $attr['lon']!=999 && $attr['lat']!=0 && $attr['lon']!=0 ) {
                            $geo.="new GLatLng(".$attr['lat'].", ".$attr['lon']."),";
                            if( $start_lat=="" ) $start_lat=$attr['lat'];
                            if( $start_lon=="" ) $start_lon=$attr['lon'];
                        }
            			$n++;
            		}
            		$i++;
            		if($i==$intervaloCaptura){ $i=0; }
            	}
            	$geo=substr($geo,0,-1);            
            ?>
            <script type="text/javascript">
                function paint_map() {
                    var map = new GMap2(document.getElementById("ultima_ruta"));
                    map.setMapType(G_HYBRID_MAP);
                    eval("markerRuta = new GPolyline([<? echo $geo; ?>], '#0000ff', 5);");
                    map.addOverlay(markerRuta);
                    var bounds = markerRuta.getBounds();
                    var centro = bounds.getCenter();
                    map.setCenter(centro);
                    var zoom = map.getBoundsZoomLevel(bounds);
                    map.setZoom(zoom);                
                }
                $(document).ready(function() {
                    paint_map();
                });        
            </script>           
        <div style="position: absolute; top:153px; left:15px;">Última ruta:</div>
        <div style="position: absolute; top: 170px; left:15px; width:400px; height:400px; border:3px solid #333;">
            <div id="ultima_ruta" style="position: absolute; top: 0px; left:0px; width:400px; height:400px; z-index:0;">  
            </div>	
            <div style="position: absolute; top:0px; left:0px; width:400px; height:400px; z-index:10;">
                <a href="ruta.php?id=<? echo $id_ultima_ruta; ?>" title="Detalle de la ruta"><img src="images/blank.gif" width="400" height="400" /></a>
            </div>        
        </div>            
		
            <?           
                //PENULTIMA RUTA
                $sqlruta="select * from rutas where id<".$id_ultima_ruta." order by id desc";
                $exruta=mysql_query($sqlruta);
                $row2=mysql_fetch_object($exruta);
                $id_penultima_ruta=$row2->id;
            	$xml=simplexml_load_string($row2->ruta);
            	$i=0; $n=0; $intervaloCaptura=1;
                $geo="";
            	foreach ($xml->trk->trkseg->trkpt as $posicion) {
            		if($i==0) {
            			$attr=$posicion->attributes();
                        if( $attr['lat']!=-1 && $attr['lon']!=-1 && $attr['lat']!=999 && $attr['lon']!=999 && $attr['lat']!=0 && $attr['lon']!=0 ) {
                            $geo.="new GLatLng(".$attr['lat'].", ".$attr['lon']."),";
                            if( $start_lat=="" ) $start_lat=$attr['lat'];
                            if( $start_lon=="" ) $start_lon=$attr['lon'];
                        }
            			$n++;
            		}
            		$i++;
            		if($i==$intervaloCaptura){ $i=0; }
            	}
            	$geo=substr($geo,0,-1);            
            ?>
            <script type="text/javascript">
                function paint_map2() {
                    var map2 = new GMap2(document.getElementById("penultima_ruta"));
                    map2.setMapType(G_HYBRID_MAP);
                    eval("markerRuta = new GPolyline([<? echo $geo; ?>], '#0000ff', 5);");
                    map2.addOverlay(markerRuta);
                    var bounds = markerRuta.getBounds();
                    var centro = bounds.getCenter();
                    map2.setCenter(centro);
                    var zoom = map2.getBoundsZoomLevel(bounds);
                    map2.setZoom(zoom);                
                }
                $(document).ready(function() {
                    paint_map2();
                });        
            </script>     
        <div style="position: absolute; top: 170px; left:445px; width:340px; height:185px; border:3px solid #333;">
            <div id="penultima_ruta" style="position: absolute; top: 0px; left:0px; width:340px; height:185px; z-index:0;">           
            </div>        
            <div style="position: absolute; top:0px; left:0px; width:340px; height:185px; z-index:10;">
                <a href="ruta.php?id=<? echo $id_penultima_ruta; ?>" title="Detalle de la ruta"><img src="images/blank.gif" width="340" height="160" /></a>
            </div>                 
        </div>       
        
            <?           
                //ANTEPENULTIMA RUTA
                $sqlruta="select * from rutas where id<".$id_penultima_ruta." order by id desc";
                $exruta=mysql_query($sqlruta);
                $row2=mysql_fetch_object($exruta);
                $id_antepenultima_ruta=$row2->id;
            	$xml=simplexml_load_string($row2->ruta);
            	$i=0; $n=0; $intervaloCaptura=1;
                $geo="";
            	foreach ($xml->trk->trkseg->trkpt as $posicion) {
            		if($i==0) {
            			$attr=$posicion->attributes();
                        if( $attr['lat']!=-1 && $attr['lon']!=-1 && $attr['lat']!=999 && $attr['lon']!=999 && $attr['lat']!=0 && $attr['lon']!=0 ) {
                            $geo.="new GLatLng(".$attr['lat'].", ".$attr['lon']."),";
                            if( $start_lat=="" ) $start_lat=$attr['lat'];
                            if( $start_lon=="" ) $start_lon=$attr['lon'];
                        }
            			$n++;
            		}
            		$i++;
            		if($i==$intervaloCaptura){ $i=0; }
            	}
            	$geo=substr($geo,0,-1);            
            ?>
            <script type="text/javascript">
                function paint_map3() {
                    var map3 = new GMap2(document.getElementById("antepenultima_ruta"));
                    map3.setMapType(G_HYBRID_MAP);
                    eval("markerRuta = new GPolyline([<? echo $geo; ?>], '#0000ff', 5);");
                    map3.addOverlay(markerRuta);
                    var bounds = markerRuta.getBounds();
                    var centro = bounds.getCenter();
                    map3.setCenter(centro);
                    var zoom = map3.getBoundsZoomLevel(bounds);
                    map3.setZoom(zoom);                
                }
                $(document).ready(function() {
                    paint_map3();
                });        
            </script>  
        <div style="position: absolute; top: 385px; left:445px; width:340px; height:185px; border:3px solid #333;">
            <div id="antepenultima_ruta" style="position: absolute; top: 0px; left:0px; width:340px; height:185px; z-index:0;">
            </div>        
            <div style="position: absolute; top:0px; left:0px; width:340px; height:185px; z-index:10;">
                <a href="ruta.php?id=<? echo $id_antepenultima_ruta; ?>" title="Detalle de la ruta"><img src="images/blank.gif" width="340" height="160" /></a>
            </div>               
        </div>
        
        <div style="position: absolute; top: 615px; left:15px;">
            <script type="text/javascript">
                function ver_rutas_en() {
                    if( document.getElementById("ver_rutas_en").value!=0 ) {
                        window.location='/rutas_en.php?ciudad='+document.getElementById("ver_rutas_en").value;
                    }
                }
            </script>
            Ver rutas en: <select id="ver_rutas_en" onchange="ver_rutas_en()" style="font-size: 10px;">
                <option value="0">-- Escoge una ciudad --</option>
                <? $sqlv="select distinct(ciudad) from rutas where ciudad<>''";
                $exv=mysql_query($sqlv);
                while( $rowv=mysql_fetch_object($exv) ) {
                   ?><option value="<? echo $rowv->ciudad; ?>"><? echo $rowv->ciudad; ?></option><? 
                } ?>
            </select>
        </div>       
        
        <div style="position: absolute; top: 615px; left:250px;">
            <script type="text/javascript">
                function tipo_ruta() {
                    if( document.getElementById("tipo_ruta").value!=0 ) {
                        window.location='/rutas_tipo.php?tipo='+document.getElementById("tipo_ruta").value;
                    }
                }
            </script>            
            Tipo de ruta: <select id="tipo_ruta" onchange="tipo_ruta()" style="font-size: 10px;">
                <option value="0">-- Escoge un tipo --</option>
            		<option value="1">Cultural-Patrimonio</option>
            		<option value="2">Rural-Senderismo</option>
            		<option value="3">Romántica</option>
            		<option value="4">Gastronómica</option>
            		<option value="5">Deportiva</option>
            		<option value="6">Paisajes</option>
            		<option value="7">Ocio-Compras</option>
            		<option value="8">Religiosa</option>
            		<option value="9">Nocturnas</option>
            </select>
        </div>    
        
        <div style="position: absolute; top: 615px; left:470px;">
            <script type="text/javascript">
                function tipo_ruta_general() {
                    if( document.getElementById("tipo_ruta_general").value!=0 ) {
                        window.location='/rutas_general.php?tipo='+document.getElementById("tipo_ruta_general").value;
                    }
                }
            </script>            
            General: <select id="tipo_ruta_general" onchange="tipo_ruta_general()" style="font-size: 10px;">
                <option value="0">-- Escoge un tipo --</option>
                    <option value="1">Ruta a pie</option>
                    <option value="2">Ruta en bici</option>
                    <option value="3">Ruta en coche</option> 
                    <option value="4">Grandes itinerarios</option>
            </select>
        </div>                    
        
		<div style="clear:both"><!-- separador --></div>
		
		<div id="pie">
             <? include("pie.php"); ?>
        </div>

</div>

</body>
</html>