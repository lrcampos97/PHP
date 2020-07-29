<?php

use \Ecommerce\Page;

// INDEX DO E-COMMERCE
$app->get('/', function() {

	$page = new Ecommerce\Page();

	$page->setTpl("index");
	
});

?>