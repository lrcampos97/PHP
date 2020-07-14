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

$app->run();


 ?>