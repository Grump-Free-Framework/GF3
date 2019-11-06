<?php

class Model {
	
	protected $db;

	function __construct() {
		//This allows you to use `$this->db` to call GrumpyPDO in module models.
		$base = Base::instance();
		$this->db = $base->db;
	}
	
	

}
