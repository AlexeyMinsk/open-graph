<?
	namespace Manao\Opengraph;
	
	use Bitrix\Main\Page\Asset,
		Bitrix\Main\Config\Option,
		Bitrix\Main\Loader;

	Class openGraphLogic{
		
		static $propName = array(
			"og:url",
			"og:title",
			"og:description",
			"og:image"
		);
		
		function onProlog(){
			
			global $APPLICATION;
			
			Asset::getInstance()->addString('<meta name="twitter:card" content="summary"/>');
			Asset::getInstance()->addString('<meta property="og:type" content="website"/>');			
			$props = $APPLICATION->GetPagePropertyList();
			$props = array_merge($props, $APPLICATION->GetDirPropertyList());
			
			foreach($props as $key => $prop){
				if(stristr($key, 'og:')){
					self::$propName[] = $key;
				}
			}
			
			if(Loader::includeModule("fileman")){
				$filemanProps = CFileMan::GetPropstypes(SITE_ID);
			}
			
			foreach(self::$propName as $name){
				
				$tempName = end(explode(":", $name));
				$moduleOption = false;
				
				if(!empty($filemanProps) && is_array($filemanProps) && count($filemanProps)){
					
					foreach($filemanProps as $key => $val){
						
						if($key == $name && strlen($val)){

							$moduleOption = $val;
							break;
						}
					}
				}
				
				if(empty($moduleOption)){
					
					$moduleOption = Option::get("manao.opengraph", $tempName);
					
					if(empty($moduleOption)){
						if($name == "og:url"){
							$moduleOption = "http://".$_SERVER['HTTP_HOST'].$APPLICATION->GetCurDir();
						}
						else{
							$moduleOption = $APPLICATION->GetProperty($tempName);
						}
					}
					elseif($tempName == "image"){
					
						if($moduleOption[0] === '/'){
							$moduleOption = "http://".$_SERVER['HTTP_HOST'].$moduleOption;
						}
						else{
							$moduleOption = "http://".$_SERVER['HTTP_HOST'].$APPLICATION->GetCurPage().'/'.$moduleOption;
						}
					}
				}
				
				if(!empty($moduleOption)){
					Asset::getInstance()->addString('<meta property="'.$name
						.'" content="'.$moduleOption.'" />');
				}
			}
		}
	}
?>	