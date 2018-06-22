<?php

class ex1 {
	
	protected $db;
	
	public function __construct(GrumpyPDO $db) {
	    $this->db = $db;
	}
	
	public function returnOutput() {
	    return "Example Output One";
	}
}