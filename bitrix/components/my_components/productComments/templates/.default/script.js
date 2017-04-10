$(document).ready(function(){
	$( "#commSend" ).click(function() {
		var isValid = true;
	 	if($("input[name~='comm_name']").val() == false) {
	 		$( "#noname" ).html('Имя не указано!').animate({ fontSize: "16px" }, 500 ).animate({ fontSize: "14px" }, 500 );
	 		isValid = false;
	 	}else{
	 		$( "#noname" ).html(' ');
	 	}
	 	if($("input[name~='comm_email']").val() == false) {
	 		$( "#noemail" ).html('Email не указан!').animate({ fontSize: "16px" }, 500 ).animate({ fontSize: "14px" }, 500 );
	 		isValid = false;
	 	}else{
	 		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,6})$/;
	 		var val = $("input[name~='comm_email']").val();
			if(!reg.test($("input[name~='comm_email']").val())) {
				$( "#noemail" ).html('Email не корректный!').animate({ fontSize: "16px" }, 500 ).animate({ fontSize: "14px" }, 500 );
				isValid = false;
	 		}else{
	 			$( "#noemail" ).html(' ');
	 		}
	 	}
	 	if($("input:radio[name~='comm_rate']:checked").val() == undefined) {
	 		$( "#norate" ).html('Рейтинг не указан!').animate({ fontSize: "16px" }, 500 ).animate({ fontSize: "14px" }, 500 );
	 		isValid = false;
	 	}else{
	 		$( "#norate" ).html(' ');
	 	}
	 	if($("textarea[name~='comm_text']").val() == false) {
	 		$( "#nocomm" ).html('Текст комментария не указан!').animate({ fontSize: "16px" }, 500 ).animate({ fontSize: "14px" }, 500 );
	 		isValid = false;
	 	}else{
	 		$( "#nocomm" ).html(' ');
	 	}

	 	if(isValid){
	 		$.post(
	 			"/bitrix/components/my_components/productComments/ajax.php",
	 			{
		 			name_area:$("input[name='comm_name']").val(),
					email_area:$("input[name='comm_email']").val(),
					rate_area:$("input[name='comm_rate']:checked").val(),
					text_area:$("textarea[name='comm_text']").val(),
					product_area:$("#prodId").val(),
					extra_emails: $("#extraEmails").val()
	 			},
	 			function(data) {
	 				$("#comm_status").html(data).addClass("comm_status_ok");
	 				$("#commSend").css("display", "none");
	 			}
	 		);
	 	}
	});

	function changeStars(value, opacity){
		$("#stars label").each(function(i,elem){
			if(i <= value-1){
				$(this).removeClass("silver").addClass("gold");
			}else{
				$(this).removeClass("gold").addClass("silver");
			}
			$(this).css("opacity", opacity);
		});
	}
	$("#stars label").hover(function() {
		var value = $(this).find('input').val();
		changeStars(value,"1");
	});
	$("#stars").mouseleave(function() {
		var value = $(this).find('label input:checked').val();
		changeStars(value,"0.8");
	});

});
