<?
	if (!$USER->IsAdmin())
    return;
	
	\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
	use Bitrix\Main\Config\Option;
	
	global $APPLICATION;
?>
<style>
	.options-wrapper{
	width:60%;
	padding: 0 5% 20px 10%;
	border: ridge silver 5px;
	overflow:hidden;
	}
	.og-options-form{
	width:100%;
	}
	.og-options-form>div{
	width:100%;
	clear: both;
	}
	.og-options-form>div>p:nth-of-type(odd){
	width:25%;
	float:left;
	}
	.og-options-form>div>p:nth-of-type(even){
	float:left;
	}
	.og-options-form>div input{
	width:100%;
	box-sizing:border-box;
	margin-bottom:10px;
	}
</style>
<?
	if(isset($_POST)){
		if($_POST['del'] !== 'on'){
			if(isset($_POST["title"]) && strlen($_POST["title"])){
				Option::set("manao.opengraph", "og:title", $_POST["title"]);
			}
			if(isset($_POST["description"]) && strlen($_POST["description"])){
				Option::set("manao.opengraph", "og:description", $_POST["description"]);
			}
			if(isset($_POST["image"]) && strlen($_POST["image"])){
				Option::set("manao.opengraph", "og:image", $_POST["image"]);
			}
		}
		else{
			Option::delete("manao.opengraph");
		}
	}
?>
<h1>Настройки модуля</h1>
<div class="options-wrapper">
	<h3>Установить основные мета-теги</h3>
	<form action="" method="POST" class="og-options-form">
		<div>
			<p>og:title</p>
			<p><input name="title" type="text" size="40" placeholder="<?=Option::get("manao.opengraph", "title")?>"></p>
		</div>
		<div>
			<p>og:description</p>
			<p><textarea name="description" cols="41" placeholder="<?=Option::get("manao.opengraph", "description")?>"></textarea></p>
		</div>
		<div>
		<p>og:image</p>
		<p>
		<input name="image" type="text" size="40" placeholder="<?=Option::get("manao.opengraph", "image")?>"></p>
		</div>
		<div>
			<p>Очистить все свойства?</p>
			<input name="del" type="checkbox">
		</div>
		<div>
			<input type="submit" value="Сохранить">
		</div>
	</form>
</div>