		
function changeComment(comment, action){
	ajax({
		url:"/bitrix/components/my_components/commentsModeration/ajax.php",
		statbox:"status"+comment,
		method:"POST",
		data:{
			id_area:comment,
			action_area:action
		},
		success:function(data){document.getElementById("status"+comment).innerHTML = data;}
	});
}

function XmlHttp() {
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e){
		try {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");} 
		catch (E) {xmlhttp = false;}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	  return xmlhttp;
}

function ajax(param) {
	if (window.XMLHttpRequest) req = new XmlHttp();
	method=(!param.method ? "POST" : param.method.toUpperCase());
	if(method == "GET") {
		send=null;
		param.url=param.url+"&ajax=true";
	}else{
		send="";
		for (var i in param.data) send+= i+"="+param.data[i]+"&";
		//send=send+"ajax=true";
	}
	req.open(method, param.url, true);
	if(param.statbox)document.getElementById("status"+param.data.id_area).innerHTML = 'Загрузка...';
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send(send);
	req.onreadystatechange = function() {
		if (req.readyState == 4 && req.status == 200) { 
			if(param.success)param.success(req.responseText);
			var commSendSuccess = document.getElementById("SendSuccess"+param.data.id_area);
			if(commSendSuccess && param.data.action_area == 'del') {
				var commCont = document.getElementById("comm"+param.data.id_area).style.background = "rgba(250,0,0,0.2)";
				document.getElementById("buttons"+param.data.id_area).style.display = 'none';
			}
			if(commSendSuccess && param.data.action_area == 'pub') {
				var commCont = document.getElementById("comm"+param.data.id_area).style.background = "rgba(0,200,0,0.3)";
				document.getElementById("buttons"+param.data.id_area).style.display = 'none';
			}
		}
	}
}


