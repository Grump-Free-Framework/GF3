<?php
namespace modules\alt_template_module;
class controller extends \Controller {

	function get() {
		echo $this->render('index', 'templates/bootstrap');
	}
	
}