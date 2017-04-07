<?
use \Bitrix\Main\Loader as Loader;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!Loader::includeModule("blog") || !Loader::includeModule("iblock") || !Loader::includeModule("catalog"))
{
	ShowError(GetMessage("SBP_NEED_REQUIRED_MODULES"));
	die();
}

$arComponentParameters = array(  
	"PARAMETERS" => array(
		"SET_TITLE" => array(),
	    "CACHE_TIME" => array()
    )
);

?>
