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
	
	
	
	// Parte del scrolling
	
	$(document).ready(function() {
	    var loading  = false; //to prevents multipal ajax loads
	    var total_groups =  //total record group(s)
	    
	    $(window).scroll(function() { //detect page scroll
	        
	        if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
	        {
	                loading = true; //prevent further ajax loading
	                $('.animation_image').show(); //show loading image
	                
	                //load data from the server using a HTTP POST request
	                $.get('resultados.php',{'metodo': 'RegistroBib/BuscarPorPalabraClaveGeneral', 'a': 'terminos', 'v': busqueda, 'numero_de_pagina': pagina.toString()}, function(data){
	                      
	                	if (data != "No existen datos para esta consulta")
	                	{	
	                		$("#resultados_paginado").append(data); //append received data into the element
		                    pagina++; //loaded group increment
	                	}	
	                	
	                	//hide loading image
	                    $('.animation_image').hide(); //hide loading image once data is received
	                    loading = false; 
	                    	                
	                }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
	                    
	                    alert(thrownError); //alert with HTTP error
	                    $('.animation_image').hide(); //hide loading image
	                    loading = false;
	                
	                });
	        }
	    });
	});
});