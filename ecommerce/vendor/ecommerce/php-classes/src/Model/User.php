<?php

namespace Ecommerce\Model;

use \Ecommerce\DB\Sql;
use \Ecommerce\Model;
use \Ecommerce\Mailer;


class User extends Model{ 

    const SESSION = "User";
    const SECRET = "HcodePhp7_Secret";
    const SECRET_IV = "HcodePhp7_Secret_IV";
    const ERROR = "UserError";
    const ERROR_REGISTER = "UserErrorRegister";
    const SUCCESS = "UserSuccess";


    // CRIAR O MÉTODO CONTRUCTOR AQUI


    public  function __construct($iduser = 0){
        if ($iduser !== 0){
            $this->get($iduser);    
        }
    }

    public static function getFromSession(){

		$user = new User();

		if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0) {

			$user->setData($_SESSION[User::SESSION]);

		}

		return $user;        
    }


	public static function checkLogin($inadmin = true)
	{

		if (
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
		) {			
			return false;

		} else {

			if ($inadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true) {

				return true;

			} else if ($inadmin === false) {

				return true;

			} else {

				return false;

			}

		}

	}    

    public static function login($login, $password){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users usu INNER JOIN tb_persons per USING(idperson) WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));

        if (count($results) === 0) {

            throw new \Exception("Usuário inexistente ou senha inválida"); // COLOCAR A "\" NO EXCEPTION POIS NOS MEUS NAME SPACE NAO TENHO UM EXPECTION ESPECÍFICO
            
        }

        $data = $results[0];    
                                   
        if (password_verify($password, $data["despassword"]) === true){
            $user = new User();            

            $data["desperson"] = utf8_encode($data["desperson"]);

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues(); // setar uma sessão ao fazer o login, para que possa ter acesso as outras páginas

            return $user;

        } else {            
            throw new \Exception("Usuário inexistente ou senha inválida"); // COLOCAR A "\" NO EXCEPTION POIS NOS MEUS NAME SPACE NAO TENHO UM EXPECTION ESPECÍFICO

        }

    }


    public static function verifyLogin($inadmin = true){ // verificar se o usuário está logado 
        
		if (!User::checkLogin($inadmin)) {

			if ($inadmin) {
				header("Location: /admin/login");
			} else {
				header("Location: /login");
			}
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

    public static function getForgot($email, $inadmin = true){

        $sql = new Sql();

        $results = $sql->select("
            SELECT * 
            FROM tb_persons per
            INNER JOIN  tb_users usu USING(idperson)
            WHERE  per.desemail = :email
        ",array(
                "email"=>$email
        ));

        if (count($results) === 0){

            throw new Exception("Não foi possível recuperar a senha.");        

        } else {

            $data = $results[0];

            $resultsRecovery = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
                ":iduser"=> $data["iduser"],
                ":desip"=> $_SERVER["REMOTE_ADDR"]
            ));

            if (count($resultsRecovery[0]) == 0){
                throw new Exception("Não foi possível recuperar a senha.");     
            } else {

                $dataRecovery = $resultsRecovery[0];

                $code = openssl_encrypt($dataRecovery['idrecovery'], 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV)); // CRIPTOGRAFIA SEGURA

                $code = base64_encode($code);

                
				if ($inadmin === true) {

					$link = "http://www.ecommerce.com.br/admin/forgot/reset?code=$code";

				} else {

					$link = "http://www.ecommerce.com.br/forgot/reset?code=$code";
					
				}

                //ENVIAR EMAIL PARA REDEFINIR SENHA

                $mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir senha do E-commerce Store","forgot",array(
                    "name"=>$data["desperson"],
                    "link"=>$link
                ));


                $mailer->send();

                return $data;

            }

        }


    }


    public static function validForgotDecrypt($code){

		$code = base64_decode($code);

        $idrecovery = openssl_decrypt($code, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));
                
        $sql = new Sql();

        $results = $sql->select(
            "SELECT *
			 FROM tb_userspasswordsrecoveries a
			 INNER JOIN tb_users b USING(iduser)
			 INNER JOIN tb_persons c USING(idperson)
			 WHERE
				a.idrecovery = :idrecovery
				AND
				a.dtrecovery IS NULL
				AND
                DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW()
        ", array(
            ":idrecovery"=> $idrecovery
        ));

        if (count($results) === 0){
            throw new \Exception("Não foi possível recuperar a senha");            
        } else {

            return $results[0];

        }

    }

    public static function setForgotUsed($idrecovery){

        $sql = new Sql();

        $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
            ":idrecovery"=> $idrecovery
        ));
        
    }

    public function save(){
        
        $sql = new Sql();


        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":desperson"=> utf8_decode($this->getdesperson()), // ESTES MÉTODOS SÃO GERADOS DE FORMA AUTOMATICA PELA CLASSE MODEL
            ":deslogin"=> $this->getdeslogin(),
            ":despassword"=>User::getPasswordHash($this->getdespassword()),
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

        $data = $results[0];

        $data["desperson"] = utf8_encode($data["desperson"]);        

        $this->setData($data);

    }

    public function update(){

        $sql = new Sql();


        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"=> $this->getiduser(),// ESTES MÉTODOS SÃO GERADOS DE FORMA AUTOMATICA PELA CLASSE MODEL
            ":desperson"=> utf8_decode($this->getdesperson()), 
            ":deslogin"=> $this->getdeslogin(),
            ":despassword"=>User::getPasswordHash($this->getdespassword()),
            ":desemail"=> $this->getdesemail(),
            ":nrphone"=> $this->getnrphone(),
            ":inadmin"=> $this->getinadmin()
        ));


        $this->setData($results[0]);
        
        $_SESSION[User::SESSION] = $this->getValues(); // atualizar usuário da sessão
    }

    public function delete(){

        $sql = new Sql();

        $sql->query("CALL sp_users_delete(:iduser)",array(
            ":iduser"=>$this->getiduser()
        ));
    }   

    public function setPassword($newPassword){

		$sql = new Sql();

		$sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
			":password"=>$newPassword,
			":iduser"=>$this->getiduser()
		));        
    }

    public function getOrders(){
		$sql = new Sql();

		$results = $sql->select("
			SELECT * 
			FROM tb_orders a 
			INNER JOIN tb_ordersstatus b USING(idstatus) 
			INNER JOIN tb_carts c USING(idcart)
			INNER JOIN tb_users d ON d.iduser = a.iduser
			INNER JOIN tb_addresses e USING(idaddress)
			INNER JOIN tb_persons f ON f.idperson = d.idperson
			WHERE a.iduser = :iduser
		", [
			':iduser'=>$this->getiduser()
		]);

		return $results;
    }


	public static function getMsgError()
	{

		$msg = (isset($_SESSION[User::ERROR])) ? $_SESSION[User::ERROR] : "";

		User::clearMsgError();

		return $msg;

	}

	public static function setMsgError($msg){

		$_SESSION[User::ERROR] = $msg;

	}

	public static function clearMsgError()
	{

		$_SESSION[User::ERROR] = NULL;

    }	    

    
	public static function getErrorRegister()
	{

		$msg = (isset($_SESSION[User::ERROR_REGISTER])) ? $_SESSION[User::ERROR_REGISTER] : "";

		User::clearErrorRegister();

		return $msg;

	}

	public static function setErrorRegister($msg){

		$_SESSION[User::ERROR_REGISTER] = $msg;

	}

	public static function clearErrorRegister()
	{

		$_SESSION[User::ERROR_REGISTER] = NULL;

    }    

    
    public static function getPasswordHash($password){
        
        return password_hash($password, PASSWORD_DEFAULT, [
            "cost"=>12
        ]);

    }


    public static function getLoginExists($login){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :deslogin",[
            ":deslogin"=>$login
        ]);

        return (count($results) > 0);

    }



	public static function getSuccess()
	{

		$msg = (isset($_SESSION[User::SUCCESS])) ? $_SESSION[User::SUCCESS] : "";

		User::clearSuccess();

		return $msg;

	}

	public static function setSuccess($msg){

		$_SESSION[User::SUCCESS] = $msg;

	}

	public static function clearSuccess()
	{

		$_SESSION[User::SUCCESS] = NULL;

    }       
    
    
}

?>