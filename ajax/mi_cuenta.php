<? 

chdir("..");
include('include/application_top.php');

if( !isset($_SESSION['usuario']) ) {
    die("Ha caducado la sesión.");
}
if( !isset($_SESSION['id_usuario']) ) {
    die("Ha caducado la sesión.");
}

if( isset($_POST['nombre']) ) {
    $nombre=addslashes(strip_tags(utf8_decode($_POST['nombre'])));
    mysql_query("update usuarios set nombre='".$nombre."' where id='".$_SESSION['id_usuario']."'");
    exit(0);
}

$sql="select * from usuarios where id='".$_SESSION['id_usuario']."'";
$ex=mysql_query($sql);
$row=mysql_fetch_object($ex);

$tipo_usuario=$row->tipo_usuario;
$nombre=stripslashes($row->nombre);

//echo "tipo_usuario:".$tipo_usuario;
//print_r($row);

?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript">
    function guardar_form() {
        var ajax_url='mi_cuenta.php';
        $.ajax({
                url:ajax_url,
                type:'POST',
                data: 'nombre='+document.getElementById('nombre_completo').value,
                beforeSend: function(){  },
                success:function() {
                    document.getElementById('boton_guardar').disabled=true;
                }
        });        
    }
</script>
<div style="position: relative; width:410px; height:305px; background:#fff; text-align:left; font-size:11px; font-family:Georgia,Arial; color:#666;">
    <div><b style="font-size: 16px;"><? echo $_SESSION['usuario']; ?></b></div>
    <div style="margin-top: 10px;">Fecha de alta: <i style="font-size: 13px;"><? echo $row->fecha_alta; ?></i></div>    
    <div style="margin-top: 20px;">Tipo de cuenta:<br /><input type="radio" id="tipo_personal" disabled="disabled" <? if( $tipo_usuario==0 ) echo "checked=\"checked\""; ?> />Personal <input type="radio" id="tipo_comercial" disabled="disabled" <? if( $tipo_usuario==1 ) echo "checked=\"checked\""; ?> />Comercial</div>
    <div style="margin-top: 20px;">Nombre:<br/><input type="text" id="nombre_completo" value="<? echo $nombre; ?>" style="width: 350px;" /></div>
    <div style="position:absolute; bottom: 0px; right:0px;">
        <input type="button" value="Guardar" id="boton_guardar" onclick="guardar_form()" />        
    </div>
</div>