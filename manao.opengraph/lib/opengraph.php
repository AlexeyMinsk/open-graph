<?
	namespace Manao\Opengraph;
	
	use Bitrix\Main\Page\Asset,
		Bitrix\Main\Config\Option,
		Bitrix\Main\Loader;
	
	Class OpenGraphLogic{
		
		function OnEpilog(){
			
			global $APPLICATION;
			
			$optionsId = array(//ID опции модуля
				"og:title",
				"og:description",
				"og:image",
				"og:image:width",
				"og:image:height"
			);
			
			$resultList = array();
			
			Asset::getInstance()->addString('<meta name="twitter:card" content="summary"/>');
			
			foreach($optionsId as $id){
				
				$option = Option::get("manao.opengraph", $id);
				
				if(!empty($option)){
					$resultList[$id] = $option;
				}
			}
			
			$props = array_merge($APPLICATION->GetDirPropertyList(), $APPLICATION->GetPagePropertyList());
			
			foreach($props as $key => $prop){
				if(stristr($key, 'og:') || strtolower($key) == 'title' || strtolower($key) == 'description' ){
					$resultList[strtolower($key)] = $prop;
				}
			}
			
			if(!array_key_exists('og:type', $resultList)){
				Asset::getInstance()->addString('<meta property="og:type" content="website"/>');
			}
			if(!array_key_exists('og:url', $resultList)){
				$url = self::getProtocol() . $_SERVER['HTTP_HOST'] . $APPLICATION->GetCurDir();
				Asset::getInstance()->addString('<meta property="og:url" content="'.$url.'"/>');
			}
			
			foreach($resultList as $key => $val){
				
				if(strpos($key, 'og:') !== false){
					self::addMeta($key, $val);
				}
				elseif($key == "title"){
					if(!array_key_exists('og:title', $resultList)){
						self::addMeta('og:title', $val);
					}
				}
				elseif($key == "description"){
					if(!array_key_exists('og:description', $resultList)){
						self::addMeta('og:description', $val);
					}
				}
			}
		}
		
		static function addMeta($name, $val){
			
			if(empty($name) || empty($val)){
				return false;
			}
			
			if(str_replace(' ', '', strtolower($name)) == 'og:image'){
				if(stristr($val, 'https://') || self::getProtocol() === 'https://'){
					$name .= ':secure_url';
				}
				if($imageUrl = self::getImageUrl($val)){
					Asset::getInstance()->addString('<meta property="'.$name
						.'" content="'.$imageUrl.'" />');
				}
			}
			else{
				Asset::getInstance()->addString('<meta property="'.$name
				.'" content="'.$val.'" />');
			}
			return true;
		}
		
		static function getImageUrl($url){
			
			global $APPLICATION;
			preg_match('/(http(?:s)?:\/\/)?([\w\/\-\d]+\.)+(\w{2,}$)/', $url, $matches);
			
			if(count($matches) == 0){
				return false;
			}
			
			if(empty($matches[1])){//если ссылка внутренняя
				
				if($matches[2][0] == '/'){
					$url = self::getProtocol() . $_SERVER['HTTP_HOST'] . $matches[0];
				}
				else{
					$url = self::getProtocol() . $_SERVER['HTTP_HOST'] . $APPLICATION->GetCurDir() . $matches[0];
				}
			}
			
			$MIME =self::getImgType($matches[3]);
			if($MIME){
				self::addMeta('og:image:type', $MIME);//добавить MINE тип картинки
			}
			
			return $url;
		}
		
		static function getProtocol(){
			
			$protocol = 'http://';
			
			if(\CMain::IsHTTPS()){
				$protocol = 'https://';
			}
			return $protocol;
		}
		
		static function getImgType($imageUrl){
			
			$expansion = end(explode(".", $imageUrl));
			$mine = "";
			
			switch($expansion){
				
				case "jpg":
				case "jpeg":
				case "jfif":
				case "jfif-tbnl":
				case "jpe":
				$mine = "image/jpeg";
				break;
				case "png":
				$mine = "image/png";
				break;
				case "gif":
				$mine = "image/gif";
				break;
				case "bm":
				case "bmp":
				$mine = "image/bmp";
				break;
			}
			return $mine;
		}
	}
?>