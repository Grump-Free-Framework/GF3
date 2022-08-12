<?php

class Controller {

	protected $f3;

	public $settings = [];

	public const REROUTE_BASE = 0;
	public const REROUTE_MODULE = 1;

	function beforeroute(){
		$this->handlePreAndPostRouting('before');
	}

	function afterroute(){
		$this->handlePreAndPostRouting('after');
	}

	function __construct() {

		$this->f3 = Base::instance();
		$this->f3->set('active_module', $this);

		$this->f3->module = $this->f3->get("PARAMS.module") ?: $this->f3->get('defaultModule');
		$this->f3->method = $this->f3->get("PARAMS.method") ?: $this->f3->VERB;

	}

	public function renderView($view_file_name, $template = 'default') {
		echo $this->render($view_file_name);
	}

	public function render($view_file_name, $template = 'default') {
		$f3 = $this->f3;
		$f3->set('module_settings', $this->settings);
		$template = $template == "default" ? $f3->get('defaultTemplate') : $template;
		$this->f3->set('content', "{$f3->module}/views/{$view_file_name}.htm");
		return \Template::instance()->render("../{$template}.htm");
	}

	public function loadModel($model, $module_override = null) {
		return $this->model($model, $module_override);
	}

	public function model($model, $module_override = null) {
		$module = $module_override ?: $this->f3->module;
		$class = "\\modules\\{$module}\\models\\{$model}";
		return new $class();
	}

	public function reroute($location, $mode = SELF::REROUTE_BASE) {
		$reroute = $mode === SELF::REROUTE_MODULE ? "{$this->f3->module}{$location}" : $location;
		$this->f3->reroute($reroute);
	}

	private function handlePreAndPostRouting($route) {
		$f3 = $this->f3;
		$modules_path = $this->f3->get('UI');
		$moduleDirs = new DirectoryIterator($modules_path);
		foreach ($moduleDirs as $dir) {

			$path = "{$modules_path}{$dir}";
			if(!$dir->isDot() && is_dir($path) && file_exists("{$path}/controller.php")) {
				if(file_exists($include_file = "{$path}/{$route}route_global.php")) {
					if (stripos(file_get_contents($include_file), '$this->') !== false) {
						throw new Exception('Using $this in beforeroute_global.php is not reliable');
					}
					require_once $include_file;
				}
				if($this->f3->get('module') == $dir) {
					if(file_exists($include_file = "{$path}/{$route}route.php")) {
						require_once $include_file;
					}
					if($route == 'before' && file_exists($settings_file = "{$path}/settings.json")) {
						$settings = json_decode(file_get_contents($settings_file));
						if (json_last_error() === JSON_ERROR_NONE) {
						   $this->settings = $settings;
					   } else {
						   throw new Exception('Settings file is not valid JSON.');
					   }
					}
				}
			}

		}
	}

}
