<?php

use \Ecommerce\PageAdmin;
use \Ecommerce\Model\User;	
use \Ecommerce\Model\Product;	

// LISTAR PRODUTOS
$app->get("/admin/products", function(){

    User::verifyLogin();

    $products = Product::listAll();

    $page = new Ecommerce\PageAdmin();

	$page->setTpl("products",[
        "products"=>$products
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


$app->get("/admin/products/:idproduct/delete", function($idproduct){

	User::verifyLogin();

	$product = new Product($idproduct);

	$product->delete();

	header('Location: /admin/products');
	exit;

});


?>