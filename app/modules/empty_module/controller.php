<?php
namespace modules\empty_module;
class controller extends \Controller {

	function get() {
		echo $this->render('index');
	}
	
}