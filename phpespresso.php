<?php 


	
	require ('markdown/markdown.php');

/*
	контроллер

		генерация главной страницы + страниц с анонсами
		генерация каждой страницы 

		запусk шаблонизатора


	модель
		список всех постов
		информация по каждому посту	

*/

	
	/**
	* controller
	*/
	class phpespresso {

		/**
		* @files - список файлов-постов
		*/
		private $files = array();
		private $pg_params = array();
		private $path = array();
		


		/**
		* конфигурация директорий приложений
		*/
		function __construct($basedir){
			
			
			
			} 


		



		/**
		* формируем из шаблона готовую html страницу
		*/
		function render() {

			}


		function generation() {

			}	


		/**
		* получаем данные о текущей странице
		*/
		private function page() {			

			$dir = 'source/posts/';
			$flist = dirlist($dir); //анализируем папку с контентом
			foreach ($flist as $file){

				}
			
			}

		
		/**
		*формируем список рубрик
		*/
		private function gencategory() {

			}	

		
		public function generation() {

			return $this->dirlist();

			}	


		
		private function dirlist($dir = '') {
			
			$full_dir = $this->path['source'].$dir;


			$flist = scandir($full_dir);
			array_shift($flist);
        	array_shift($flist);

        	foreach ($flist as $file) {
        		$full_file = $full_dir.$file;
        		
        		if (is_dir($full_file)) {	
        			$arr = $this->dirlist($dir.$file.'/');
        		}		
        		elseif(is_file($full_file) and pathinfo($file, PATHINFO_EXTENSION) == 'md'){
        			$this->render_page($dir.$file);
        		}
        	}
        		        		
        	return $flist;}	


		
		function generation(){
			$this->map(); //формируем весь список постов
					
		}
		
		
		/**
		* $limit количество элементов на странице
		*/
		private function render_main($page = 1, $limit = 10) {

			arsort($this->files);
			$count = count($this->files);
			$start = $page*$limit;
			$end = $atart+$limit;
			
			if ($end > $count)
				$end = $count;

			
			for ($i=$start;$i<=$end; $i++)
				$items[$i] = $this->files[$i]; 
			

			return $items;


		}		



		/**
		* saved file and create directory
		*/
		private function file_save($file, $content) {
			
			$dir = dirname($file);

			if (!is_dir($dir))
				if (!mkdir($dir, 0775, True))
					return False;

			return file_put_contents($file, $content);	

		}		


		
		


		
		private function page_html($c, $file) {

			//получаем результат
			ob_start();
				include($this->path['layer'].'layer.phtml');
				$result = trim(ob_get_contents());
			ob_end_clean();	

			return $this->file_save($this->path['result'].str_replace('.md', '.html', $file), $result);

			}
			


		/**
		* вывод последних постов
		*/
		private function endpost($limit = 10) {
			return array_slice($input, 0, $limit);
			}


		private function genpage($content) {

			}


		/**
		* Генерируем центральный шаблон, в который вставкой заменяем кусок на текущий контент 
		*/
		private function genlayout(){

			}

	}		

	/*
	* model
	*/
	abstract class es_posts {

		/**
		* структура базы
		*/ 
		
		# поля хранения данных
		var $model = array('title'=>'varchar(200)', 
							 'date'=>'datetime', 
							 'description' => "text", 
							 'post' => 'text', 
							 'partname' => 'tinyint(4)'
						);  
		

		var $posts = array(); # список всех постов
		var $params = array();

		abstract function post($path); # генерация определенного поста
		abstract function map(); # формирование карты сайта - все посты
		abstract function config(); # загрузка файла конфига
		abstract function # генерация главной страницы и архива с запуском шаблона
		abstract function #  генерация конкретной страницы с запуском шаблока



		function __construct(){
			$this->params = json_decode(file_get_contents('config.json')); # чтения конфига
			$this->config(); # локальные параметры
		}


		

	}


	
	/**
	* хранение в md файлах
	*/
	class phpespresso_md extends es_posts {


		private $pages = array(); # список всех постов
		private $posts = 0; # общее кол-во постов

		
		function config(){

			$this->path['base'] = $basedir;
			$this->path['source'] = $basedir.'/app/source/';
			$this->path['layer'] = $basedir.'/app/theme/';
			$this->path['posts'] = $basedir.'/app/json/post/';
			$this->path['map'] = $basedir.'/app/json/map/';


		}

		function add() {
			//createfile
		}

	
		/**
		* постраничные анонсы
		*/
		function page($limit, $page = 1) {

			if (sizeof($this->page) == 0)
				$this->map();

			$count = sizeof($this->pages);
			$start = $page * $limit;


			for ($in=$atart: $n<=$end; $n++){
				$result[] = $this->pages[$in];
			}

			return $result; 
			
		}


		/**
		* count posts
		*/
		function posts() {

			

		}

		

		/**
		* create sitemap file
		*/
		function map() {

			$fmap = $this->path['base'].'/sitemap/';
			$page_array = array();

			$this->dirlist(); # получаем список всех постов
			
			$count = sizeof($this->pages);

			if (sizeof($this->pages) == 0)
				return False;


			arsort($this->pages); # сортируем по последним записям
			
			$nn = 0;
			$page  = 0;

			# разбиваем по страницам
			/*for ($p=0; $p <= $count; $p++){
				$page_array[] = array_shift($this->pages);	
				if ($nn == $limit or $p == $count){ # бьем порционально на страницы
					$nn = 0;
					$page++;
					file_put_contents($page.'.json', json_encode($page_array));
					$page_array = array(); # обнуляем массив		
				}
			}*/

		}


		/*
		* сканируем файлы в папке вместе с подпапками
		*/
		function dirlist($dir, $ext = 'md') {

			$full_dir = $this->path['source'].$dir;
			//$fmap = $this->path['base'].'/sitemap/'; 

			$handle = opendir($full_dir);
			
			while(($currfile = readdir($handle)) !== false){
				if ( $currfil == '.' or $currfil == '..' ){
					continue;
				elseif is_dir($currfile){
					$this->dirlist($dir.$currfile);
				}
				elseif(pathinfo($currfile, PATHINFO_EXTENSION) == $ext){
					$item = $this->post($file);
        			$uid = $item['date']; # индифицируем по дате создания файла
					//file_put_contents($this->path['map'].$uid.'.json', json_encode(array('file'=>$currfile)));
					$files[$uid] = $currfile;
				}	

			}	

			closedir($handle);


			return $files;

      

        	

		}




		/**
		* одиночный пост
		*/
		function post($path){
			
			$jfile = $this->path['json'].$path.'.json';
			$mdfile = $this->path['source'].$path.'.md';


			if (file_exists($jfile) and (filemtime($jfile) > filemtime($mdfile))) //сформированный файл проверка актульности
				return json_decode(file_get_contents($file)); 
			else {
				$result = $this->parser_page($path);
				if ($result !== null)
					file_put_contents($jfile, json_encode($result));
		
			}
				return $this->render($path);
				
		}

		


		/**
		* определяем параметры страницы	
		* @source - файл с основным контентом страницы
		*/
		public function parser_page($source) {
			
			$filename = $this->path['source'].$source;	

			$params = array();
			$start = False; 

			$handle = @fopen($filename, "r"); 
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
   			
   				$params['source'] = $filename;
   				$params['content'] = Markdown($content);

   			  	$newfile = $this->path['json'].str_replace('.md','.json', $source);   				
   				
   				$this->file_save($newfile, json_encode($params)); // page json
   				$this->page_html($params, $source);


   				$this->files[$params['date']] = $newfile;

   				fclose($handle);

   				return $params; 

			}}


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
		
			return False;}	





	}



