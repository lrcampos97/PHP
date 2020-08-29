<?php

use \Ecommerce\PageAdmin;
use \Ecommerce\Model\User;	


/// PAINEL DE GERENCIAMNETO
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



// ESQUECI MINHA SENHA
$app->get('/admin/forgot', function(){

	$page = new Ecommerce\PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("forgot");	

});

// POST ESQUECI MINHA SENHA 
$app->post('/admin/forgot', function(){

	$user = User::getForgot($_POST["email"]);	

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


?>