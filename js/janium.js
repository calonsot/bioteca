$(document).ready(function() {
	$(document).on('click', "[id^='ficha_']", function(){
	   var numero_ficha = $(this).attr('id').substring(6);
	   
	   jQuery.ajax({
           success: function(html){
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
	   return false;
   	});
});