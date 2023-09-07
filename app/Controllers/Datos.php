<?php
	namespace App\Controllers;
	class Datos{
		
		protected String $title;

		function __construct(){
			//$this->title = $title;			
		}

		public function getTitle(){
			return $this->title;
		}

		public function setTitle(String $title){
			$this->title = $title;
		}
	}
?>