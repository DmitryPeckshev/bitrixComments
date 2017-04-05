
window.onload = function() {
	var isValid = false;
	var submitBtn = document.getElementById('commSend');
	submitBtn.addEventListener('click', formValidate, false);
	showAverage();
};		

function formValidate() { 
	var commForm = document.getElementById("commForm");
	var formElems = commForm.elements;
	var nameAlert = document.getElementById("noname");
	var emailAlert = document.getElementById("noemail");
	var rateAlert = document.getElementById("norate");
	var commAlert = document.getElementById("nocomm");
	isValid = true;
	if (!formElems.comm_name.value) {
        nameAlert.innerHTML = 'Имя не указано!'; isValid = false;
    }else{
		nameAlert.innerHTML = ' ';
	}
	if (!formElems.comm_email.value) {
		emailAlert.innerHTML = 'Email не указан!'; isValid = false;
    }else{
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,6})$/;
		if(!reg.test(formElems.comm_email.value)) {
			emailAlert.innerHTML = 'Указан некорректный Email'; isValid = false;
		}else{
			emailAlert.innerHTML = ' ';
		}	
	}
	if (!formElems.comm_rate.value) {
		rateAlert.innerHTML = 'Рейтинг не указан!'; isValid = false;
    }else{
		rateAlert.innerHTML = ' ';
	}
	if (!formElems.comm_text.value) {
		commAlert.innerHTML = 'Текст комментария не указан!'; isValid = false;
    }else{
		commAlert.innerHTML = ' ';
	}
	var prodId = document.getElementById("prodId").value;
			
	if(isValid) {
		ajax({
		url:"/bitrix/components/my_components/productComments/ajax.php",
		statbox:"comm_status",
		method:"POST",
		data:{
			name_area:document.getElementById("commForm").elements.comm_name.value,
			email_area:document.getElementById("commForm").elements.comm_email.value,
			rate_area:document.getElementById("commForm").elements.comm_rate.value,
			text_area:document.getElementById("commForm").elements.comm_text.value,
			product_area:prodId,
			extra_emails: document.getElementById("extraEmails").value
		},
		success:function(data){document.getElementById("comm_status").innerHTML = data;}
		});
	}
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
		send=send+"ajax=true";
	}
	req.open(method, param.url, true);
	if(param.statbox)document.getElementById(param.statbox).innerHTML = 'Идет отправка...';
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send(send);
	req.onreadystatechange = function() {
		if (req.readyState == 4 && req.status == 200) { 
			if(param.success)param.success(req.responseText);
			var commSendSuccess = document.getElementById("commSendSuccess");
			if(commSendSuccess) {
				document.getElementById("commSend").style.display = "none";
				document.getElementById("comm_status").className = "comm_status_ok";
			}
		}
	}
}


function starsAnimation(onstar) {
	for(var i=1;i<=5;i++){
		if(i<=onstar){
			document.getElementById("star"+i).style.background = "url(/bitrix/components/my_components/productComments/images/star.png) round";
		}else{
			document.getElementById("star"+i).style.background = "url(/bitrix/components/my_components/productComments/images/star_silver.png) round";
		}
		document.getElementById("star"+i).style.opacity = "1";
	}
}
function starsReset(onstar){
	for(var i=1;i<=5;i++){
		if(document.getElementById("rad"+i).checked){
			for(var j=1;j<=5;j++){
				if(j<=i){
					document.getElementById("star"+j).style.background = "url(/bitrix/components/my_components/productComments/images/star.png) round";
					document.getElementById("star"+j).style.opacity = "0.8";
				}else{
					document.getElementById("star"+j).style.background = "url(/bitrix/components/my_components/productComments/images/star_silver.png) round";
					document.getElementById("star"+j).style.opacity = "0.8";
				}
			}
		}else{
			document.getElementById("star"+i).style.background = "url(/bitrix/components/my_components/productComments/images/star_silver.png) round";
			document.getElementById("star"+i).style.opacity = "0.8";
		}
	}
}

function showAverage() {
	var ave = document.getElementById('average');
	var aveNum = ave.getAttribute('data-ave');
	ave.style.width = 24*aveNum+1+'px';
}