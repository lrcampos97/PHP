<?php

use \Ecommerce\PageAdmin;
use \Ecommerce\Model\User;
use \Ecommerce\Model\Category;	
use \Ecommerce\Model\Product;	

// GET CATEGORIAS
$app->get("/admin/categories", function(){

	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : "";
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if ($search != '') {

		$pagination = Category::getPageSearch($search, $page);

	} else {

		$pagination = Category::getPage($page);

	}

	$pages = [];

	for ($x = 0; $x < $pagination['pages']; $x++)
	{

		array_push($pages, [
			'href'=>'/admin/categories?'.http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1
		]);

	}

	$page = new PageAdmin();

	$page->setTpl("categories", [
		"categories"=>$pagination['data'],
		"search"=>$search,
		"pages"=>$pages
	]);		

});


// ADICIONAR CATEGORIAS
$app->get("/admin/categories/create", function(){

	User::verifyLogin(); // VERIFICAR SEMPRE O USUÁRIO

	$page = new PageAdmin();
	
	$page->setTpl("categories-create");		

});


// SALVAR CATEGORIA 
$app->post('/admin/categories/create', function(){

	User::verifyLogin(); // VERIFICAR SEMPRE O USUÁRIO

	$category = new Category();

	$category->setData($_POST);

	$category->save();
	
	header("Location: /admin/categories");
	exit;

});

// DELETAR CATEGORIA 
$app->get('/admin/categories/:idcategory/delete', function($idcategory){

	User::verifyLogin(); // VERIFICAR SEMPRE O USUÁRIO

	$category = new Category($idcategory);
	
	$category->delete();
	
	header("Location: /admin/categories");
	exit;
});

// EDITAR CATEGORIA 
$app->get('/admin/categories/:idcategory', function($idcategory){

	User::verifyLogin(); // VERIFICAR SEMPRE O USUÁRIO

	$category = new Category($idcategory);
	
	$page = new PageAdmin();
	
	$page->setTpl("categories-update",[
		"category"=>$category->getValues()
	]);				
});

// SALVAR EDIÇÃO CATEGORIA 
$app->post('/admin/categories/:idcategory', function($idcategory){

	User::verifyLogin(); // VERIFICAR SEMPRE O USUÁRIO
	
	$category = new Category($idcategory);
				
	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;
});

$app->get('/admin/categories/:idcategory/products', function($idcategory){

	User::verifyLogin(); // VERIFICAR SEMPRE O USUÁRIO

	$category = new Category($idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products",[
		"category"=>$category->getValues(),
		"productsNotRelated"=>$category->getProducts(false),
		"productsRelated"=>$category->getProducts()
	]);


});

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category($idcategory);

	$product = new Product($idproduct);

	$category->addProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;

});

$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category($idcategory);

	$product = new Product($idproduct);

	$category->removeProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;

});

?>