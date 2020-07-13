<?php

    namespace Cliente; // NAME SPACE DESTA CLASSE;

    class Cadastro extends \Cadastro{

        public function RegistrarVenda(){
            echo "Venda registrada para o cliente " . $this->getNome()."<br/>";
        }

    }
?>