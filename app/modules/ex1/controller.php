<?php
namespace modules\ex1;
class Controller extends \Controller {
	function get() {
		$model = loadModel("model");
		$this->f3->set('output', $model->returnOutput());
		echo render('index');
	}
}