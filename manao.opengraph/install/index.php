<?
	\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
	use \Bitrix\Main\ModuleManager,
	Bitrix\Main\Loader;
	
	$strPath2Lang = str_replace("\\", "/", __FILE__);
	$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
	
	include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
	
	Class manao_opengraph extends CModule {
		
		const MODULE_ID = 'manao.opengraph';
		
		var $MODULE_ID = "manao.opengraph";
		var $MODULE_NAME;
		var $MODULE_VERSION;
		var $MODULE_VERSION_DATE;
		var $MODULE_DESCRIPTION;
		
		function __construct() {
			
			$arModuleVersion = array();
			$path = str_replace("\\", "/", __FILE__);
			$path = substr($path, 0, strlen($path) - strlen("/index.php"));
			include($path."/version.php");
			
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
			$this->MODULE_NAME = GetMessage("opengraph_MODULE_NAME");
			$this->MODULE_DESCRIPTION = GetMessage("opengraph_MODULE_DESC");
			$this->PARTNER_NAME = GetMessage("opengraph_PARTNER_NAME");
			$this->PARTNER_URI = GetMessage("opengraph_PARTNER_URI");
		}
		
		function InstallDB($arParams = array()){
			return true;
		}
		
		function UnInstallDB($arParams = array()){
			return true;
		}
		
		function InstallEvents(){
			
			Bitrix\Main\EventManager::getInstance()->registerEventHandler( 
				"main", 
				"OnEpilog", 
				$this->MODULE_ID, 
				"Manao\\Opengraph\\openGraphLogic", 
				"OnEpilog" 
			);
			return true;
			}
		
		function UnInstallEvents(){
			
			Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler( 
				"main", 
				"OnEpilog", 
				$this->MODULE_ID, 
				"Manao\\Opengraph\\openGraphLogic", 
				"OnEpilog" 
			); 
			return true;
		}
		
		function setPageProps(){
			
			if(Loader::includeModule("fileman")){
				
				$rsSites = CSite::GetList($by="sort", $order="asc", Array());

				while($site = $rsSites->fetch()){
					$filemanProps = CFileMan::GetPropstypes($site['ID']);
					
					if(empty($filemanProps)){
						$filemanProps = array();
					}
					$filemanProps['og:title'] = '';
					$filemanProps['og:description'] = '';
					$filemanProps['og:image'] = '';
					
					CFileMan::SetPropstypes($filemanProps, false, $site['ID']);
				}
			}
		}
		
		function unsetPageProps(){
			
			if(Loader::includeModule("fileman")){
				
				$rsSites = CSite::GetList($by="sort", $order="asc", Array());
				
				while($site = $rsSites->fetch()){
					$filemanProps = CFileMan::GetPropstypes($site['ID']);
					
					if(isset($filemanProps["og:title"])){
						unset($filemanProps["og:title"]);
					}
					if(isset($filemanProps["og:description"])){
						unset($filemanProps["og:description"]);
					}
					if(isset($filemanProps["og:image"])){
						unset($filemanProps["og:image"]);
					}
					CFileMan::SetPropstypes($filemanProps, false, $site['ID']);
				}
			}
		}
		
		function DoInstall(){
			
			ModuleManager::RegisterModule(self::MODULE_ID);
			$this->InstallEvents();
			$this->setPageProps();
		
		return true;
		}
		
		function DoUninstall(){
			
			ModuleManager::UnRegisterModule(self::MODULE_ID);
			$this->UnInstallEvents();
			$this->unsetPageProps();
			
			return true;
		}
	}
?>	