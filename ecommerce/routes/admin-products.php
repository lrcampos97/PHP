<?php

use \Ecommerce\PageAdmin;
use \Ecommerce\Model\User;	
use \Ecommerce\Model\Product;	


$app->get("/admin/products/:idproduct/delete", function($idproduct){

	User::verifyLogin();

	$product = new Product($idproduct);

	$product->delete();

	header('Location: /admin/products');
	exit;

});

// LISTAR PRODUTOS
$app->get("/admin/products", function(){

	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : "";
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if ($search != '') {

		$pagination = Product::getPageSearch($search, $page);

	} else {

		$pagination = Product::getPage($page);

	}

	$pages = [];

	for ($x = 0; $x < $pagination['pages']; $x++)
	{

		array_push($pages, [
			'href'=>'/admin/products?'.http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1
		]);

	}

	$products = Product::listAll();

	$page = new PageAdmin();

	$page->setTpl("products", [
		"products"=>$pagination['data'],
		"search"=>$search,
		"pages"=>$pages
	]);
});

// CRIAR PRODUTO
$app->get("/admin/products/create", function(){

    User::verifyLogin();

    $page = new Ecommerce\PageAdmin();

	$page->setTpl("products-create");	
});

// SALVAR PRODUTO
$app->post("/admin/products/create", function(){

    User::verifyLogin();

    $product = new Product();

    $product->setData($_POST);

    $product->save();

    header("Location: /admin/products");
    exit;        
});


// EDITAR PRODUTO
$app->get("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

    $product = new Product($idproduct);

    $page = new Ecommerce\PageAdmin();

	$page->setTpl("products-update",[
        "product"=>$product->getValues()
    ]);	
});


// SALVAR PRODUTO EDITADO
$app->post("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

    $product = new Product($idproduct);

    $product->setData($_POST);

    $product->save();
        
    $product->setPhoto($_FILES["file"]);    

    header("Location: /admin/products");
    exit;        
});





?>