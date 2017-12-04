<?
	/*
	ver 0.0.2
	перед использование добавить в тег <html prefix="og: http://ogp.me/ns#">
	Определение класса OpenGraph в файл init.php сайта
	в месте вывода использовать нестатичесскую функцию showOG()
	Пример:
	
	$objectOG = new OpenGraph();
	$objectOG->showOG();
	
	Определять можно в контентной части с помощью нестатичесской функции setOG()
	в функцию setOG() передаётся массив, где ключ - имя свойства open graph
	например og:title будет title;
	значением выступает строка или ассоциативный массив(для ключа image, но можно передать и строку).
	Функция setOG() определяет основные параметры OG(кроме image) без явной передачи
	с возможностью переопределения(title, type, url, description, locale, site_name).
	Допустим вызов setOG() без передачи параметров, однако в этом случае соц.сеть подтянет случайную картинку.
	Рекомендуемое разрешение картинки 1200x610(facebook). MIME тип картинки определяется из расширения.
	Пример:
	
	$objectOG = new OpenGraph();
	$objectOG->setOG(
		array(
			"description" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit.",
			"image" => array(
				array(
					"url" => SITE_TEMPLATE_PATH."/img/no-foto.jpg",
					"width" => 300,
					"height" => 300
				)
			)
		)
	);
	*/
	class OpenGraph{
		
		private $propName = array(
			"og:title",
			"og:type",
			"og:url",
			"og:description",
			"og:locale",
			"og:site_name",
			"og:image",
			"image:secure_url",
			"og:image:type",
			"og:image:width",
			"og:image:height",
			"og:audio",
			"og:video"
		);
		
		public function setOG($options){
			
			global $APPLICATION;
			
			if(isset($options["title"]) && strlen($options["title"])){
				
				$APPLICATION->SetPageProperty("og:title", $options["title"]);
			}
			else{
				$APPLICATION->SetPageProperty("og:title", $APPLICATION->GetTitle());
			}
			
			if(isset($options["type"]) && strlen($options["type"])){
				
				$APPLICATION->SetPageProperty("og:type", $options["type"]);
			}
			else{
				$APPLICATION->SetPageProperty("og:type", 'website');
			}
			
			if(isset($options["url"]) && strlen($options["url"])){
				
				$APPLICATION->SetPageProperty("og:url", $options["url"]);
			}
			else{	
				$APPLICATION->SetPageProperty("og:url", "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			}
			
			if(isset($options["description"]) && strlen($options["description"])){
				
				$APPLICATION->SetPageProperty("og:description", $options["description"]);
			}
			else{				
				$APPLICATION->SetPageProperty("og:description", $APPLICATION->GetProperty('description'));
			}
			
			if(isset($options["locale"]) && strlen($options["locale"])){
				
				$APPLICATION->SetPageProperty("og:locale", $options["locale"]);
			}
			else{
				$APPLICATION->SetPageProperty("og:locale", "ru-RU");
			}
			
			if(isset($options["site_name"]) && strlen($options["site_name"])){
				
				$APPLICATION->SetPageProperty("og:site_name", $options["site_name"]);
			}
			else{
				$APPLICATION->SetPageProperty("og:site_name", $_SERVER['HTTP_HOST']);
			}
			
			if(isset($options["image"])){
				
				if(is_array($options["image"]) && count($options["image"])){
					foreach($options["image"] as $picArr){
						if(isset($picArr['url'])){
							$APPLICATION->SetPageProperty("og:image", $picArr['url']);
												
							$APPLICATION->SetPageProperty(
								"og:image:type",
								$this->getImgType($picArr['url'])
							);
						}
						if(isset($picArr['secure_url'])){
							$APPLICATION->SetPageProperty("og:image:secure_url", $picArr['secure_url']);
							
							$APPLICATION->SetPageProperty(
								"og:image:type",
								$this->getImgType($picArr['secure_url'])
							);
						}
						if(isset($picArr['width'])){
							$APPLICATION->SetPageProperty("og:image:width", $picArr['width']);
						}
						if(isset($picArr['height'])){
							$APPLICATION->SetPageProperty("og:image:height", $picArr['height']);
						}
					}
				}
				elseif(gettype($options["image"]) === "string" && strlen($options["image"])){
					$APPLICATION->SetPageProperty("og:image", $options["image"]);
				}
			}
			
			if(isset($options["audio"]) && strlen($options["audio"])){
				$APPLICATION->SetPageProperty("og:audio", $options["audio"]);
			}
			
			if(isset($options["video"]) && strlen($options["video"])){	
				$APPLICATION->SetPageProperty("og:video", $options["video"]);
			}
		}
		
		public function showOG(){
			
			global $APPLICATION;
			
			foreach($this->propName as $name){
				$APPLICATION->ShowMeta($name);
			}
		}
		
		private function getImgType($imageUrl){
			
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