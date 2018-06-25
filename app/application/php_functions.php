<?php

function render($content, $template = 'main.htm') {
	$base = Base::instance();
	$base->set('content', $content);
    return \Template::instance()->render("../templates/".$template);
}