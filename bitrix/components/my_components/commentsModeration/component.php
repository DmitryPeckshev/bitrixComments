<?php
if (CModule::IncludeModule("blog")) {	
	if(!$_GET["show"] || $_GET["show"] == 'K') {
		$statusFilter = 'K';
	}
	if($_GET["show"] == 'P') {
		$statusFilter = 'P';
	}

	$arOrder = Array(
        "ID" => "DESC",
        "DATE_CREATE" => "DESC"
    );
	$arFilter = Array(
			"BLOG_ID"=>'1',
			"POST_ID" =>'1',
			"PUBLISH_STATUS" => $statusFilter
		);
	$arSelectedFields = Array("ID", "AUTHOR_ID", "AUTHOR_NAME", "AUTHOR_EMAIL", "AUTHOR_IP1", "TITLE", "POST_TEXT", "DATE_CREATE", "PUBLISH_STATUS");
	$productsId = array();
	$dbComment = CBlogComment::GetList($arOrder, $arFilter, false, false, $arSelectedFields);
		$allComments = array();
	while ($arComment = $dbComment->Fetch()) {	
		array_push($allComments, $arComment);
		array_push($productsId, $arComment["AUTHOR_IP1"]);
	}
	
	if(CModule::IncludeModule("iblock")){ 
		$catQuery = CIBlock::GetList(
		    Array(), 
		    Array(
		        'TYPE'=>'catalog', 
		        'SITE_ID'=>SITE_ID, 
		        'ACTIVE'=>'Y'
		    ), false
		);
		$catalogs = array();
		while($oneCat = $catQuery->Fetch()) {
			array_push($catalogs, $oneCat['ID']);
		}
		$products = CIBlockElement::GetList(
			Array(),
			Array(
				"IBLOCK_ID" => $catalogs,
				"ID" => $productsId
			),
			false,
			false,
			Array("ID","NAME")
		);
		$arResult["NAMES"] = array();
		while($oneProduct = $products->GetNextElement()) {
			$prodFields = $oneProduct->GetFields();
			$arResult["NAMES"][$prodFields["ID"]] = $prodFields["NAME"];
		}
	}

	$rs_ObjectList = new CDBResult;
	$rs_ObjectList->InitFromArray($allComments);
	$rs_ObjectList->NavStart(intval('20'), false);
	$arResult["NAV_STRING"] = $rs_ObjectList->GetPageNavString("", '');
	$arResult["PAGE_START"] = $rs_ObjectList->SelectedRowsCount() - ($rs_ObjectList->NavPageNomer - 1) * $rs_ObjectList->NavPageSize;
	while($onePage = $rs_ObjectList->Fetch()) {
		$arResult["COMMENTS"][] = $onePage;
	}
	
	$this->IncludeComponentTemplate(); 
}
?>