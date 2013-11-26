<?php 
	
	/*
	*  
	*
	*
	*/

	require ('markdown/markdown.php');


	class phpespresso {

		public $pages = array();
		public $path = array();

		
		function __construct($dir) {
			
			$this->basedir = $dir.'/';
			$this->posts = $this->basedir.'posts'; //исходники в mardown

			// подгрузка файла конфига
			$fconfig = $this->basedir.'config.json'; 
			
			if (file_exists($fconfig)) // если есть файл настроеk
				$this->params = json_decode(file_get_contents($fconfig)); # чтения конфига

			
		}

		
		// выбираем тему лога
		function theme($dir){
			$this->theme = $dir;
			return $this;
		}


		//генерим сайт
		function render($site) {

			$this->site = $site; //итоговая папка для рединга
			$this->backend = $site.'backend/'; //папка где будут лежать сгенерированный json бэкенд 
			
			
			$items = $this->init(); //натравляем папку с постами

			exit;

			$count = sizeof($items); // количество страниц в блоге

			
			if ($count == 0)
				return False;

			krsort($items); # сортируем по последним записям

			$nn = 0;
			$pnn = 0;
			$page = 1;
			$limit = 20;
			$curr = array();
			$fpage = $site.'index.html'; //первая страница

			foreach ($items as $item) {
				
				$nn ++; // общее кол-во элементов
				$pnn ++; // количество на странице
				$curr[] = $item;
				
				if ($pnn == $limit or $nn == $count) {  //бьем постранично
					if ($page > 1)
						$fpage = $this->site.'page/'.$page.'.html';
					
					$this->render_page($this->theme.'page.phtml', $fpage, $curr, $this->theme.'app.phtml'); // создаем страницу анонса статей
					$this->fsave($this->backend.'page'.$page.'.json', json_encode($curr));  // сохраняем карту сайта побитую на страницы
					
					$page ++;
					$pnn = 0;
					
					$curr = array(); //сброс массива

				}

			}

		}


		private function init() {

			$len = strlen($this->posts);

			//главные разделы
			$maindirs = glob($this->posts.'/*', GLOB_ONLYDIR|GLOB_NOSORT);


									
			if (sizeof($maindirs) > 0) {
				foreach($maindirs as $maindir){
					$name = substr($maindir, $len + 1);
					$publs[$name] = $this->dirlist($maindir);
				}
			}

			// массивы главных директорий
			// прошел одну директорию осортировал убил массив или склеил

						
			return;
		}

		


		/*
		* сканируем файлы в папке вместе с подпапками
		*/
		function dirlist($dir) {

			echo $dir."\n\n";

			$pages = glob($dir."/*.md");

			print_r($pages);

			
			return;

			

			foreach ($pages as $page){

			}



			if (false == ($handle = @opendir($dir)))
				return null;
						
			$pages = array();

			print_r(glob($dir."/*.md"));



			while(($currfile = readdir($handle)) !== false){
				
				echo "\n\n".$currfile."\n\n";
				
				if ( $currfile == '.' or $currfile == '..' )
					continue;
				elseif (is_dir($dir.'/'.$currfile)){
					$this->dirlist($dir.'/'.$currfile);
				}
				elseif(pathinfo($currfile, PATHINFO_EXTENSION) == 'md'){
					$params = $this->parser_page($dir.$currfile);
					$pages[$params['date']] = $params;
				}	

			}	

			
			closedir($handle);


			return $pages;

		}

	
		/**
		* определяем параметры страницы	
		* @source - файл с основным контентом страницы
		*/
		public function parser_page($filename) {
						
			$name = substr($filename, 0, -3); //имя поста без расширения
			$c = $this->page($name);
			$c['name'] = $name;
			$html_page = $this->site.'post/'.str_replace('-', '/', $name).'.html'; // итоговый html страница

			$this->pageview($this->theme.'app.phtml', $html_page, $c);


			
			return $c;

			
		}

		
		//информация по отдельной странице
		public function page($name){

			$fsource = $this->posts.'/'.$name.'.md';

			$fjson = $this->backend.'posts/'.$name.'.json';

			// cache json backend
			if (file_exists($fjson) and filectime($fjson) > filectime($fsource))
				return json_decode(file_get_contents($fjson), True);

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
                $this->fsave($fjson, json_encode($params));

   				return $params;

   			}

		}


		/*
		* рендрим пост в рамках шаблона
		*/
		private function pageview($layout, $file, $c){

			ob_start();
                include ($this->theme.'app.phtml');
                $result = trim(ob_get_contents());
            ob_end_clean();

            return $this->fsave($file, $result);

		}



		/*
		* рендринг любой странице в рамках шаблона
		*/
		private function render_page($view, $file, $c = null, $layout = '') {

			ob_start();
                include ($view);
                $c['content'] = trim(ob_get_contents());
            ob_end_clean();

            if ($layout == '') // если есть центральный шаблон
            	return $this->fsave($file, $content);

            ob_start();
            	include ($layout);
            	$result = trim(ob_get_contents());
            ob_end_clean();

            return $this->fsave($file, $result);	


		}







		/*
		* формируем html страницы на основе шаблона
		* @fview - текущий шаблон
		* @file - итоговый файл
		* @с - переменные 
		*/
		private function view($fview, $file, $c) {

			//формируем контент	
			ob_start();
				include ($fview);
				$content = trim(ob_get_contents());
			ob_end_clean();


			ob_start();
				include ($this->theme.'app.phtml');
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