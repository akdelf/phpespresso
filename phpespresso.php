<?php 


	/**
	*
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
		function __construct($dir){
			
			$this->path['source'] = $dir.'/app/source/';
			$this->path['presult'] = $dir.'/app/html/';
			$this->path['player'] = $dir.'/app/theme/';
			
			} 


		/**
		*формируем из шаблона готовую html страницу
		*/
		function render() {

			}


		/**
		*получаем данные о текущей странице
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
        			$this->pageconfig($ffull);
        		
        		}	
        	}
        		
        		print_r($this->pg_params);
        		return $flist;
			
			}	


		
		/**
		* определяем параметры страницы	
		* @source - файл с основным контентом страницы
		*/
		public function pageconfig($source) {
			

			$params = array();
			$start = False; 

			$handle = @fopen($source, "r"); 
			if ($handle) { 
   				while (!feof($handle)) { 
       				$line ++;
       				$str = fgets($handle, 4096);
       				if ($str == '---'){
       					if ($start) 
							break;
						$start = True;	
       				}
       				elseif ($cparams = $this->parse_param($str))
       					$params[$cparams['name']] = $cparams['value'];									
				}	
   			
   				$params['source'] = $source;
   				$this->pg_params[$params['date']] = $params;

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
		
				return array('name'=>$name, 'value'=>$value);
			}
		
			return False;

			}


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

