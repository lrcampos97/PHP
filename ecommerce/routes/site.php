<?php

use \Ecommerce\Page;
use \Ecommerce\Model\Product;
use \Ecommerce\Model\Category;
use \Ecommerce\Model\Cart;

// INDEX DO E-COMMERCE
$app->get('/', function() {

	$products = Product::listAll();

	$page = new Ecommerce\Page();

	$page->setTpl("index",[
		"products"=>Product::checkList($products)
	]);
	
});


$app->get('/categories/:idcategory',function($idcategory){

	$pageNumber = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
	
	$category = new Category($idcategory);
		
	$pagination = $category->getProductsPage($pageNumber); // se eu quiser que o usuário escolha qtos itens mostrar por página, passar o segundo parâmetro

	$pages = [];

	for ($i=1; $i <= $pagination["pages"]; $i++) { 
		array_push($pages,[
			'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
			'page'=>$i
		]);
	}


	$page = new Ecommerce\Page();

	$page->setTpl("category",[
		"category"=>$category->getValues(),
		"products"=>$pagination["data"],
		"pages"=>$pages
	]);

});


$app->get('/products/:desurl', function($desurl){

	$product = new Product();

	$product->getFromUrl($desurl);

	$page = new Ecommerce\Page();

	$page->setTpl("product-detail",[
		"product"=>$product->getValues(),
		"categories"=>$product->getCategories()
	]);

});

$app->get('/cart', function(){

	$cart = Cart::getFromSession();

	$page = new Ecommerce\Page();

	$page->setTpl("cart");

})


?>