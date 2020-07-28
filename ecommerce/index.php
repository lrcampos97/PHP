<?php 

session_start(); // iniciar uma sessão

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Ecommerce\Page;
use \Ecommerce\PageAdmin;
use \Ecommerce\Model\User;	
use \Ecommerce\Model\Category;	

$app = new Slim();

$app->config('debug', true);


// INDEX DO E-COMMERCE
$app->get('/', function() {

	$page = new Ecommerce\Page();

	$page->setTpl("index");
	

});

// PAINEL DE GERENCIAMNETO
$app->get('/admin', function() {

	User::verifyLogin();

	$page = new Ecommerce\PageAdmin();

	$page->setTpl("index");
	
});


// PAGE PARA LOGIN 
$app->get('/admin/login', function() {

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("login");	
});


//POST FORM LOGIN
$app->post('/admin/login', function() {

	User::login($_POST["login"], $_POST["password"]);
	
	header("Location: /admin"); // navegar para a página de administrador
	exit;
});

$app->get('/admin/logout', function(){

	User::logout();

	header("Location: /admin/login");

	exit;
});

$app->get('/admin/users', function(){

	User::verifyLogin();

	$users = User::listAll();
	
	$page = new PageAdmin();

	$page->setTpl('users', array(
		"users"=> $users
	));

});

// CRIAR NOVO USUÁRIO
$app->get('/admin/users/create', function(){

	User::verifyLogin();
	
	$page = new PageAdmin();

	$page->setTpl('users-create');

});

// ADD USUARIO
$app->post('/admin/users/create', function(){
	
	User::verifyLogin();
		
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;


	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

		"cost"=>12

	]);

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

// CUIDAR A ORDEM DESTA ROTA, PELO FATO DELA ESTAR COM O /DELETE NO FINAL

$app->get('/admin/users/:iduser/delete', function($iduser){
	
	User::verifyLogin();	

	$user = new User((int)$iduser);
	
	$user->delete();

	header("Location: /admin/users");
	exit;	

});


// USERS UPDATE
$app->get('/admin/users/:iduser', function($iduser){

	User::verifyLogin();

	$user = new User((int)$iduser);
	
	$page = new PageAdmin();

	$page->setTpl('users-update', array(
		"user"=> $user->getValues()
	));

});

// SALVAR USUÁRIO
$app->post('/admin/users/:iduser', function($iduser){
	
	User::verifyLogin();
	
	$user = new User((int)$iduser);

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});

// ESQUECI MINHA SENHA
$app->get('/forgot', function(){

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("forgot");	

});

// POST ESQUECI MINHA SENHA 
$app->post('/forgot', function(){

	$user = User::getForgot($_POST["email"], false);	

	header("Location: /admin/forgot/sent");
	exit;

});

// EMAIL ENVIADO
$app->get('/admin/forgot/sent', function(){

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("forgot-sent");	

});

// RESETAR SENHAR
$app->get('/admin/forgot/reset', function(){


	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);
	
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));	
});

$app->post("/admin/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]); // verificar novamente o código	

	User::setForgotUsed($forgot["idrecovery"]); // setar na tabela a data que foi utilizado o código de recuperação

	$user = new User($forgot["iduser"]);


	$newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT, [ //encriptografar senha

		"cost"=>12 // quanto mais mais seguro, porém também requer mais processamento

	]);

	$user->setPassword($newPassword);

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);
	
	$page->setTpl("forgot-reset-success");		
});


// GET CATEGORIAS
$app->get("/admin/categories", function(){

	User::verifyLogin(); // VERIFICAR SEMPRE O USUÁRIO

	$categories = Category::listAll();

	$page = new PageAdmin();
	
	$page->setTpl("categories",[
		"categories"=>$categories
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

$app->run();


 ?>