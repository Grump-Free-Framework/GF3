<?php

class ex2 {
	
	protected $db;
	
	public function __construct(GrumpyPDO $db) {
	    $this->db = $db;
	}
	
	public function returnOutput() {
	    return "Example Output Two";
	}
}