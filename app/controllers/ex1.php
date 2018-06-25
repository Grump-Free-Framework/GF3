<?php
namespace Controllers;
class ex1 extends \Controller {
	function get() {
		
		$example = new \Models\ex1();
		$this->f3->set('output', $example->returnOutput());

		echo render('ex1/index.htm');
	}
}