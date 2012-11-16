
<?
	class phpexpresso {

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
		private function category() {

			}	


		private function dirlist($path) {
			$flist = scandir($dir);
			array_shift($flist);
        	array_shift($flist);
        	return $flist;
			}	


		/**
		* Генерируем центральный шаблон, в который вставкой заменяем кусок на текущий контент 
		*/
		private function genlayout(){

			}	