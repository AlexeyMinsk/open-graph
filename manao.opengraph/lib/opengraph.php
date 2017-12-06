<?
	namespace Manao\Opengraph;
	
	use Bitrix\Main\Page\Asset,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;
	
	Class openGraphLogic{
		
		function OnEpilog(){
			
			global $APPLICATION;
			
			$optionsId = array(//ID опции модуля
			"og:title",
			"og:description",
			"og:image"
			);
			
			$resultList = array();
			
			Asset::getInstance()->addString('<meta name="twitter:card" content="summary"/>');
			
			$props = array_merge($APPLICATION->GetDirPropertyList(), $APPLICATION->GetPagePropertyList());
			
			foreach($props as $key => $prop){
				if(stristr($key, 'og:') || strtolower($key) == 'title' || strtolower($key) == 'description' ){
					$resultList[strtolower($key)] = $prop;
				}
			}
			foreach($optionsId as $id){
				
				$option = Option::get("manao.opengraph", $id);
				
				if(!empty($option)){
					$resultList[$id] = $option;
				}
				}
				
				if(Loader::includeModule("fileman")){
					$filemanProps = \CFileMan::GetPropstypes('s1');			
				}
				
				$resultList = array_diff(array_merge($resultList, $filemanProps), array(""));
				
				if(!in_array('og:type', $resultList)){
					Asset::getInstance()->addString('<meta property="og:type" content="website"/>');
				}
				foreach($resultList as $key => $val){
					
					if(strpos($key, 'og:') !== false){
						self::addMeta($key, $val);
					}
					elseif($key == "title"){
						if(!in_array('og:title', $resultList)){
							self::addMeta('og:title', $val);
						}
					}
					elseif($key == "description"){
						if(!in_array('og:description', $resultList)){
							self::addMeta('og:description', $val);
						}
					}
					elseif($key == "image"){
						if(!in_array('og:image', $resultList)){
							self::addMeta('og:image', $val);
						}
					}
				}
			}
			
			static function addMeta($name, $val){
				
				if(empty($name) || empty($val)){
					return false;
				}
				
				Asset::getInstance()->addString('<meta property="'.$name
				.'" content="'.$val.'" />');
				
				return true;
			}
		}
	?>		