<?


	/**
	*
	*/


	class phpexpresso {

		/**
		* @files - список файлов-постов
		*/
		var $files = array();

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

			return $this->dirlist('source/posts');
		}	


		private function dirlist($dir) {

			$flist = scandir($dir);
			array_shift($flist);
        	array_shift($flist);
        	foreach ($flist as $file) {
        		if (is_dir($file)) {
        			$arr = $this->dirlist($file);
        		}		
        		
        		elseif(is_file($file) and pathinfo($file, PATHINFO_EXTENSION) == 'md'){
        			$this->files[$file] = filemtime($file);
        		
        		}	
        	}
        		return $flist;
			
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

