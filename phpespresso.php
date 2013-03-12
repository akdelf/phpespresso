<?php 


	/**
	*
	*/

	require ('markdown/markdown.php');

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
			
			$this->path['source'] = $basedir.'/app/source/';
			$this->path['presult'] = $basedir.'/app/html/';
			$this->path['layer'] = $basedir.'/app/theme/';
			$this->path['config'] = $basedir.'/cache/config/';
			$this->path['html'] = $basedir.'/cache/html/';
			
			} 


		/**
		* формируем из шаблона готовую html страницу
		*/
		function render() {

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

			return $this->dirlist($this->path['source']);

			}	


		
		private function dirlist($dir) {
			
			$flist = scandir($dir);
			array_shift($flist);
        	array_shift($flist);

        	foreach ($flist as $file) {
        		$ffull = $dir.$file;
        		
        		if (is_dir($ffull)) {	
        			$arr = $this->dirlist($dir.$file.'/');
        		}		
        		elseif(is_file($ffull) and pathinfo($file, PATHINFO_EXTENSION) == 'md'){
        			$this->render_page($ffull);
        		}
        	}
        		        		
        	return $flist;}	


		
		/**
		* определяем параметры страницы	
		* @source - файл с основным контентом страницы
		*/
		public function render_page($source) {
			

			$params = array();
			$start = False; 

			$handle = @fopen($source, "r"); 
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
   			
   				$params['source'] = $source;
   				$params['content'] = Markdown($content);

   			  	$newfile = str_replace(array(' ', '-', ':'), '_', $params['date']);
   				
   				$this->pg_params[$params['date']] = $params;
   				file_put_contents($this->path['config'].$newfile.'.json', json_encode($params));

   				fclose($handle); 

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


		
		private function pagerender($layer, $file) {

			//$content = markdown($this->path['source'].$file);
			$content = file_get_contents($this->path['source'].$file);

			//получаем результат
			ob_start();
				include($this->path['layer'].$layer);
				$result = trim(ob_get_contents());
			ob_end_clean();	

			$htmlfile = $this->path['result'].str_replace('.md', '.html', $file);
			file_put_contents($htmlfile, $result);

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

