<?php

class c_ex1 extends Controller {

	function index() {

		$example = new ex1($this->db);

		$output = $example->returnOutput();

		$this->f3->set('output', $output);
		
        echo $this->template->render('ex1/index.htm');

	}
	
}