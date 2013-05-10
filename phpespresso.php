<?php 


	
	require ('markdown/markdown.php');


	class phpespresso {

		public $pages = array();
		public $path = array();

		
		function __construct($dir){
			$this->basedir = $dir;
			$this->params = json_decode(file_get_contents('config.json')); # чтения конфига
			$this->config(); # локальные параметры
		}


		function config(){

			$this->path['base'] = $this->basedir;
			$this->path['source'] = $this->basedir.'/app/source/';
			$this->path['layer'] = $this->basedir.'/app/theme/';
			$this->path['posts'] = $this->basedir.'/app/json/post/';
			$this->path['map'] = $this->basedir.'/app/json/map/';



			return $this;

		}


		/*
		* сканируем файлы в папке вместе с подпапками
		*/
		function dirlist($dir) {

			//$fmap = $this->path['base'].'/sitemap/';

			$handle = opendir($dir);

			if ($handle == False)
				return null;
						
			$files = array();

			while(($currfile = readdir($handle)) !== false){
				if ( $currfile == '.' or $currfile == '..' )
					continue;
				elseif (is_dir($dir.$currfile)){
					$this->dirlist($dir.$currfile.'/');
				}
				elseif(pathinfo($currfile, PATHINFO_EXTENSION) == 'md'){
					$params = $this->parser_page($dir.$currfile);
					$uid = $params['date']; # индифицируем по дате создания файла
					file_put_contents($this->path['map'].$uid.'.json', json_encode(array('file'=>$currfile))); # формируем карту сайта
					$this->pages[$uid] = $currfile;
				}	

			}	

			closedir($handle);

			
			return $files;

		}

	/**
		* create sitemap file
		*/
		function map() {

			$fmap = $this->path['base'].'/sitemap/';
			$this->dirlist($this->path['source']); # получаем список всех постов
									
			$count = sizeof($this->pages);

			if (sizeof($this->pages) == 0)
				return False;

			arsort($this->pages); # сортируем по последним записям
			
			$nn = 0;
			$page  = 0;

			return $pages;

		}	




		/**
		* определяем параметры страницы	
		* @source - файл с основным контентом страницы
		*/
		private function parser_page($filename) {
						
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
   			  	file_put_contents($newfile, json_encode($params)); # формируем карту сайта  				
   				
   				//$this->file_save($newfile, json_encode($params)); // page json
   				//$this->page_html($params, $source);


   				//$this->files[$params['date']] = $newfile;

   				fclose($handle);

   				return $params; 

			}
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