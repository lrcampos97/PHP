<?php

namespace Ecommerce\Model;

use \Ecommerce\DB\Sql;
use \Ecommerce\Model;
use \Ecommerce\Mailer;


class Product extends Model {

    public  function __construct($idproduct = 0){
        if ($idproduct !== 0){            
            $this->get($idproduct);    
        }
    }

    public static function listAll(){
        
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_products ORDER BY desproduct"); 
    }

    public static function checkList($list){ // função para tratar as imagens dos produtos, pelo fato das mesmas não estarem no BD

        foreach ($list as &$row) { // O "&" serve para que eu possa alterar a variavel "row" e consequentemente, alterar o valor dentro do array.
            
            $p = new Product();
            $p->setData($row);
            $row = $p->getValues();
        }

        return $list;
    }

    public function getFromUrl($desUrl){
                
        $sql = new Sql();

        $row = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1",[
            ":desurl"=>$desUrl
        ]);

        $this->setData($row[0]);

    }

    public function getCategories(){

		$sql = new Sql();

		return $sql->select("
			SELECT * FROM tb_categories a INNER JOIN tb_productscategories b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct
		", [

			':idproduct'=>$this->getidproduct()
		]);       

    }
        

    public function get($idproduct){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_products prod WHERE prod.idproduct = :idproduct", array(
            ":idproduct"=>$idproduct
        ));
        
        $this->setData($results[0]);        
    }


    public function delete(){

        $sql = new Sql();

        $sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct",array(
            ":idproduct"=>$this->getidproduct()
        ));
       
    }   

    public function save(){
        
        $sql = new Sql();


        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct"=> $this->getidproduct(), // ESTES MÉTODOS SÃO GERADOS DE FORMA AUTOMATICA PELA CLASSE MODEL
            ":desproduct"=> $this->getdesproduct(),
            ":vlprice"=> $this->getvlprice(),
            ":vlwidth"=> $this->getvlwidth(),
            ":vlheight"=> $this->getvlheight(),
            ":vllength"=> $this->getvllength(),
            ":vlweight"=> $this->getvlweight(),
            ":desurl"=> $this->getdesurl()
        ));    
        
        $this->setData($results[0]);       
    }    


    public function checkPhoto(){

		if (file_exists(
			$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
			"res" . DIRECTORY_SEPARATOR . 
			"site" . DIRECTORY_SEPARATOR . 
			"img" . DIRECTORY_SEPARATOR . 
			"products" . DIRECTORY_SEPARATOR . 
			$this->getidproduct() . ".jpg"
			)) {

			$url = "/res/site/img/products/" . $this->getidproduct() . ".jpg";

		} else {

			$url = "/res/site/img/product.jpg";

		}

		return $this->setdesphoto($url);        

    }

    public function getValues(){

        $this->checkPhoto();

        $values = parent::getValues();

        return $values;
    }

    public function setPhoto($file){        

        if ($file["name"] !== ''){

            $extension = explode('.',  $file["name"]);
            $extension = end($extension);

            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($file["tmp_name"]);
                    break;
                
                case 'gif':
                    $image = imagecreatefromgif($file["tmp_name"]);
                    break;

                case 'png':
                    $image = imagecreatefrompng($file["tmp_name"]);
                    break;                
            }

            $dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
                "res" . DIRECTORY_SEPARATOR . 
                "site" . DIRECTORY_SEPARATOR . 
                "img" . DIRECTORY_SEPARATOR . 
                "products" . DIRECTORY_SEPARATOR . 
                $this->getidproduct() . ".jpg";

            imagejpeg($image, $dist); // CONVERTER PARA JPG

            imagedestroy($image);

            $this->checkPhoto();        
        }
    }


	public static function getPage($page = 1, $itemsPerPage = 10)
	{

		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_products 
			ORDER BY desproduct
			LIMIT $start, $itemsPerPage;
		");

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];

	}

	public static function getPageSearch($search, $page = 1, $itemsPerPage = 10)
	{

		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_products 
			WHERE desproduct LIKE :search
			ORDER BY desproduct
			LIMIT $start, $itemsPerPage;
		", [
			':search'=>'%'.$search.'%'
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];

	}    

}