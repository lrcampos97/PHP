<?php

    // ******************* FUNÇÕES *********************
    /* 
        func_get_args() -> Utilizar dentro de funções caso queira pegar os parâmetros. (se os parâmetros não existiram na declaração)   
        & -> na função é passagem de parâmetro por referencia; (ou seja, vou poder alterar o valor que está sendo passado dentro da function)        
        int ...$valores -> declaração de tipos escalares.
    */     
 
    echo "<br/> <strong> 1 - FUNÇÕES </strong> <br/>"; 

    function somar($a, $b){
        return $a + $b;
    }

    function subtrair($a, $b){
        return $a - $b;
    }

    function argumentos(){
        return func_get_args();

        
    }
    var_dump(argumentos("Algum tipo de parametro", 3));

    echo "<br>";    

    $variabelGlobal = 10;

    function passagemParametroReferencia(&$valor){ // PARÂMETRO POR REFERÊNCIA 
        $valor += 20;
        echo $valor."<br>";
    }
    echo passagemParametroReferencia($variabelGlobal) ;

    echo "alterado <br>".($variabelGlobal);


    echo "<br> ------ NOVIDADES PHP 7 --------";

    function declaraoTiposEscalares(int ...$valores){
        return array_sum($valores); // soma todos os valores aumaticamente.
    }

    function funcaoComRetorno(): float{
        

        return 30;
    }

    echo "<br>";
    echo declaraoTiposEscalares(25,36,50);
    echo "<br>";
    echo funcaoComRetorno();
    echo "<br>";





?>