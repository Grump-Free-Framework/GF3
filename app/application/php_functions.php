<?php

function render($content, $template = 'templates/main') {
	$base = Base::instance();
	$module = $base->module;
	$base->set('content', "$module/views/$content.htm");
    return \Template::instance()->render("../$template.htm");
}
function loadModel($model, $module_override = null) {
	$module = $module_override === null ? Base::instance()->module : $module_override;
	$class = "\\modules\\{$module}\\models\\{$model}";
	return new $class();
}
