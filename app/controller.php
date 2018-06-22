<?php

class Controller {
	
	protected $f3, $db, $template;
	protected $loadtime;

	function beforeroute(){
		//echo 'Before routing - ';
		echo $this->template->render('main_header.htm');
		echo $this->template->render('main_navbar.htm');
	}

	function afterroute(){
		//echo '- After routing';
		$this->f3->set('loadtime', round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3));
		echo $this->template->render('main_footer.htm');
	}

	function __construct() {
		
		$this->f3 = Base::instance();
		
	    $db = new GrumpyPDO(
			$this->f3->get('db_host'), 
			$this->f3->get('db_username'), 
			$this->f3->get('db_password'), 
			$this->f3->get('db_database')
		);

		$this->template = new Template;
	    $this->db = $db;
	}

}
