function changeComment(comment, action){	
	$.post(
		"/bitrix/components/my_components/commentsModeration/ajax.php",
		{
			id_area:comment,
			action_area:action
		},
		function(data) {
			$("#status"+comment).html(data);
			$("#commSend").css("display", "none");
			if($("#SendSuccess"+comment) && action == 'del') {
				$("#comm"+comment).css("background", "rgba(250,0,0,0.2)");
				$("#buttons"+comment).css("display", 'none');
			}
			if($("#SendSuccess"+comment) && action == 'pub') {
				$("#comm"+comment).css("background", "rgba(0,200,0,0.3)");
				$("#buttons"+comment).css("display", "none");
			}
		}
	);
}