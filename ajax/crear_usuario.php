<? 

chdir("..");
include('include/application_top.php');

if( isset( $_POST['email_new'] ) ) {
    
    $tipo=$_POST['tipo'];
    
    mysql_query("insert into usuarios (email,pass,tipo_usuario) values ('".$_POST['email_new']."','".md5($_POST['pass_new'])."','".$tipo."')");    
    
    $_SESSION['usuario']=$_POST['email_new'];
    
    $id_usuario=mysql_result(mysql_query("select id from usuarios where email='".$_POST['email_new']."'"),0,"id");
    $_SESSION['id_usuario']=$id_usuario;    
    
    header("Location: /index.php");
    exit(0);
}

?>
<div style="position: relative; width:400px; height:320px;">

    <script type="text/javascript">
        function valEmail(valor){
            re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/
            if(!re.exec(valor))    {
                return false;
            }else{
                return true;
            }
        }
        function check_user() {
            if( valEmail( document.getElementById('email_new').value ) ) {
                var string_pass=document.getElementById('pass_new').value;
                var string_pass2=document.getElementById('pass2_new').value;
                if( string_pass.length>2 ) {
                    if( string_pass==string_pass2 ) {
                        document.getElementById('formulario_usuario').submit();
                    } else {
                        alert("Las contraseñas no coinciden.");    
                    }
                } else {
                    alert("La contraseña tiene que ser por lo menos de 3 carácteres.");    
                }
            } else {
                alert("La dirección de correo es incorrecta.");
            }
        }
    </script>

    <h1>Registrarse en takeawalk.es</h1>
    <p>Introduce tu dirección de correo electrónico y una contraseña para crear tu cuenta en takeawalk.es y empezar a crear rutas.</p>

    <form action="/ajax/crear_usuario.php" method="post" id="formulario_usuario">
        <div style="margin-top: 20px;">correo electrónico</div>
        <div><input type="text" name="email_new" id="email_new" /></div>
        <div>contraseña</div>
        <div><input type="password" name="pass_new" id="pass_new" /></div>
        <div>confirmar contraseña</div>
        <div><input type="password" name="pass2_new" id="pass2_new" /></div>
        
        <div style="margin-top: 20px;">Tipo de cuenta:<br /><input type="radio" id="tipo_personal" name="tipo" checked="checked" value="0" />Personal <input type="radio" id="tipo_comercial" name="tipo" value="1" />Comercial</div>        
        
        <div style="margin-top: 20px;"><input type="button" onclick="check_user()" value="Crear usuario" /></div>    
    </form>        
</div>