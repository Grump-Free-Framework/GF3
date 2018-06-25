<?php

class Controller {
	
	protected $f3;

	function beforeroute(){
		//echo 'Before routing - ';
	}

	function afterroute(){
		//echo '- After routing';
	}

	function __construct() {
		//I was originally going to just completely remove this class but
		//This line makes it so you don't have to have a __construct on every controller;
		//instead just extend the page contoller class with Controller to have access to this variable.
		//on page controllers base can be accessed by $this->f3
		$this->f3 = Base::instance();
	}

}
