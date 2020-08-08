<?php

use \Ecommerce\Page;
use \Ecommerce\Model\Product;
use \Ecommerce\Model\Category;
use \Ecommerce\Model\Cart;
use \Ecommerce\Model\Address;
use \Ecommerce\Model\User;

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

	$values = $cart->getValues();
	$products = $cart->getProducts();

	$page->setTpl("cart",[
		"cart"=>$values,
		"products"=>$products,
		"error"=>Cart::getMsgError()
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
});

// CÁLCULO FRETE
$app->post('/cart/freight', function(){

	$cart = Cart::getFromSession();

	$cart->setFreight($_POST["zipcode"]);

	header("Location: /cart");
	exit;	
});


// CHECKOUT USUARIO SITE
$app->get('/checkout', function(){

	User::verifyLogin(false);

	$page = new Ecommerce\Page();

	$cart = Cart::getFromSession();

	$address = new Address();

	$page->setTpl("checkout",[
		"cart"=>$cart->getValues(),
		"address"=>$address->getValues()
	]);
});


// LOGIN DO SITE
$app->get('/login', function(){
	$page = new Ecommerce\Page();

	$page->setTpl("login",[
		"error"=>User::getMsgError(),
		"errorRegister"=>User::getErrorRegister(),
		"registerValues"=>(isset($_SESSION["registerValues"])) ? $_SESSION["registerValues"] : ["name"=>"", "email"=>"", "phone"=>""]
	]);
});


// SALVAR LOGIN
$app->post('/login', function(){

	try {
	
		User::login($_POST["login"], $_POST["password"]);

	} catch (Exception $e) {

		User::setMsgError($e->getMessage());

	}

	header("Location: /checkout");
	exit;
});


// LOGOUT
$app->get('/logout', function(){

	User::logout();

	header("Location: /login");
	exit;
});


// NOVO USUÁRIO SITE
$app->post('/register', function(){

	$_SESSION["registerValues"] = $_POST; // UTILIZADO PARA SALVAR OS DADOS NA SESSÃO QUANDO OCORRER ALGUM ERRO NA VALIDAÇÃO DO FORMULÁRIO,


	try {

		if (!isset($_POST["name"]) || ($_POST["name"] == "")){

			User::setErrorRegister("Preencha o seu nome.");

			header("Location: /login");
			exit;
		}

		if (!isset($_POST["email"]) || ($_POST["email"] == "")){

			User::setErrorRegister("Preencha o seu e-mail.");

			header("Location: /login");
			exit;
		}
		
		if (!isset($_POST["password"]) || ($_POST["password"] == "")){

			User::setErrorRegister("Preencha a sua senha.");

			header("Location: /login");
			exit;
		}		
	

		if (User::checkLogin($_POST["email"])){

			User::setErrorRegister("Este endereço de e-mail já está sendo utilizado.");

			header("Location: /login");
			exit;			
		}

		$user = new User();

		$user->setData([
			"inadmin"=>0,
			"deslogin"=>$_POST["email"],
			"desperson"=>$_POST["name"],
			"desemail"=>$_POST["email"],
			"despassword"=>$_POST["password"],
			"nrphone"=>$_POST["phone"]
		]);

		$user->save();

		User::login($_POST["email"], $_POST["password"]);

	} catch (Exception $e) {

		User::setMsgError($e->getMessage());

	}
	
	header("Location: /checkout");
	exit;
});

?>