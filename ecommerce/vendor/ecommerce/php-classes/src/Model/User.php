<?php

namespace Ecommerce\Model;

use \Ecommerce\DB\Sql;
use \Ecommerce\Model;


class User extends Model{ 

    const SESSION = "User";

    public static function login($login, $password){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));

        if (count($results) === 0) {

            throw new \Exception("Usuário inexistente ou senha inválida"); // COLOCAR A "\" NO EXCEPTION POIS NOS MEUS NAME SPACE NAO TENHO UM EXPECTION ESPECÍFICO
            
        }

        $data = $results[0];


        if (password_verify($password, $data["despassword"]) === true){
            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues(); // setar uma sessão ao fazer o login, para que possa ter acesso as outras páginas

            return $user;

        } else {
            
            throw new \Exception("Usuário inexistente ou senha inválida"); // COLOCAR A "\" NO EXCEPTION POIS NOS MEUS NAME SPACE NAO TENHO UM EXPECTION ESPECÍFICO

        }

    }


    public static function verifyLogin($inadmin = true){ // verificar se o usuário está logado 

        if (
            !isset($_SESSION[User::SESSION]) // SE EXISTE A SESSÃO
            ||
            !$_SESSION[User::SESSION] //SE A SESSÃO NAO ESTÁ VAZIA
            || 
            !(int)$_SESSION[User::SESSION]["iduser"] > 0 // se o ID do usuário setado na session, for > 0
            || 
            (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin )
        {
            header("Location: /admin/login");
            exit;
        }        
    }


    public static function logout(){
        $_SESSION[User::SESSION] = NULL;
    }
}

?>