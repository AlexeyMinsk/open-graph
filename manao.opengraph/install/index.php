<?
	use \Bitrix\Main\ModuleManager,
		\Bitrix\Main\Loader,
		\Bitrix\Main\Config\Option,
		\Bitrix\Main\Localization\Loc; 
		
	Loc::loadMessages(__FILE__);
	
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
			$this->MODULE_NAME = Loc::getMessage("opengraph_MODULE_NAME");
			$this->MODULE_DESCRIPTION = Loc::getMessage("opengraph_MODULE_DESC");
			$this->PARTNER_NAME = Loc::getMessage("opengraph_PARTNER_NAME");
			$this->PARTNER_URI = Loc::getMessage("opengraph_PARTNER_URI");
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
			"Manao\\Opengraph\\OpenGraphLogic", 
			"OnEpilog" 
			);
			return true;
		}
		
		function UnInstallEvents(){
			
			Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler( 
			"main", 
			"OnEpilog", 
			$this->MODULE_ID, 
			"Manao\\Opengraph\\OpenGraphLogic", 
			"OnEpilog" 
			); 
			return true;
		}
		
		function setPageProps(){
			
			if(Loader::includeModule("fileman")){
				
				$filemanProps = CFileMan::GetPropstypes();
				
				$tempArr = array(
					'og:title' => Loc::getMessage("TITLE_NAME"),
					'og:description' => Loc::getMessage("DESC_NAME"),
					'og:image' => Loc::getMessage("IMAGE_NAME"),
					'og:image:width' => Loc::getMessage("IMAGE_WIDTH"),
					'og:image:height' => Loc::getMessage("IMAGE_HEIGHT")
				);
				
				$filemanProps = array_merge($filemanProps, $tempArr);
				
				CFileMan::SetPropstypes($filemanProps);
			}
		}
		
		function unsetPageProps(){
			
			if(Loader::includeModule("fileman")){
				
				$filemanProps = CFileMan::GetPropstypes();
				
				if(isset($filemanProps["og:title"])){
					unset($filemanProps["og:title"]);
				}
				if(isset($filemanProps["og:description"])){
					unset($filemanProps["og:description"]);
				}
				if(isset($filemanProps["og:image"])){
					unset($filemanProps["og:image"]);
				}
				if(isset($filemanProps["og:image:width"])){
					unset($filemanProps["og:image:width"]);
				}
				if(isset($filemanProps["og:image:height"])){
					unset($filemanProps["og:image:height"]);
				}
				CFileMan::SetPropstypes($filemanProps);
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
			Option::delete("manao.opengraph");
			
			return true;
		}
	}	