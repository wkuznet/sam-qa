jQuery(function($){

	$('#sam_qa_form').validate({
		submitHandler: function(form){
			var params = $(form).serialize();
			$.ajax({
				url: "/wp-admin/admin-ajax.php",
				type: "POST",
				data: "action=send_qaform&" + params,
				success: function(data){
					$("#qa-ajax-result").html( data );
				},
				timeout: 3000
			});
		},
		messages:{
			sam_qa_name: "Укажите ваше имя",
			sam_qa_email: "Укажите e-mail",
            sam_qa_text: "Нет текста"
		},
		errorElement: "div",
		errorClass: "text-danger"
	});
});