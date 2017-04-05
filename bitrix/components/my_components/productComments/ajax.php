<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

header("Content-type: text/plain; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
sleep(1);

$decodedPost = array();	
while(list($key, $val) = each($_POST)) {
	//$decodedPost[$key] = iconv("UTF-8","CP1251",$val);
	$decodedPost[$key] = $val;
}

if (CModule::IncludeModule("blog")) {
	
	$UserIP = CBlogUser::GetUserIP();
	$arFields = array(
		"TITLE" => $decodedPost["rate_area"],
		"POST_TEXT" => $decodedPost["text_area"],
		"AUTHOR_NAME" => $decodedPost["name_area"],
		"AUTHOR_EMAIL" => $decodedPost["email_area"],
		"BLOG_ID" => 1,
		"POST_ID" => 1, 
		"AUTHOR_ID" => $USER->GetID(),
		"DATE_CREATE" => ConvertTimeStamp(false, "FULL"), 
		"AUTHOR_IP" => $UserIP[0],
		"AUTHOR_IP1" => $decodedPost['product_area'],
		"PUBLISH_STATUS" => "K"
	);

	$newID = CBlogComment::Add($arFields);
	if(IntVal($newID)>0) {
		echo "Ваш комментарий сохранен!<br>Он появится на сайте только после проверки администратором.";
		echo "<input type='hidden' id='commSendSuccess'>";
	}else{
		if ($ex = $APPLICATION->GetException())
			echo $ex->GetString();
	}

	if($_POST['extra_emails'] == 'N'){
		if(CModule::IncludeModule("iblock")){ 
			$catQuery = CIBlock::GetList(Array(), Array('TYPE'=>'catalog', 'SITE_ID'=>SITE_ID, 'ACTIVE'=>'Y'), false);
			$catalogs = array();
			while($oneCat = $catQuery->Fetch()) {array_push($catalogs, $oneCat['ID']);}
			$products = CIBlockElement::GetList(
				Array(),
				Array("IBLOCK_ID" => $catalogs,"ID" => $decodedPost['product_area']),
				false,
				false,
				Array("ID","NAME")
			);
			while($oneProduct = $products->GetNextElement()) {
				$prodFields = $oneProduct->GetFields();
				$productName = $prodFields["NAME"];
			}
		}
		$filterAdmin = Array(
		    "ACTIVE" => "Y",
		    "GROUPS_ID" => "1"
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filterAdmin);
		$to = $_POST['extra_emails'];
		while($oneAdmin = $rsUsers->Fetch()) {
			if($to == false){
				$to = $oneAdmin['EMAIL'];
			}else{
				$to .= ', '.$oneAdmin['EMAIL'];
			}
		}
		$message = <<<EOT
			<b>Пользователь {$decodedPost['name_area']} прокомментировал товар {$productName}</b>
			<p>Email: {$decodedPost["email_area"]}</p>
			<p>Рейтинг: {$decodedPost['rate_area']} из 5</p>
			<p>Текст комментария:</p>
			<p>{$decodedPost['text_area']}</p>
EOT;
		mail($to, 'Новый комментарий', $message);
	}
}
?>