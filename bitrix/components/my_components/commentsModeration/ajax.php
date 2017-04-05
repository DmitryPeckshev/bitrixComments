<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

header("Content-type: text/plain; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

if (CModule::IncludeModule("blog")) {
	
	if($_POST['action_area'] == 'del'){
		$DB->StartTransaction();
		if(!CBlogComment::Delete($_POST['id_area'])) {
		    echo 'Ошибка удаления ';
		    $DB->Rollback();
		}
		else{
		    $DB->Commit();
			echo "Комментарий удален!";
			echo "<input type='hidden' id='SendSuccess".$_POST['id_area']."'>";
		}
	}
	
	if($_POST['action_area'] == 'pub'){
		$DB->StartTransaction();
		if(!CBlogComment::Update($_POST['id_area'], array("PUBLISH_STATUS" => 'P'))) {
		    echo 'Не удалось опубликовать комментарий ';
		    $DB->Rollback();
		}
		else{
		    $DB->Commit();
			echo "Комментарий опубликован!";
			echo "<input type='hidden' id='SendSuccess".$_POST['id_area']."'>";
		}
	}
}
?>