<?php 
	
	/*
	
	
	*/


	require ('markdown/markdown.php');


	class phpespresso {

		public $pages = array();
		public $path = array();

		
		function __construct($dir){
			$this->basedir = $dir.'/';
			$this->params = json_decode(file_get_contents('config.json')); # чтения конфига
			$this->config(); # локальные параметры
		}


		function config(){

			// пути исповедимы

			$this->posts = $this->basedir.'_posts/'; //исходники в md
			$this->layouts = $this->basedir.'_layouts/'; //внешний вид
			$this->maps = $this->basedir.'_maps/';
			$this->site = $this->basedir.'_site/';
			$this->cache = $this->basedir.'cache/';

			return $this;

		}


		/*
		* сканируем файлы в папке вместе с подпапками
		*/
		function dirlist($dir = '') {

			 
			$fulldir = $this->posts.$dir.'/'; // full name folder
			
			if (false == ($handle = @opendir($fulldir)))
				return null;
						
			$files = array();

			while(($currfile = readdir($handle)) !== false){
				
				if ( $currfile == '.' or $currfile == '..' )
					continue;
				elseif (is_dir($fulldir.$currfile)){
					$this->dirlist($dir.$currfile.'/');
				}
				elseif(pathinfo($currfile, PATHINFO_EXTENSION) == 'md'){
					$params = $this->parser_page($dir.$currfile);
					$pages[$params['date']] = $params['post'];
				}	

			}	

			
			closedir($handle);
		
			

			return $pages;

		}

	/**
		* create sitemap file
		*/
		function map() {

						
			$pages = $this->dirlist(); # получаем список всех постов
			$count = sizeof($pages);

			
			if (count == 0)
				return False;

			krsort($pages); # сортируем по последним записям

			/*$nn = 0;
			$page = 1;
			$limit = 20;
			$curr = array();

			foreach ($pages as $page) {
				
				$nn ++;
				$curr[] = $page;
				
				if ($nn == $limit) {  //бьем постранично
					$nn = 0;
					$this->fsave($this->maps.'page'.$page.'.json', json_encode($curr));  // сохраняем карту сайта
					$page ++;
					$curr = array();

				}

			

			}*/

			//$this->fsave($this->maps.'pages.json', json_encode($pages));  // сохраняем карту сайта
			
			return $pages;

		}

	

		
		//информация по отдельной странице
		public function page($name){

			$fsource = $this->posts.$name.'.md';
			$fmap = $this->maps.'posts/'.$name.'.json';

			// cache json backend
			if (file_exists($fmap) and filectime($fmap) > filectime($fsource))
				return json_decode(file_get_contents($fmap), True);

			$params = array();
			$start = False; 

			if ( !is_readable($fsource) )
				die ("can't read " . $fsource);

			$content = '';

			$handle = @fopen($fsource, "r"); 
			if ($handle) { 
   				while (!feof($handle)) { 
       				$str = trim(fgets($handle, 4096));
       				if ($str == '---')
       					$start = !$start;
       				elseif($start) {
						if ($cparams = $this->parse_param($str)) // если параметр а не пустая строка
       						$params[$cparams['name']] = $cparams['value'];
       				}		
       				else
       					$content .= "\n".$str;
 												
				}

				fclose($handle);	
   			
   				$params['content'] = Markdown($content);

   				if (!isset($params['date']))
   					$params['date'] = date("Y-m-d H:i", filectime($fsource));

   				//save map post
                $this->fsave($fmap, json_encode($params));

   				return $params;

   			}

		}




		/**
		* определяем параметры страницы	
		* @source - файл с основным контентом страницы
		*/
		public function parser_page($filename) {
						
			$name = substr($filename, 0, -3); //имя поста без расширения
			$c = $this->page($name);

			$this->render($this->site.'posts/'.str_replace('-', '/', $name).'.html', $c);

			
		}



		/*
		* формируем html страницы на основе шаблона
		*/
		private function render($file, $c) {

			ob_start();
				include ($this->layouts.'app.phtml');
				$result = trim(ob_get_contents());
			ob_end_clean();

			return $this->fsave($file, $result);


		}



		
		/**
		* сохранение файла и создания папки под него
		*/
		private function fsave($filename, $value = ''){
						
			if ($value == '')
				return False;

			$dir = dirname($filename);

			if (!is_dir($dir)){
				if (!mkdir($dir, 0755, True))
					die ("невозможно создать папку $dir");
			}	

			
			return file_put_contents($filename, $value);

		}

		

		/**
		* добавляем порцию параметров
		*
		*/
		private function parse_param($row){
			
			$pos = strpos($row, ':');
						
			if ($pos > 0) {
				$name = substr($row, 0, $pos);
				$value = substr($row, $pos + 1);
		
				return array('name'=>trim($name), 'value'=>trim($value));
			}
		
			return False;
		}		

	

	}