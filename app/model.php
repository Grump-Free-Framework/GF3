<?php

class Model {

	protected $f3, $db;

	function __construct() {
		//This allows you to use `$this->db` to call GrumpyPDO in module models.
		$this->f3 = Base::instance();
		$this->db = $this->f3->db;

	}



}
