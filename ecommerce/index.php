<?php 

session_start(); // iniciar uma sessão

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Ecommerce\Page;
use \Ecommerce\PageAdmin;
use \Ecommerce\Model\User;	

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


$app->get('/admin/forgot', function(){

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("forgot");	

});

$app->post('/admin/forgot', function(){

	$user = User::getForgot($_POST["email"]);	

	header("Location: /admin/forgot/sent");
	exit;

});

$app->get('/admin/forgot/sent', function(){

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("forgot-reset-success");	

});

$app->run();


 ?>