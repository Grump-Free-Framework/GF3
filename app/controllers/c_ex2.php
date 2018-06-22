<?php

class c_ex2 extends Controller {

	function index() {

		$example = new ex2($this->db);

		$output = $example->returnOutput();

		$this->f3->set('output', $output);
		
        echo $this->template->render('ex2/index.htm');

	}
	
	function method_example() {
		$this->f3->set('output', "Example Two - Method Example");
        echo $this->template->render('ex2/index.htm');
	}
	
	function method_example_post() {
		$this->f3->set('output', "User Value (method_example): ".$_POST['text']);
        echo $this->template->render('ex2/index.htm');
	}
	
	function post() {
		$this->f3->set('output', "User Value (index): ".$_POST['text']);
        echo $this->template->render('ex2/index.htm');
	}
	
}