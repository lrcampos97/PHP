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

//CARREGAR PRODUTOS DA CATEGORIA
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

//DETALHES DO PRODUTO
$app->get('/products/:desurl', function($desurl){

	$product = new Product();

	$product->getFromUrl($desurl);

	$page = new Ecommerce\Page();

	$page->setTpl("product-detail",[
		"product"=>$product->getValues(),
		"categories"=>$product->getCategories()
	]);

});

// CARRINHO DE COMPRA
$app->get('/cart', function(){

	$cart = Cart::getFromSession();

	$page = new Ecommerce\Page();

	$page->setTpl("cart",[
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts()
	]);

});

// ADICIONAR PRODUTO NO CARRINHO
$app->get('/cart/:idproduct/add', function($idproduct){

	$product = new Product($idproduct);

	$cart = Cart::getFromSession();

	$qtd = (isset($_GET["qtd"])) ? (int)$_GET["qtd"] : 1;
	

	for ($i=0; $i < $qtd ; $i++) { 
		
		$cart->addProduct($product);

	}	

	header("Location: /cart");
	exit;
});

// REMOVER UMA ÚNICA UNIDADE DO PRODUTO
$app->get('/cart/:idproduct/minus', function($idproduct){

	$product = new Product($idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");
	exit;
});

// REMOVER PRODUTO DO CARRINHO
$app->get('/cart/:idproduct/remove', function($idproduct){

	$product = new Product($idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	header("Location: /cart");
	exit;
})

?>