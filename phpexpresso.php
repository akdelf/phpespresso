<?


	/**
	*
	*/


	class phpexpresso {

		/**
		* @files - список файлов-постов
		*/
		var $files = array();
		var $params = array();
		var $psource = '/source';
		var $phtml = '/html';


		/**
		*формируем из шаблона готовую html страницу
		*/
		function render() {

			}


		/**
		*получаем данные о текущей странице
		*/
		private function page () {			

			$dir = 'source/posts';
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

			return $this->dirlist('app/source');
		}	


		private function dirlist($dir) {

			$flist = scandir($dir);
			array_shift($flist);
        	array_shift($flist);
        	foreach ($flist as $file) {
        		echo $file;
        		if (is_dir($file)) {
        			$arr = $this->dirlist($dir.$file);
        		}		
        		
        		elseif(is_file($file) and pathinfo($file, PATHINFO_EXTENSION) == 'md'){
        			$this->files[$file] = filemtime($file);
        		
        		}	
        	}
        		
        		print_r($this->files);
        		return $flist;
			
			}	


		
		/**
		* определяем параметры страницы	
		* @source - файл с основным контентом страницы
		*/
		public function pageconfig($source) {
			
			$param = array();
			$start = False; 

			$handle = @fopen($source, "r"); 
			if ($handle) { 
   				while (!feof($handle)) { 
       				$line ++;
       				$line = fgets($handle, 4096);
       				if ($line == '---'){
       					if ($start) 
							break;
						$start = True;	
       				}
       				elseif ($param = $this->parse_param($line))
						$this->params[] = $param;	
				}	
   			
   				fclose($handle); 
			} 

		}	


		/**
		* определяем группу параметров
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


		private functon pagerender($file) {

			$content = markdown($file);

			//получаем результат
			ob_start();
			include('');
			$result = trim(ob_get_contents());
			ob_end_clean();	

			file_put_contents(filename, $result);
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

