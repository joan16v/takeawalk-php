<link rel="shortcut icon" href="tw.gif" />	

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAND9i6OLPFQ0G1eHdrWaX9RRaYdnS34-agDuzrGJEEgsMrLzs_BSV9rTP71tLHW581_W0FNFYbbSkOg" type="text/javascript"></script>    

<link rel="stylesheet" href="estilos.css" type="text/css" /> 
<link rel="stylesheet" href="fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<script type="text/javascript">
    function registrarse() {
		$.fancybox({
            'frameWidth': 400, 'frameHeight': 300,
            'hideOnContentClick': false,
            'titleShow':false,
            'href':'ajax/crear_usuario.php'
        });        
    }
    function crear_ruta() {
		$.fancybox({
            'frameWidth': 700, 'frameHeight': 500,
            'hideOnContentClick': false,
            'titleShow':false,
            'href':'ajax/crear_ruta.php'
        });               
    }
    $(document).ready(function(){
        $(".extLink").fancybox({
             'width' : 730,
             'height' : 530,
             'autoScale' : false,
             'transitionIn' : 'none',
             'transitionOut' : 'none',
             'type' : 'iframe'
         });
        $(".extLink2").fancybox({
             'width' : 430,
             'height' : 330,
             'autoScale' : false,
             'transitionIn' : 'none',
             'transitionOut' : 'none',
             'type' : 'iframe'
         });         
    });        
</script>