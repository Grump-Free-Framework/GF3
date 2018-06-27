<?php

function render($content, $template = 'templates/main') {
	$base = Base::instance();
	$module = $base->module;
	$base->set('content', "$module/views/$content.htm");
    return \Template::instance()->render("../$template.htm");
}
function loadModel($model) {
	$base = Base::instance();
	$class = "\\modules\\{$base->module}\\models\\{$model}";
	$model = new $class();
	return $model;
}