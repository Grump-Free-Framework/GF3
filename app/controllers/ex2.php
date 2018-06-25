<?php
namespace Controllers;
class ex2 extends \Controller {

	function get() {
		
		$example = new \Models\ex2();
		$this->f3->set('output', $example->returnOutput());
		
		echo render('ex2/index.htm');
	}
	
	function method_example() {
		if($this->f3->get('VERB') == "POST") {
			$this->method_example_post();
			return;
		}
		$this->f3->set('output', "Example Two - Method Example");
		echo render('ex2/index.htm');
	}
	
	function method_example_post() {
		$this->f3->set('output', "User Value (method_example): ".$_POST['text']);
		echo render('ex2/index.htm');
	}
	
	function post() {
		$this->f3->set('output', "User Value (index): ".$_POST['text']);
		echo render('ex2/index.htm');
	}
	
}