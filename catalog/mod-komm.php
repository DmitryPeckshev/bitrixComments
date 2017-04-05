<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Модерирование комментариев");
?>

<?php 
	$APPLICATION->IncludeComponent(
	"my_components:commentsModeration", 
	".default", 
	array(
		"IS_MAIL" => "Y",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "N",
		"DETAIL_URL" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"SET_TITLE" => "Y",
		"IS_ACTIVE" => "Y",
		"PAGINATION" => "50"
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>