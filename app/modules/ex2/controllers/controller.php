<?php
namespace modules\ex2\controllers;
class Controller extends \Controller {

	function get() {
		
		$example = loadModel('model');
		$this->f3->set('output', $example->returnOutput());
		
		echo render('index');
	}
	
	function method_example() {
		if($this->f3->get('VERB') == "POST") {
			$this->method_example_post();
			return;
		}
		$this->f3->set('output', "Example Two - Method Example");
		echo render('index');
	}
	
	function method_example_post() {
		$this->f3->set('output', "User Value (method_example): ".$_POST['text']);
		echo render('index');
	}
	
	function post() {
		$this->f3->set('output', "User Value (index): ".$_POST['text']);
		echo render('index');
	}
	
}