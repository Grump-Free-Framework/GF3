<?php

class Controller {

	protected $f3;

	function beforeroute(){
		$this->HandlePreAndPostRouting('before');
	}

	function afterroute(){
		$this->HandlePreAndPostRouting('after');
	}

	function __construct() {

		$this->f3 = Base::instance();

		if($this->f3->get('redactDatabaseInfoOnCrashLogs')) {
			//redact this information from logs once
			$redact_config_values = ['db_host', 'db_password', 'db_database', 'db_username'];
			foreach($redact_config_values as $key) {
			    $this->f3->set($key, 'REDACTED');
			}
		}

		$this->f3->module = $this->f3->get("PARAMS.module") ?: $this->f3->get('defaultModule');
		$this->f3->method = $this->f3->get("PARAMS.method") ?: $this->f3->VERB;

	}

	public function render($view_file_name, $template = 'default') {
		if($template == "default") {
			$template = $this->f3->get('defaultTemplate');
		}
		$this->f3->set('content', "{$this->f3->module}/views/{$view_file_name}.htm");
		return \Template::instance()->render("../{$template}.htm");
	}

	public function model($model, $module_override = null) {
		$module = $module_override ?: $this->f3->module;
		$class = "\\modules\\{$module}\\models\\{$model}";
		return new $class();
	}

	private function HandlePreAndPostRouting($route) {
		$modules_path = $this->f3->get('UI');
		$moduleDirs = new DirectoryIterator($modules_path);
		foreach ($moduleDirs as $dir) {

			$path = "{$modules_path}/{$dir}";
			if(!$dir->isDot() && is_dir($path) && file_exists("{$path}/controller.php")) {
				if(file_exists($include_file = "{$path}/{$route}route_global.php")) {
					require_once $include_file;
				}
				if(file_exists($include_file = "{$path}/{$route}route.php") && $this->f3->get('module') == $dir) {
					require_once $include_file;
				}
			}

		}
	}

}
