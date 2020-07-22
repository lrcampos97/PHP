<?php

namespace Ecommerce\Model;

use \Ecommerce\DB\Sql;
use \Ecommerce\Model;


class User extends Model{ 

    const SESSION = "User";


    // CRIAR O MÉTODO CONTRUCTOR AQUI


    public  function __construct($iduser = 0){
        if ($iduser !== 0){
            $this->get($iduser);    
        }
    }


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

    public static function listAll(){
        
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_users usu INNER JOIN tb_persons per USING(idperson) ORDER BY per.desperson"); // USING -> função utilizada com o JOIN no Mysql quando o campo é o mesmo em ambas tabelas             
    }


    public static function logout(){
        $_SESSION[User::SESSION] = NULL;
    }


    public function save(){
        
        $sql = new Sql();


        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":desperson"=> $this->getdesperson(), // ESTES MÉTODOS SÃO GERADOS DE FORMA AUTOMATICA PELA CLASSE MODEL
            ":deslogin"=> $this->getdeslogin(),
            ":despassword"=> $this->getdespassword(),
            ":desemail"=> $this->getdesemail(),
            ":nrphone"=> $this->getnrphone(),
            ":inadmin"=> $this->getinadmin()
        ));


        $this->setData($results[0]);
    }

    public function get($iduser){


        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users usu INNER JOIN tb_persons per USING(idperson) WHERE usu.iduser = :iduser", array(
            ":iduser"=>$iduser
        ));

        $this->setData($results[0]);

    }

    public function update(){
        //sp_usersupdate_save

        $sql = new Sql();


        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"=> $this->getiduser(),
            ":desperson"=> $this->getdesperson(), // ESTES MÉTODOS SÃO GERADOS DE FORMA AUTOMATICA PELA CLASSE MODEL
            ":deslogin"=> $this->getdeslogin(),
            ":despassword"=> $this->getdespassword(),
            ":desemail"=> $this->getdesemail(),
            ":nrphone"=> $this->getnrphone(),
            ":inadmin"=> $this->getinadmin()
        ));


        $this->setData($results[0]);        
    }

    public function delete(){

        $sql = new Sql();

        $sql->query("CALL sp_users_delete(:iduser)",array(
            ":iduser"=>$this->getiduser()
        ));
    }   
}

?>