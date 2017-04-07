<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a class="allcommlink <?php if($_GET["show"] == 'P'){
	echo " unactive";
}?>" href="<?php echo $APPLICATION->GetCurPage();?>?show=K">Неопубликованные</a>
<a class="allcommlink <?php if(!$_GET["show"] || $_GET["show"] == 'K'){
	echo " unactive";
}?>" href="<?php echo $APPLICATION->GetCurPage();?>?show=P">Опубликованные</a>

<div class="commcontainer">
	<?php foreach ($arResult['COMMENTS'] as $oneComment):?>
		<div class="oneComment" id="comm<?php echo $oneComment['ID'];?>">

			<div class="showStatus" id="status<?php echo $oneComment['ID'];?>"></div>

			<div class="commname">
				<?php echo $oneComment['AUTHOR_NAME'];?>&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="commemail">					
					<?php echo $oneComment['AUTHOR_EMAIL'];?>
				</span>
				<span class="commdate">
					<?php echo $oneComment['DATE_CREATE'];?>
				</span>
			</div>
			<div class="commproduct">
				<?php echo $arResult['NAMES'][$oneComment['AUTHOR_IP1']];?>
			</div>
			<div class="commrate">
				<?php for($i=0;$i<$oneComment['TITLE'];$i++){
					echo '<img src="/bitrix/components/my_components/productComments/images/star.png" class="starimg">';
				}?>
			</div>
			<div class="commtext">
				<pre><?php echo $oneComment['POST_TEXT'];?></pre>
			</div>
			<div id="buttons<?php echo $oneComment['ID'];?>">
				<input type="<?php if(!$_GET["show"] || $_GET["show"] == 'K'){echo "button";}else{echo "hidden";}?>" value="Опубликовать" name="commPub" onclick="changeComment(<?php echo $oneComment['ID'];?>,'pub')"/>
				<input type="button" value="Удалить" name="commDel" onclick="changeComment(<?php echo $oneComment['ID'];?>,'del')"/>
			</div>
		</div>
	<?php endforeach;?>
</div>
<div class="pageNav">
	<?echo $arResult["NAV_STRING"];?>
</div>
