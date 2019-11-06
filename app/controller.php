<?php

class Controller {
	
	protected $f3;

	function beforeroute(){
		//echo 'Before routing - ';
		foreach (glob($this->f3->get('UI')."*/beforeroute.php") as $filename) {
			include $filename;
		}
	}

	function afterroute(){
		//echo '- After routing';
		foreach (glob($this->f3->get('UI')."*/afterroute.php") as $filename) {
			include $filename;
		}
	}

	function __construct() {
		//I was originally going to just completely remove this class but
		//This line makes it so you don't have to have a __construct on every controller;
		//instead just extend the page contoller class with Controller to have access to this variable.
		//on page controllers base can be accessed by $this->f3
		$this->f3 = Base::instance();
		
		$module = $this->f3->get("PARAMS.module");
		$this->f3->module = $module ? $module : $this->f3->get("defaultModule");
		
	}
	
	public function render($content, $template = 'templates/main') {
		$module = $this->f3->module;
		$this->f3->set('content', "$module/views/$content.htm");
		return \Template::instance()->render("../$template.htm");
	}
	
	public function model($model, $module_override = null) {
		$module = $module_override === null ? $this->f3->module : $module_override;
		$class = "\\modules\\{$module}\\models\\{$model}";
		return new $class();
	}

}
