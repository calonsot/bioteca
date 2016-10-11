<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="keywords" content="xxx">
<meta name="viewport" content="width=device-width">
<title>Biblioteca digital | Conabio</title>

<link rel="stylesheet" type="text/css" href="css/desktop.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/tablet.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/smartphone.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/common.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/common_print.css" media="print">
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/janium.css">
<link rel="stylesheet" href="css/responsiveslides.css">
<link rel="stylesheet" href="css/demo.css">
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/responsiveslides.min.js"></script>
<script>
  $(document).ready(function(){
    $('#menu-mobile-head').click(function(){
      $('#menu-mobile a').slideToggle(400);
    });


    // Para saber si ya viene de un valor en los menus preestablecidos
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

	var busqueda = getUrlParameter('v');

    if (busqueda != "") $('#busqueda').val(busqueda);    
  });
  
    $(function () {
      $("#slider4").responsiveSlides({
        auto: true,
        pager: false,
        nav: true,
        speed: 500,
        namespace: "callbacks",
        before: function () {
          $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
          $('.events').append("<li>after event fired.</li>");
        }
      });
    });
</script> 

<script src="js/jquery.scrollTop.js"></script>
<script type="text/javascript">  
  $(function(){
             scrollTop({
                       color:"rgba(50, 61, 44, 0.7)",
                       top:400, 
                       time:500,
                       position:"bottom",
                       speed: 300 
                      });
             });
</script>

<script type="text/javascript">
  pagina = 2;
  busqueda = "<?php if (isset($_GET['v']))	echo $_GET['v'];	else	echo ''; ?>";
</script>
<script src="js/janium.js"></script>

</head>

<body>

<div id="wrapper">

  <div id="header">
  <div id="idioma"><a href="en/index.html">English</a> <strong>|</strong> Espa&ntilde;ol</div>
    <span class="conoce">
    <img src="images/f_bm.png" width="900" height="100" usemap="#Map" border="0">
    <map name="Map">
      <area shape="rect" coords="6,13,189,89" href="index.php">
      <area shape="rect" coords="479,6,883,93" href="http://www.biodiversidad.gob.mx">
    </map>
    </span>
    <span class="conoce2">
    <img src="images/f_bm2.png" width="401" height="102" usemap="#Map2" border="0">
    <map name="Map2">
      <area shape="rect" coords="3,3,395,51" href="http://www.biodiversidad.gob.mx">
      <area shape="rect" coords="108,69,291,103" href="index.php">
    </map>
    </span>
  </div><span class="cf"></span>
  <!-- header ENDS-->
  
  	
    <div id="menu-mobile-head">
      	<div class="hambutton">Menú</div>
    </div>
    <div id="menu-mobile">
      <a href="index.php?v=audios&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Audios</a>
       <a href="index.php?v=articulo&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Biodiversitas</a>
      <a href="index.php?v=carteles&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Carteles</a>
      <a href="index.php?v=exposicion&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Exposiciones</a>
      <a href="index.php?v=videos&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Videos</a>
      <a href="index.php?v=libros&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1" style="pointer-events: none; cursor: default; opacity: .4;">Libros</a>
      <a href="index.php?v=mapas&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1" style="pointer-events: none; cursor: default; opacity: .4;">Mapas</a>
      <a href="index.php?v=tesis&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1" style="pointer-events: none; cursor: default; opacity: .4;">Tesis</a>
      <span class="cf"></span>
    </div><!-- menu mobile ENDS-->
    <div id="menu">
      <a href="index.php?v=audios&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Audios</a>
       <a href="index.php?v=articulo&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Biodiversitas por artículo</a>
       <a href="index.php?v=edicionxnumero&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Biodiversitas por numero</a>
      <a href="index.php?v=carteles&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Carteles</a>
      <a href="index.php?v=exposicion&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Exposiciones</a>
      <a href="index.php?v=videos&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1">Videos</a>
      <a href="index.php?v=libros&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1" style="pointer-events: none; cursor: default; opacity: .4;">Libros</a>
      <a href="index.php?v=mapas&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1" style="pointer-events: none; cursor: default; opacity: .4;">Mapas</a>
      <a href="index.php?v=tesis&metodo=RegistroBib%2FBuscarPorPalabraClaveGeneral&a=terminos&inicio=1" style="pointer-events: none; cursor: default; opacity: .4;">Tesis</a>
    </div><!-- menu ENDS-->


    <div id="ima">
        <!-- Slideshow 4 -->
    <div class="callbacks_container">
      <ul class="rslides" id="slider4">
        <li>
          <img src="images/ima.jpg" alt="">
        </li>
        <li>
          <img src="images/ima_s2.jpg" alt="">
        </li>
        <li>
          <img src="images/ima_s3.jpg" alt="">
        </li>
      </ul>
    </div>
      <span class="cf"></span>
    </div><!-- ima ENDS-->

 
  <div id="content">
  <?php
  //error_reporting(E_ALL);
  //ini_set('display_errors', 1);
  ?>
	
<span class="tit">Catálogo general</span>
<form action="index.php" method="get">
  	<input type="text" name="v" id="busqueda"> 
    <input type="submit" value="Buscar" id="boton">
    <input type="hidden" name="metodo" value="RegistroBib/BuscarPorPalabraClaveGeneral">
    <input type="hidden" name="a" value="terminos">
    <input type="hidden" name="inicio" value="1">
    <br /><span><small><small>Ojo: Puedes realizar busquedas más especificas con el símbolo "+" entre dos palabras clave, ej. "carteles+peces" </small></small></span>
</form>

  <?php
  include 'resultados.php';
  ?>

  <div id='resultados_paginado'></div>

  <div class="animation_image" style="display:none" align="center">
    <img src="images/loading.gif">
  </div>      
  
  </div>
  
      <div id="footer">
        <center><img src="images/logos.png">
        </center>
      </div><!-- footer ENDS-->

</div><!-- menu ENDS-->

</body>
</html>