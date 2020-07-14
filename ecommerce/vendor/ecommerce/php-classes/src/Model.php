<?php

namespace Ecommerce;

class Model {

    private $values = []; // valores dentro do objeto a ser instanciado

    public function __call($name, $args){ // função invocada ao chamar algum método da classe

        $method = substr($name, 0, 3); // verificar se é GET ou SET
        $fieldName = substr($name, 3, strlen($name)); // pegar o nome do campos


        switch ($method) {
            case "get":
                return $this->values[$fieldName];
                break;            
            case "set":
                return $this->values[$fieldName] = $args[0];
                break;
        }

    }


    public function setData($data){

        foreach ($data as $key => $value) {
            $this->{"set".$key}($value);
        }

    }

    public function getValues(){

        return $this->values;

    }

}


?>
