<?php

$f3->route('GET /@module','c_{@module}->index');
$f3->route('GET /@module/@method','c_{@module}->@method');

$f3->route('POST /@module','c_{@module}->post');
$f3->route('POST /@module/@method','c_{@module}->{@method}_post');