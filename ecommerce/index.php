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

require_once('routes/site.php');
require_once('routes/admin.php');
require_once('routes/admin-users.php');
require_once('routes/admin-categories.php');
require_once('routes/admin-products.php');


$app->run();


 ?>