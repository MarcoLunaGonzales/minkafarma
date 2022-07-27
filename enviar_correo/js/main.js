$(function() {

	'use strict';

	// Form

	var contactForm = function() {

		if ($('#contactForm').length > 0 ) {
			$( "#contactForm" ).validate( {
				rules: {
					name: {
						required: true,
						email: true
					},
					message: {
						required: true,
						minlength: 5
					}
				},
				messages: {
					name: "Por favor ingrese el destinatario",
					message: "Por favor ingrese su mensaje"
				},
				/* submit via ajax */
				submitHandler: function(form) {	

				  if($("#adjuntos_texto_csv").val()==""||$("#adjuntos_texto").val()==""){
        				alert("Todavía se están cargando los archivos adjuntos espere...");
      			  }else{
      			  	var $submit = $('.submitting'),
						waitText = 'Enviando correo';

					$.ajax({   	
				      type: "POST",
				      url: "php/send-email.php",
				      data: $(form).serialize(),

				      beforeSend: function() { 
				      	$submit.css('display', 'block').text(waitText);
				      },
				      success: function(msg) {
		               if (msg == 'OK') {
		               	$('#form-message-warning').hide();
				            setTimeout(function(){
		               		$('#contactForm').fadeOut();
		               	}, 1000);
				            setTimeout(function(){
				               $('#form-message-success').fadeIn();   
		               	}, 1400);
			               
			            } else {
			               $('#form-message-warning').html(msg);
				            $('#form-message-warning').fadeIn();
				            $submit.css('display', 'none');
			            }
				      },
				      error: function() {
				      	$('#form-message-warning').html("Ocurrio algun error vuelva a intentarlo");
				         $('#form-message-warning').fadeIn();
				         $submit.css('display', 'none');
				      }
			      });  
      			  } 
		  		}
				
			} );
		}
	};
	contactForm();

});