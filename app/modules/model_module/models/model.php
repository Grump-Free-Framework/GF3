<?php
namespace modules\model_module\models;
class Model extends \Model {
	
	public function get_db_details() {
	    return $this->db;
	}
	
}