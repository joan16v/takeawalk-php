<? 

chdir("..");
include('include/application_top.php');

if( !isset($_SESSION['usuario']) ) {
    die("Ha caducado la sesión.");
}
if( !isset($_SESSION['id_usuario']) ) {
    die("Ha caducado la sesión.");
}

if ( !function_exists('json_decode') ){
    function json_decode($content, $assoc=false) {
                require_once 'include/JSON.php';
                if ( $assoc ){
                    $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
                    $json = new Services_JSON;
                }
        return $json->decode($content);
    }
}

if( isset($_POST['ruta_valores']) ) {

    $ruta=json_decode($_POST['ruta_valores']);
    $descripcion=addslashes(strip_tags($_POST['descripcion']));

    $xml="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<gpx>
  <metadata>
    <name>10/09/2009 20:13</name>
    <desc>ruta</desc>
    <author>
      <name></name>
    </author>
    <time>2009-09-10T20:16:02.04</time>
  </metadata>
  <trk>
    <name>Track 1</name>
    <trkseg>
";

    for($i=0;$i<count($ruta);$i++) {
        if( $i==0 ) {
            $start_lat=$ruta[$i][1];
            $start_lon=$ruta[$i][0];
        }
        $xml.="        <trkpt lat=\"".$ruta[$i][1]."\" lon=\"".$ruta[$i][0]."\">
        <desc></desc>
        <ele></ele>
        <name></name>
        <speed></speed>
        <dist></dist>
        <lat>".$ruta[$i][1]."</lat>
        <lon>".$ruta[$i][0]."</lon>
      </trkpt>\n";
    }
    $xml.="    </trkseg>
  </trk>
</gpx>
";

    $start_alt=0;

    mysql_query("insert into rutas (ruta,id_usuario,descripcion,tipo_ruta_general,tipo_ruta_clas) values ('".$xml."','".$_SESSION['id_usuario']."','".$descripcion."','".$_POST['tipo_ruta_general']."','".$_POST['tipo_ruta_clas']."')");
    
    $id_ruta=mysql_insert_id();

    /*
    ?>
    <html>
    <head><? /* ?>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script type="text/javascript" src="/fancybox/jquery.fancybox-1.3.4.pack.js"></script>        
        <link rel="stylesheet" href="/estilos.css" type="text/css" /> 
        <link rel="stylesheet" href="/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />        
        <script type="text/javascript">
            function ok_ruta() {
        		$.fancybox({
                    'frameWidth': 300, 'frameHeight': 100,
                    'hideOnContentClick': false,
                    'titleShow':false,
                    'href':'/ajax/ok_ruta.php'
                });               
            }     
            $(document).ready(function() {
                ok_ruta();
            });               
        </script><? */ /* ?>
        <script type="text/javascript">
            function cierra()
            {
            parent.$.fn.fancybox.close();
            }        
        </script>
    </head>
    <body onload="cierra()">    
    </body>
    </html>
    <? */

    $_SESSION['ruta_creada']=1;
    
    ?>
    <script type="text/javascript">
        function cierra()
        {
        parent.jQuery.fancybox.close();
        }        
    </script>    
    <div style="position: relative; width:700px; height:500px; font-family:arial;">
        <div>Se ha creado la ruta correctamente!</div>
        <div style="margin-top: 10px;">[ <a href="javascript:cierra()">Cerrar ventana</a> ]</div>
        <div style="margin-top: 10px;">[ <a target="_blank" href="/ruta.php?id=<? echo $id_ruta; ?>">Ir a la ruta</a> ]</div>
    </div>    
    <?
    
    exit(0);
}

?>
<div style="position: relative; width:700px; height:500px;">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>

    <script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAND9i6OLPFQ0G1eHdrWaX9RRaYdnS34-agDuzrGJEEgsMrLzs_BSV9rTP71tLHW581_W0FNFYbbSkOg" type="text/javascript"></script>    
    <script type="text/javascript" src="/js/jquery.json-2.2.js"></script>
    
    <link rel="stylesheet" href="estilos.css" type="text/css" /> 
    
    <script type="text/javascript">
        //<![CDATA[
    
        $(document).ready(function() {
            initialize();
        });
    
        var map;
        var geocoder;
        var ruta = new Array();
        var num_puntos=0;
        var punto_anterior;
        var distancia_ruta=0;
        var global_puntos = new Array();
        var epolyline;
        var last_distance;
    
        var miIcono = new GIcon(G_DEFAULT_ICON);
        miIcono.image = "/images/point2-activo.png";
        var tamanoIcono = new GSize(10,10);
        miIcono.iconSize = tamanoIcono;
        miIcono.iconAnchor = new GPoint(5,5);
        var tamanoSombra = new GSize(0,0);
        miIcono.shadowSize = tamanoSombra;
    
        function initialize() {
                if( GBrowserIsCompatible() ) {
                        map = new GMap2(document.getElementById("map_canvas"));
                        geocoder = new GClientGeocoder();
                        map.setCenter(new GLatLng(39.47449798171261,-0.37593841552734375),13);
                        map.addControl(new GLargeMapControl());
                        map.addControl(new GMapTypeControl());
                        map.setMapType(G_HYBRID_MAP);
                        map.enableScrollWheelZoom();
                        GEvent.addListener(map,"click",
                            function (overlay,point) {
                                    if (point) {
                                            //añadir las coordenadas del punto al array
                                            var punto=new Array();
                                            punto[0]=point.x;
                                            punto[1]=point.y;
                                            ruta[num_puntos]=punto;
                                            eval('point'+num_puntos+'=point');
                                            if( num_puntos>0 ) document.getElementById('boton_deshacer').disabled=false;
                                            if( num_puntos>0 ) {
                                                eval('marker'+num_puntos+'=new GMarker(point,miIcono)');
                                                eval('map.addOverlay(marker'+num_puntos+');');
                                                if( (num_puntos-1)>0 ) {
                                                    eval('marker'+(num_puntos-1)+'.setImage("/images/maps/point2.png")');
                                                }
                                                epolyline = new GPolyline([punto_anterior,point],"#0000dd",6,0.4);
                                                map.addOverlay(epolyline);
                                                distancia_ruta=distancia_ruta+point.distanceFrom(punto_anterior);
                                                last_distance=point.distanceFrom(punto_anterior);
                                                var temp=(distancia_ruta/1000);
                                                document.getElementById('distance_route').value=temp.toFixed(2)+"km";
                                            } else {
                                                map.addOverlay(new GMarker(point));
                                            }
                                            punto_anterior=point;
                                            num_puntos++;
                                    }
                            }
                        );
                }
        }
    
        function deleteOverlay() {
            if( num_puntos>1 ) {
                eval('distancia_ruta=distancia_ruta-point'+(num_puntos-2)+'.distanceFrom(punto_anterior)');
                var temp=(distancia_ruta/1000);
                document.getElementById('distance_route').value=temp.toFixed(2)+"km";
                eval('map.removeOverlay(marker'+(num_puntos-1)+');');
                eval('punto_anterior=point'+(num_puntos-2));
                map.removeOverlay(epolyline);
                ruta.pop();
                document.getElementById('boton_deshacer').disabled=true;
                num_puntos--;
                if( (num_puntos-2)>0 ) {
                    eval('marker'+(num_puntos-1)+'.setImage("/images/point2-activo.png")');
                }
            }
        }
    
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
    
        function reset_ruta() {
            map.clearOverlays();
            ruta = new Array();
            num_puntos=0;
            distancia_ruta=0;
            document.getElementById('distance_route').value="";
        }
    
        function dump(arr,level) {
                //simil de print_r() de php para js
                var dumped_text = "";
                if(!level) level = 0;
                //The padding given at the beginning of the line.
                var level_padding = "";
                for(var j=0;j<level+1;j++) level_padding += "    ";
                if(typeof(arr) == 'object') { //Array/Hashes/Objects
                        for(var item in arr) {
                                var value = arr[item];
                                if(typeof(value) == 'object') { //If it is an array,
                                        dumped_text += level_padding + "'" + item + "' ...\n";
                                        dumped_text += dump(value,level+1);
                                } else {
                                        dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
                                }
                        }
                } else { //Stings/Chars/Numbers etc.
                        dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
                }
                return dumped_text;
        }
    
        function verArray() {
            //ver info para debug
            alert(dump(ruta));
        }
    
        function save_ruta() {
            if( num_puntos>1 ) {
                document.getElementById('ruta_valores').value=$.toJSON(ruta);
                document.getElementById('form_guardar_ruta').submit();
            } else {
                alert("No se puede guardar. ¡No has creado ninguna ruta!");
            }
        }
    
        //]]>
    </script>
    
    <div style="padding-bottom:10px; font-size:11px; font-family:georgia,arial;">
        Ciudad: <input type="text" value="Valencia" style="font-size:11px" onclick="this.value=''" id="ira" /> <input type="button" value="Centrar" style="font-size:11px" onclick="showAddress(document.getElementById('ira').value)" /> 
        <div style="position:absolute; top:0px; right:20px;"></div>
    </div>
    <div id="map_canvas" style="width:680px; height: 410px; border:3px solid #333;"></div>
    <div id="formulario" style="padding: 10px; font-size:11px; font-family:georgia,arial;">
    
        
        <input type="hidden" value="" name="q" />
        
        <div style="position: absolute; top:0px; right:20px;">
            <input type="button" value="Deshacer último punto" style="font-size:11px;" id="boton_deshacer" disabled="disabled" onclick="deleteOverlay()" />
        </div>
        
        <div style="position: absolute; bottom: 0px; left:0px;">
            <input type="text" style="font-size:14px; border: 1px solid #fff; font-weight:bold;" id="distance_route" value="" />
        </div>       
    
        <div style="position: absolute; bottom:10px; right:50px;">
            <a title="Resetear" href="javascript:reset_ruta();"><img src="/images/reset.png" /></a>
        </div>            
        <div style="position: absolute; bottom:10px; right:10px;">
            <a title="Guardar Ruta" href="javascript:save_ruta();"><img src="/images/save.png" /></a>
        </div>
        
        
        <form action="/ajax/crear_ruta.php" method="POST" id="form_guardar_ruta">
        
            <div style="position: absolute; bottom:20px; left:120px;">
                <select name="tipo_ruta_general" style="font-size: 10px; width:150px;">
                    <option value="1">Ruta a pie</option>
                    <option value="2">Ruta en bici</option>
                    <option value="3">Ruta en coche</option> 
                    <option value="4">Grandes itinerarios</option>
                </select>
            </div>
            
            <div style="position: absolute; bottom:0px; left:120px;">
                <select name="tipo_ruta_clas" style="font-size: 10px; width:150px;">
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
        
            <div style="position: absolute; bottom: 0px; right:100px; font-size:10px;">
                <table>
                <tr>
                <td style="font-size: 10px;">Breve descripción:</td>
                <td><textarea name="descripcion" style="font-size: 10px; width:200px; height:30px;"></textarea></td>
                </tr>
                </table>                
            </div>             
            <input type="hidden" id="ruta_valores" name="ruta_valores" value="" />
        </form>
        
    </div>    

</div>