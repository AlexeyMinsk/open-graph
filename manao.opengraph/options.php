<?
	if (!$USER->IsAdmin()){
		return;
	}
	
	use \Bitrix\Main\Config\Option,
		\Bitrix\Main\Localization\Loc;
	
	Loc::loadMessages(__FILE__);
	global $APPLICATION;
	
	$rightsTab = array(
		"DIV" => "edit3",
		"TAB" => Loc::getMessage("MAIN_OPTIONS"),
		"ICON" => "fileman_settings",
		"TITLE" => Loc::getMessage("MODULE_OPTIONS")
	);
	$aTabs[] = $rightsTab;
	
	$tabControl = new CAdmintabControl("tabControl", $aTabs);
	
	$tabControl->Begin();
	
	if(isset($_POST)){

		if(isset($_POST["title"])){
			Option::set("manao.opengraph", "og:title", $_POST["title"]);
		}
		if(isset($_POST["description"])){
			Option::set("manao.opengraph", "og:description", $_POST["description"]);
		}
		if(isset($_POST["image"])){
			Option::set("manao.opengraph", "og:image", $_POST["image"]);
		}
	}
?>
<div class="options-wrapper">
	
	<form action="" method="POST" class="og-options-form">
		<?=bitrix_sessid_post()?>
		<?$tabControl->BeginNextTab();?>
		
		<tr>
			<td valign="top"><label for="og:title">og:title</label></td>
			<td><input type="text" name="title" id="og:title" size="40" value="<?=Option::get("manao.opengraph", "og:title")?>"></td>
		</tr>
		<tr>
			<td valign="top"><p>og:description</p></td>
			<td><textarea name="description" cols="42"><?=Option::get("manao.opengraph", "og:description")?></textarea></td>
		</tr>
		<tr>
			<td valign="top"><label for="og:image">og:image</label></td>
			<td><input type="text" name="image" id="og:image" size="40" value="<?=Option::get("manao.opengraph", "og:image")?>"></td>
		</tr>
		<?$tabControl->End();?>
		<?$tabControl->Buttons(array());?>
		</form>
	</div>
