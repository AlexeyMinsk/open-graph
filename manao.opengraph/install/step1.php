<?
IncludeModuleLangFile(__FILE__);
?>
<form action="<?echo $APPLICATION->GetCurPage(); ?>">
<?echo bitrix_sessid_post(); ?>
<input type="hidden" name="lang" value="<?echo LANG ?>">
<input type="hidden" name="id" value="latyhouski_restorepass">
<input type="hidden" name="install" value="Y">
<input type="hidden" name="step" value="2">
<input type="submit" name="inst" value="<?echo GetMessage("MOD_INSTALL"); ?>">
</form>