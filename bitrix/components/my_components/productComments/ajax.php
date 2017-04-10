<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (CModule::IncludeModule("blog")) {
	
	$UserIP = CBlogUser::GetUserIP();
	$arFields = array(
		"TITLE" => $_POST["rate_area"],
		"POST_TEXT" => $_POST["text_area"],
		"AUTHOR_NAME" => $_POST["name_area"],
		"AUTHOR_EMAIL" => $_POST["email_area"],
		"BLOG_ID" => 1,
		"POST_ID" => 1, 
		"AUTHOR_ID" => $USER->GetID(),
		"DATE_CREATE" => ConvertTimeStamp(false, "FULL"), 
		"AUTHOR_IP" => $UserIP[0],
		"AUTHOR_IP1" => $_POST['product_area'],
		"PUBLISH_STATUS" => "K"
	);

	$newID = CBlogComment::Add($arFields);
	if(IntVal($newID)>0) {
		echo "Ваш комментарий сохранен!<br/>Он появится на сайте после проверки администратором.";
		echo "<input type='hidden' id='commSendSuccess'>";
	}else{
		if ($ex = $APPLICATION->GetException())
			echo $ex->GetString();
	}

if($_POST['extra_emails'] != 'N'){
	if(CModule::IncludeModule("iblock")){ 
		$catQuery = CIBlock::GetList(Array(), Array('TYPE'=>'catalog', 'SITE_ID'=>SITE_ID, 'ACTIVE'=>'Y'), false);
		$catalogs = array();
		while($oneCat = $catQuery->Fetch()) {array_push($catalogs, $oneCat['ID']);}
		$products = CIBlockElement::GetList(
			Array(),
			Array("IBLOCK_ID" => $catalogs,"ID" => $_POST['product_area']),
			false,
			false,
			Array("ID","NAME")
		);
		while($oneProduct = $products->GetNextElement()) {
			$prodFields = $oneProduct->GetFields();
			$productName = $prodFields["NAME"];
		}
	}
	$getMailEventType = CEventType::GetList(array("TYPE_ID" => "NEW_PRODUCT_COMMENT"));
	while ($mailEvent = $getMailEventType->Fetch()) {
	    $mailEventTypeId = $mailEvent['ID'];
	}
	if(!$mailEventTypeId){
		$mailEventType = new CEventType;
	    $mailEventType->Add(array(
	        "LID"           => LANGUAGE_ID,
	        "EVENT_NAME"    => "NEW_PRODUCT_COMMENT",
	        "NAME"          => "Новый комментарий",
	        "DESCRIPTION"   => "Новый комментарий"
	    ));
	}
	$filterAdmin = Array(
	    "ACTIVE" => "Y",
	    "GROUPS_ID" => "1"
	);
	$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filterAdmin);
	$to = array();
	$to = explode(" ", $_POST['extra_emails']);
	while($oneAdmin = $rsUsers->Fetch()) {
		array_push($to, $oneAdmin['EMAIL']);
	}
	
	$eventFilter = Array(
	    "EVENT_NAME"    => "NEW_PRODUCT_COMMENT",
	    "SITE_ID"       => SITE_ID,
	    "ACTIVE"        => "Y",
	    "BODY_TYPE"     => "html",
	    );
	$arEventFields = array(
		"USER_NAME" => $_POST["name_area"],
		"PRODUCT" => $productName,
		"USER_EMAIL" => $_POST["email_area"],
		"RATING" => $_POST["rate_area"],
		"COMMENT" => $_POST["text_area"],
	);
	foreach ($to as $oneEmail) {	
		$eventFilter["TO"] = $oneEmail;
		$rsMess = CEventMessage::GetList($by="site_id", $order="desc", $eventFilter);
		while ($rsMessage = $rsMess->Fetch()) {
			$mailTemplateId = $rsMessage['ID'];
		}
		if(!$mailTemplateId){
			$message = <<<EOT
				<b>Пользователь #USER_NAME# прокомментировал товар #PRODUCT#</b>
				<p>Email: #USER_EMAIL#</p>
				<p>Рейтинг: #RATING# из 5</p>
				<p>Текст комментария:</p>
				<p>#COMMENT#</p>
EOT;
			$newEventarr = array(
				"ACTIVE" => "Y",
				"EVENT_NAME" => "NEW_PRODUCT_COMMENT",
				"LID" => SITE_ID,
				"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
				"BCC" => "#BCC#",
				"SUBJECT" => "Новый отзыв",
				"BODY_TYPE" => "html",
				"MESSAGE" => $message,
				"EMAIL_TO" => $oneEmail
			);
			$emess = new CEventMessage;
			$emess->Add($newEventarr);
			CEvent::Send("NEW_PRODUCT_COMMENT", SITE_ID, $arEventFields, "N", $emess);
		}else{
			CEvent::Send("NEW_PRODUCT_COMMENT", SITE_ID, $arEventFields, "N", $mailTemplateId);
		}
	}
}

}
?>