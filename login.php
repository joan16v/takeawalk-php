<?php

// -----------------------------------------
// ----- take a walk -----------------------
// -----------------------------------------

include('include/application_top.php');

if( !isset($_POST['email']) ) exit(0);
if( !isset($_POST['password']) ) exit(0);

$email=$_POST['email'];
$password=md5($_POST['password']);

$_SESSION['error']=0;

//echo $email." - ".$password;
//echo "select * from usuarios where email='".$email."' and pass='".$password."'";

if( mysql_num_rows(mysql_query("select * from usuarios where email='".$email."' and pass='".$password."'"))>0 ) {    
    $_SESSION['usuario']=$email;
    $id_usuario=mysql_result(mysql_query("select id from usuarios where email='".$email."'"),0,"id");
    $_SESSION['id_usuario']=$id_usuario;    
    
    if( $_POST['recordar']=="on" ) { 
	  setcookie("email",$email,time()+100*24*60*60); //cookies de 100 dias
      setcookie("id_usuario",$id_usuario,time()+100*24*60*60); //cookies de 100 dias	  
	}
    
} else {
    $_SESSION['error']=1;
}

header("Location: index.php");

?>