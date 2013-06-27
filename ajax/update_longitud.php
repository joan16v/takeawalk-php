<?php

chdir("..");
include('include/application_top.php');

if( !isset($_POST['id']) ) exit(0);
if( !isset($_POST['long']) ) exit(0);

$id=$_POST['id'];
$long=$_POST['long'];

$long_bbdd=mysql_result(mysql_query("select longitud from rutas where id='".$id."'"),0,"longitud");

if( $long_bbdd==0 ) {
    mysql_query("update rutas set longitud='".$long."' where id='".$id."'");                     
}

?>