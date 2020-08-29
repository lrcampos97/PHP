<?php

use \Ecommerce\Page;
use \Ecommerce\Model\Product;
use \Ecommerce\Model\Category;
use \Ecommerce\Model\Cart;
use \Ecommerce\Model\Address;
use \Ecommerce\Model\User;
use \Ecommerce\Model\Order;
use \Ecommerce\Model\OrderStatus;

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

	User::verifyLogin(false);

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


// SALVAR CHECKOUT
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

	$cart = Cart::getFromSession();

	$cart->getCalculateTotal();

	$order = new Order();

	$order->setData([
		'idcart'=>$cart->getidcart(),
		'idaddress'=>$address->getidaddress(),
		'iduser'=>$user->getiduser(),
		'idstatus'=>OrderStatus::EM_ABERTO,
		'vltotal'=>$cart->getvltotal()
	]);	

	$order->save();

	header("Location: /order/".$order->getidorder());
	exit;
});



// ORDER
$app->get('/order/:idorder', function($idorder){

	User::verifyLogin(false);

	$page = new Ecommerce\Page();

	$order = new Order($idorder);

	$page->setTpl("payment",[
		"order"=>$order->getvalues()
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


$app->get('/profile-orders', function(){
	
	User::verifyLogin(false);

	$user = User::getFromSession();

	$page = new Page();

	$page->setTpl('profile-orders',[
		"orders"=>$user->getorders()
	]);
});


$app->get('/profile/orders/:idorder', function($idorder){
	
	User::verifyLogin(false);

	$order = new Order($idorder);

	$cart = new Cart($order->getidcart());

	$page = new Page();

	$page->setTpl('profile-orders-detail',[
		"order"=>$order->getValues(),
		"products"=>$cart->getProducts(),
		"cart"=>$cart->getValues()
	]);
});


// ALTERAR SENHA 
$app->get('/profile/change-password', function(){
	
	User::verifyLogin(false);

	$page = new Page();

	$page->setTpl('profile-change-password',[
		"changePassError"=>User::getMsgError(),
		"changePassSuccess"=>User::getSuccess()
	]);
});

// ALTERAR SENHA POST 
$app->post('/profile/change-password', function(){
	
	User::verifyLogin(false);

	if (!isset($_POST['current_pass']) || $_POST['current_pass'] === '') {

		User::setMsgError("Digite a senha atual.");
		header("Location: /profile/change-password");
		exit;

	}

	if (!isset($_POST['new_pass']) || $_POST['new_pass'] === '') {

		User::setMsgError("Digite a nova senha.");
		header("Location: /profile/change-password");
		exit;

	}

	if (!isset($_POST['new_pass_confirm']) || $_POST['new_pass_confirm'] === '') {

		User::setMsgError("Confirme a nova senha.");
		header("Location: /profile/change-password");
		exit;

	}	

	if ($_POST['current_pass'] === $_POST['new_pass']) {

		User::setMsgError("A sua nova senha deve ser diferente da atual.");
		header("Location: /profile/change-password");
		exit;		

	}	

	$user = User::getFromSession();

	if (!password_verify($_POST['current_pass'], $user->getdespassword())) {

		User::setMsgError("A senha está inválida.");
		header("Location: /profile/change-password");
		exit;			

	}

	$user->setdespassword($_POST['new_pass']);

	$user->update();
	
	User::setSuccess("Senha alterada com sucesso.");

	header("Location: /profile/change-password");
	exit;
});


// BOLETO
$app->get('/boleto/:idorder', function($idorder){

	User::verifyLogin(false);	

	$order = new Order($idorder);

	// DADOS DO BOLETO PARA O SEU CLIENTE
	$dias_de_prazo_para_pagamento = 10;
	$taxa_boleto = 5.00;
	$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
	$valor_cobrado = 200; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
	$valor_cobrado = str_replace(",", ".",$valor_cobrado);
	$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

	$dadosboleto["nosso_numero"] = $order->getidorder();  // Nosso numero - REGRA: Máximo de 8 caracteres!
	$dadosboleto["numero_documento"] = $order->getidorder();	// Num do pedido ou nosso numero
	$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
	$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

	// DADOS DO SEU CLIENTE
	$dadosboleto["sacado"] = $order->getdesperson();
	$dadosboleto["endereco1"] = $order->getdesaddress() . " " . $order->getdesdistrict();
	$dadosboleto["endereco2"] = $order->getdescity() . " - " . $order->getdesstate() . " - " . $order->getdescountry() . " -  CEP: " . $order->getdeszipcode();

	// INFORMACOES PARA O CLIENTE
	$dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja E-commerce Store";
	$dadosboleto["demonstrativo2"] = "Taxa bancária - R$ 0,00";
	$dadosboleto["demonstrativo3"] = "";
	$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
	$dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
	$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: contato@ecommercestore.com.br";
	$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto Loja E-commerce Store - www.ecommercestore.com.br";

	// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
	$dadosboleto["quantidade"] = "";
	$dadosboleto["valor_unitario"] = "";
	$dadosboleto["aceite"] = "";		
	$dadosboleto["especie"] = "R$";
	$dadosboleto["especie_doc"] = "";


	// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


	// DADOS DA SUA CONTA - ITAÚ
	$dadosboleto["agencia"] = "1690"; // Num da agencia, sem digito
	$dadosboleto["conta"] = "48781";	// Num da conta, sem digito
	$dadosboleto["conta_dv"] = "2"; 	// Digito do Num da conta

	// DADOS PERSONALIZADOS - ITAÚ
	$dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

	// SEUS DADOS
	$dadosboleto["identificacao"] = "Ecommerce Store";
	$dadosboleto["cpf_cnpj"] = "59.748.799/0001-41";
	$dadosboleto["endereco"] = "Rua Ademar Saraiva Leão, 234 - Alvarenga, 09853-120";
	$dadosboleto["cidade_uf"] = "São Bernardo do Campo - SP";
	$dadosboleto["cedente"] = "ECOMMERCE STORE LTDA";

	// NÃO ALTERAR!
	$path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "boletophp" . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR;

	require_once($path . "funcoes_itau.php");
	require_once($path . "layout_itau.php");
});

?>