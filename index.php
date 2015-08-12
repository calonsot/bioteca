<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        
		<link rel="stylesheet" type="text/css" href="css/janium.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		
		<script type="text/javascript">
			// Para el scrolling
			pagina = 2;
			busqueda = "<?php if (isset($_POST['v']))	echo $_POST['v'];	else	echo ''; ?>";
		</script>
		
		<script src="js/janium.js"></script>
	</head>
<body>

<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

?>

<form action="index.php" method="post">
	Busqueda general: <input type="text" name="v"><br>
	<input type="submit" value="Buscar">
	<input type="hidden" name="metodo" value="RegistroBib/BuscarPorPalabraClaveGeneral">
	<input type="hidden" name="a" value="terminos">
</form>

<?php
include 'resultados.php';
?>


<div id='resultados_paginado'></div>

<div class="animation_image" style="display:none" align="center">
<img src="images/loading.gif">
</div>

	
</body>
</html>