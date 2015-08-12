$(document).ready(function() {
	$(document).on('click', "[id^='ficha_']", function(){
	   var numero_ficha = $(this).attr('id').substring(6);
	   var detalle_esta_vacio =  $('#detalle_' + numero_ficha).is(':empty');

	   if (detalle_esta_vacio)
	   {
		   jQuery.ajax({
	           success: function(html){
	        	  $('#tabla_' + numero_ficha).hide(); 
	              $('#detalle_' + numero_ficha).html(html);
	           },
	           fail: function(){
	               $('#detalle').html('Hubo un error al cargar los datos, por favor intentalo de nuevo.');
	           },
	           type: 'POST',
	           url: 'detalle.php',
	           cache: true,
	           data: {ficha: numero_ficha}
	       });
	   } else {  // Ya habia hecho la peticion con ajax
		   $('#tabla_' + numero_ficha).hide(); 
           $('#detalle_' + numero_ficha).show();
	   }
	   
	   return false;
   	});
	
	$(document).on('click', "[id^='ocultar_ficha_']", function(){
		   var numero_ficha = $(this).attr('id').substring(14);
		   $('#tabla_' + numero_ficha).show();
           $('#detalle_' + numero_ficha).hide();
		   
		   return false;
	   	});
});