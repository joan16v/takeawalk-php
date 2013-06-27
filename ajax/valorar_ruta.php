<?php

chdir("..");
include('include/application_top.php');

if( !isset($_POST['id']) ) exit(0);
if( !isset($_POST['voto']) ) exit(0);
if( !isset($_SESSION['id_usuario']) ) exit(0);

$sql="select * from valoraciones_rutas where id_usuario='".$_SESSION['id_usuario']."' and id_ruta='".$_POST['id']."'";
if( mysql_num_rows(mysql_query($sql))>0 ) {
    mysql_query("update valoraciones_rutas set valoracion='".$_POST['voto']."' where id_usuario='".$_SESSION['id_usuario']."' and id_ruta='".$_POST['id']."'");
} else {
    mysql_query("insert into valoraciones_rutas (id_usuario,id_ruta,valoracion) values ('".$_SESSION['id_usuario']."','".$_POST['id']."','".$_POST['voto']."')");
}

?>