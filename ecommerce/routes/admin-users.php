<?php

use \Ecommerce\PageAdmin;
use \Ecommerce\Model\User;	

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

?>
