<?php

chdir("..");
include('include/application_top.php');

if( !isset($_GET['id']) ) exit(0);

$sql="select * from rutas where id='".$_GET['id']."'";
$ex=mysql_query($sql);
$row=mysql_fetch_object($ex);
$fichero=$row->ruta;
$fecha=str_replace(" ", "_", str_replace(":", "-", $row->fecha_alta));

header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename=".$fecha.".gpx");
echo $fichero;

?>