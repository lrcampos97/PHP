<?php

    class sql extends PDO {

        private $conn;

        public function __construct(){
            $this->conn = new PDO("mysql:hostname=localhost;dbname=curso","root","");
        }


        public function setParam($statment, $key, $value){

            $statment->bindParam($key, $value);

        }

        public function setParams($statment, $parameters = array()){

            foreach ($parameters as $key => $value) {
            
                $this->setParam($key, $value);

            }

        }

        public function query($query, $params = array()){
            
            $stmt = $this->conn->prepare($query);

            $this->setParams($stmt, $params);

            $stmt->execute();

            return $stmt;
        
        }


        public function select($query, $params = array()):array{
            
            $stmt = $this->query($query,$params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

    }
?>