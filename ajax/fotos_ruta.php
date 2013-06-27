<? 

chdir("..");
include('include/application_top.php');

$id=$_POST['id'];

//RUTA
$sqlruta="select * from rutas where id='".$id."'";
$exruta=mysql_query($sqlruta);
$row2=mysql_fetch_object($exruta);
$xml=simplexml_load_string($row2->ruta);
$i=0; $n=0; $intervaloCaptura=1;
$geo="";
$start_lat=""; $start_lon=""; $end_lat=""; $end_lon="";
$array_fotos=array();
foreach ($xml->trk->trkseg->trkpt as $posicion) {
	if($i==0) {
		$attr=$posicion->attributes();
        if( $attr['lat']!=-1 && $attr['lon']!=-1 && $attr['lat']!=999 && $attr['lon']!=999 && $attr['lat']!=0 && $attr['lon']!=0 ) {
            $geo.="new GLatLng(".$attr['lat'].", ".$attr['lon']."),";
            if( $start_lat=="" ) $start_lat=$attr['lat'];
            if( $start_lon=="" ) $start_lon=$attr['lon'];
            $end_lat=$attr['lat'];
            $end_lon=$attr['lon'];      
            
            $array_fotos[]=$attr['lat'].",".$attr['lon'];
            
                                  
        }
		$n++;
	}
	$i++;
	if($i==$intervaloCaptura){ $i=0; }
}
$geo=substr($geo,0,-1);   

$pano_array=array();

//mostrar imagenes de google street view
for( $i=0;$i<count($array_fotos);$i++ ) {
    $xml = simplexml_load_file("http://maps.google.com/cbk?output=xml&ll=".$array_fotos[$i]);
    $pano_id=$xml->annotation_properties->link["pano_id"];
    $pano_id=(string)$pano_id;
    if( $pano_id!="" ) {
        if( !in_array($pano_id,$pano_array) ) {
            $pano_array[]=$pano_id;
            ?><div style="position: relative; float:left; margin-left:8px; margin-top:8px;">
                    <a title="Ampliar" class="lightbox" href="<? echo "http://cbk0.google.com/cbk?output=tile&panoid=".$pano_id."&zoom=2&x=0&y=0"; ?>"><img width="75" height="75" style="border: 1px solid #999;" src="<? echo "http://cbk0.google.com/cbk?output=tile&panoid=".$pano_id."&zoom=2&x=0&y=0"; ?>" /></a>
              </div><?            
        }                    
    }
}

//print_r($pano_array);

?>
<script type="text/javascript"> $(function() { $('a.lightbox').lightBox(); }); </script>