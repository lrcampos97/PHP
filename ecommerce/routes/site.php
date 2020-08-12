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

	$address = new Address();

	$cart = Cart::getFromSession();	

	if(!isset($_GET["zipcode"])){
		$_GET["zipcode"] = $cart->getdeszipcode();
	}
		
	if(isset($_GET["zipcode"])){
		$address->loadFromCEP($_GET["zipcode"]);

		$cart->setdeszipcode($_GET["zipcode"]);

		$cart->save();

		$cart->getCalculateTotal();
	}

	if (!$address->getdesaddress()) $address->setdesaddress("");
	if (!$address->getdescomplement()) $address->setdescomplement("");
	if (!$address->getdescity()) $address->setdescity("");
	if (!$address->getdesstate()) $address->setdesstate("");
	if (!$address->getdescountry()) $address->setdescountry("");
	if (!$address->getdesdistrict()) $address->setdesdistrict("");

	$page = new Ecommerce\Page();

	$page->setTpl("checkout",[
		"cart"=>$cart->getValues(),
		"address"=>$address->getValues(),
		"products"=>$cart->getProducts(),
		"error"=>$address->getMsgError()
	]);
});

$app->post('/checkout', function(){

	User::verifyLogin(false);
	
	$user = User::getFromSession();


	if (!isset($_POST["zipcode"]) || $_POST["zipcode"] === "" ){
		Address::setMsgError("Informe o CEP.");
		
		header("Location: /checkout");
		exit;
	};

	if (!isset($_POST["desaddress"]) || $_POST["desaddress"] === "" ){
		Address::setMsgError("Informe o Endereço.");
		
		header("Location: /checkout");
		exit;
	};	

	if (!isset($_POST["desdistrict"]) || $_POST["desdistrict"] === "" ){
		Address::setMsgError("Informe o Bairro.");
		
		header("Location: /checkout");
		exit;
	};	
	
	if (!isset($_POST["descity"]) || $_POST["descity"] === "" ){
		Address::setMsgError("Informe a Cidade.");
		
		header("Location: /checkout");
		exit;
	};		

	if (!isset($_POST["desstate"]) || $_POST["desstate"] === "" ){
		Address::setMsgError("Informe o Estado.");
		
		header("Location: /checkout");
		exit;
	};	
	
	if (!isset($_POST["descountry"]) || $_POST["descountry"] === "" ){
		Address::setMsgError("Informe o País.");
		
		header("Location: /checkout");
		exit;
	};	

	$address = new Address();

	$_POST["deszipcode"] = $_POST["zipcode"];
	$_POST["idperson"] = $user->getidperson();

	$address->setData($_POST);

	$address->save();

	header("Location: /order");
	exit;
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
	

		if (User::getLoginExists($_POST["email"])){

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
	
	$_SESSION["registerValues"] = ["name"=>"", "email"=>"", "phone"=>""];

	header("Location: /checkout");
	exit;
});



// ESQUECI MINHA SENHA
$app->get('/forgot', function(){

	$page = new Ecommerce\Page();

	$page->setTpl("forgot");	

});

// POST ESQUECI MINHA SENHA 
$app->post('/forgot', function(){

	$user = User::getForgot($_POST["email"], false);	

	header("Location: /forgot/sent");
	exit;

});

// EMAIL ENVIADO
$app->get('/forgot/sent', function(){

	$page = new Ecommerce\Page();

	$page->setTpl("forgot-sent");	

});

// RESETAR SENHAR
$app->get('/forgot/reset', function(){


	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new Ecommerce\Page();
	
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));	
});


$app->post("/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]); // verificar novamente o código	

	User::setForgotUsed($forgot["idrecovery"]); // setar na tabela a data que foi utilizado o código de recuperação

	$user = new User($forgot["iduser"]);

	$newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT, [ //encriptografar senha

		"cost"=>12 // quanto mais mais seguro, porém também requer mais processamento

	]);

	$user->setPassword($newPassword);	

	$page = new Ecommerce\Page();
	
	$page->setTpl("forgot-reset-success");		
});


// PERFIL
$app->get('/profile', function(){

	User::verifyLogin(false);
	
	
	$user = User::getFromSession();

	$page = new Ecommerce\Page();
	
	$page->setTpl("profile",[
		"user"=>$user->getValues(),
		"profileMsg"=>User::getSuccess(),
		"profileError"=>User::getMsgError()
	]);	
});




// PERFIL SALVAR
$app->post('/profile', function(){

	User::verifyLogin(false);		


	if (!isset($_POST["desperson"]) || $_POST["desperson"] === ""){
		
		User::setMsgError("Preencha seu nome.");	
		
		header("Location: /profile");
		exit;
	}

	if (!isset($_POST["desemail"]) || $_POST["desemail"] === ""){
		User::setMsgError("Preencha seu e-mail.");	
		header("Location: /profile");
		exit;
	}

	$user = User::getFromSession();

	if ($_POST["desemail"] !== $user->getdesemail()){
		if (User::getLoginExists($_POST["desemail"])) {

			User::setMsgError("Este endereço de e-mail já está sendo usado.");
			header("Location: /profile");
			exit;
		}

	}	

	// PARA EVITAR INJECTION
	$_POST["inadmin"] = $user->getinadmin();
	$_POST["despassword"] = $user->getdespassword();
	$_POST["deslogin"] = $_POST["desemail"];

	$user->setData($_POST);

	$user->update();

	User::setSuccess("Dados salvos com sucesso.");

	header("Location: /profile");
	exit;
});


?>