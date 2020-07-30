<?php

use \Ecommerce\Page;
use \Ecommerce\Model\Product;
use \Ecommerce\Model\Category;

// INDEX DO E-COMMERCE
$app->get('/', function() {

	$products = Product::listAll();

	$page = new Ecommerce\Page();

	$page->setTpl("index",[
		"products"=>Product::checkList($products)
	]);
	
});


$app->get('/categories/:idcategory',function($idcategory){
	
	$category = new Category($idcategory);
	
	$page = new Ecommerce\Page();

	$page->setTpl("category",[
		"category"=>$category->getValues(),
		"products"=>Product::checkList($category->getProducts())
	]);

});


?>