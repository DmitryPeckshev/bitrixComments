<?php

if (CModule::IncludeModule("blog")) {
	$arResult['PRODUCT_ID'] = $arParams['PRODUCT_ID'];
	
	$arOrder = Array(
        "ID" => "DESC",
        "DATE_CREATE" => "DESC"
    );
	$arFilter = Array(
			"BLOG_ID"=>'1',
			"POST_ID" =>'1',
			"AUTHOR_IP1"=>$arParams['PRODUCT_ID'],
			"PUBLISH_STATUS"=>'P'
		);
	$arSelectedFields = Array("ID", "BLOG_ID", "POST_ID", "PARENT_ID", "AUTHOR_ID", "AUTHOR_NAME", "AUTHOR_EMAIL", "AUTHOR_IP", "AUTHOR_IP1", "TITLE", "POST_TEXT", "DATE_CREATE", "PUBLISH_STATUS");

	$commNum = 0;
	$averageRating = 0;
	$dbComment = CBlogComment::GetList($arOrder, $arFilter, false, false, $arSelectedFields);
		$arResult["COMMENTS"] = array();
	while ($arComment = $dbComment->Fetch()) {	
		array_push($arResult["COMMENTS"], $arComment);
		$commNum++;
		$averageRating += $arComment["TITLE"];
	}
	$averageRating /= $commNum;
	$arResult["COMM_NUM"] = $commNum;
	$arResult["COMM_AVERAGE"] = $averageRating;
	if($arParams['IS_MAIL'] == 'N'){
		$arResult['MAIL_ADRESS'] = 'N';
	}else{
		$arResult['IS_MAIL'] = $arParams['IS_MAIL'];
		foreach ($arParams['MAIL_ADRESS'] as $oneMail) {
			if($oneMail != false){
				if($arResult['MAIL_ADRESS'] == false){
					$arResult['MAIL_ADRESS'] = $oneMail;
				}else{
					$arResult['MAIL_ADRESS'] .= ', '.$oneMail;
				}
			}
		}
	}
	$this->IncludeComponentTemplate(); 
}
?>