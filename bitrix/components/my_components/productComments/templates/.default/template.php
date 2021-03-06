<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?CJSCore::Init(array("jquery"));?>
<br/><h3>Напишите свой комментарий</h3><br/>
<?php
global $USER;		
if($USER->GetFirstName()){
	$currentName = $USER->GetFirstName();
}else{
	$currentName = $USER->GetLogin();
}
?>	
<form id="commForm">
	<div class="formRow">
		<p class="inptittle">Имя: </p>
		<input type="text" name="comm_name" value="<?php echo $currentName; ?>" class="inptext"/>
		<p id="noname"></p>
	</div>
	<div style="clear:both;"></div>

	<div class="formRow">
		<p class="inptittle">Email: </p>
		<input type="email" name="comm_email" value="<?php echo $USER->GetEmail(); ?>" class="inptext"/>
		<p id="noemail"></p>
	</div>
	<div style="clear:both;"></div>
	
	<div class="formRow">
	<p class="inptittle">Рейтинг товара: </p>
		<div class="stars" id="stars">
			<label class="silver">
				<input type="radio" name="comm_rate" value="1" />
			</label><label class="silver">
				<input type="radio" name="comm_rate" value="2" />
			</label><label class="silver">
				<input type="radio" name="comm_rate" value="3" />
			</label><label class="silver">
				<input type="radio" name="comm_rate" value="4" />
			</label><label class="silver">
				<input type="radio" name="comm_rate" value="5" />
			</label>
		</div>
		<p id="norate"></p>
	</div>
	<div style="clear:both;"></div>
	<p class="inptittle">Текст комментария: </p><p id="nocomm"></p>
	<textarea name="comm_text"></textarea>
	<input type="hidden" value="<?php echo $arResult['PRODUCT_ID']; ?>" id="prodId" />
	<br/>
	<input type="button" value="Отправить" name="commSend" id="commSend">
</form>
<p id="comm_status"></p>

<h3>Комментарии пользователей</h3>
<div class="commallstat">
	<?php if($arResult["COMM_NUM"] == 0):?>
		Нет отзывов.
	<?php else: ?>
		<div id="average" style="width:<?php echo round(24*$arResult['COMM_AVERAGE'])+1;?>px">
		<?php for($i=0;$i<5;$i++){
			echo '<img src="/bitrix/components/my_components/productComments/images/star.png" class="starimg">';
		}?>
		</div><br/>
		<?php echo $arResult["COMM_NUM"];
			if($arResult["COMM_NUM"]%10==1){echo ' отзыв';}
			elseif($arResult["COMM_NUM"]%10==2||$arResult["COMM_NUM"]%10==3||$arResult["COMM_NUM"]%10==4){echo ' отзыва';}
			else{echo " отзывов";} ?> | <?php echo round($arResult["COMM_AVERAGE"],1); 
		?> из 5
	<?php endif; ?>
</div>
<div>
	<?php foreach ($arResult['COMMENTS'] as $oneComment):?>
		<div class="oneComment">
			<div class="commrate">
				<?php for($i=0;$i<$oneComment['TITLE'];$i++){
					echo '<img src="/bitrix/components/my_components/productComments/images/star.png" class="starimg">';
				}?>
				<span class="commdate">
					<?php echo $oneComment['DATE_CREATE'];?>
				</span>
			</div>
			<div class="commname">
				<?php echo $oneComment['AUTHOR_NAME'];?>
			</div>
			<div class="commtext">
				<pre><?php echo $oneComment['POST_TEXT'];?></pre>
			</div>
		</div>
	<?php endforeach;?>
</div>
<input type="hidden" id="extraEmails" value="<?php echo $arResult['MAIL_ADRESS']; ?>" />
<div class="pageNav">
	<?echo $arResult["NAV_STRING"];?>
</div><br/>

<?php global $USER;
if ($USER->IsAdmin()){
	echo "<a class='modlink' href='/catalog/mod-komm.php'>Модерация комментариев</a>";
} ?>
<br/><br/>