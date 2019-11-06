<?php
namespace modules\model_module;
class controller extends \Controller {

	function get() {
	
		//load model. Models are generally where any database interactions should be done.
		$model = $this->model('model');
		
		//set the variable $db_details to be used in the view
		$this->f3->set('db_details', $model->get_db_details());
		
		echo $this->render('index');
	}
	
}