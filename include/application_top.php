<?php

// iniciamos sesiones
session_start();

if( isset($_COOKIE['email']) && isset($_COOKIE['id_usuario']) ) { 
    $_SESSION['email']=$_COOKIE['email'];
    $_SESSION['id_usuario']=$_COOKIE['id_usuario'];  
};

// Configuración y conexión a bbdd
require('include/configure.php');
require('include/connect_db.php');

function consulta($sql) {
	$exec=mysql_query($sql);
	$row=mysql_fetch_object($exec);
	return $row;
}

function contar($sql) {
	$exec=mysql_query($sql);
	$numrow=mysql_num_rows($exec);
	return $numrow;
}

//funcion que quita acentos y caracteres raros
function formatearNombre($x) {
    $x=str_replace(" ","-",$x);
    $x=str_replace("ñ","n",$x);    
    $x=str_replace("Á","a",$x);    
    $x=str_replace("À","a",$x);
    $x=str_replace("É","e",$x);	    
    $x=str_replace("È","e",$x);    
    $x=str_replace("Í","i",$x);    
    $x=str_replace("Ì","i",$x);    
    $x=str_replace("Ó","o",$x);    
    $x=str_replace("Ò","o",$x);    
    $x=str_replace("Ú","u",$x);    
    $x=str_replace("Ù","u",$x);      
    $x=str_replace("á","a",$x);    
    $x=str_replace("à","a",$x);
    $x=str_replace("é","e",$x);	    
    $x=str_replace("è","e",$x);    
    $x=str_replace("í","i",$x);    
    $x=str_replace("ì","i",$x);    
    $x=str_replace("ó","o",$x);    
    $x=str_replace("ò","o",$x);    
    $x=str_replace("ú","u",$x);    
    $x=str_replace("ù","u",$x);   
    $x=str_replace("!","",$x);    
    $x=str_replace("¡","",$x);    
    $x=str_replace("?","",$x);    
    $x=str_replace("¿","",$x);        
	$x=strtolower($x); 
	return $x;
}

function actual_date() {
     //devuelve la fecha actual
     $week_days = array ("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
     $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
     $year_now = date ("Y");
     $month_now = date ("n");
     $day_now = date ("j");
     $week_day_now = date ("w");
     $date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;
     if( $_SESSION['lang_session']=="EN" ) {
        $date=mes(date("m"))." ".$day_now.", ".$year_now;
     }
     return $date;
}

function _date($x) {
     //devuelve la fecha actual
     $week_days = array ("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
     $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
     $year_now = substr($x,0,4);
     $month_now = trim(substr($x,5,2),"0");
     $day_now = substr($x,8,2);
     $fecha = strtotime($year_now.'-'.$month_now.'-'.$day_now);
     $week_day_now = date ("w",$fecha);
     $date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;
     return $date;
}

//GENTE ONLINE
	// Tiempo máximo de espera
	$time = 5 ;
	// Momento que entra en línea
	$date = time() ;
	// Recuperamos su IP
	$ip = $_SERVER['REMOTE_ADDR'];
	$navegador=$_SERVER['HTTP_USER_AGENT'];
	//echo $navegador;
	// Tiempo Limite de espera 
	$limite = $date-$time*60 ;
	// si se supera el tiempo limite (5 minutos) lo borramos
	mysql_query("delete from gente_online where date < $limite") ;
	// tomamos todos los usuarios en linea
	$resp = mysql_query("select * from gente_online where ip='$ip'") ;
	// Si son los mismo actualizamos la tabla gente_online
	if(mysql_num_rows($resp) != 0) {
	mysql_query("update gente_online set date='$date' where ip='$ip'") ;
	}
	// de lo contrario insertamos los nuevos
	else {
	mysql_query("insert into gente_online (ip,navegador) values ('$ip','".$navegador."')") ;
	}
//FIN DE GENTE ONLINE

?>