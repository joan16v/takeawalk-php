<? 

session_start();

session_destroy();
setcookie("email","",time()-3600);
setcookie("id_usuario","",time()-3600);

header("Location: index.php");

?>