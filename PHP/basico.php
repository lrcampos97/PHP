<?php
    // ******************* TIPAGEM *********************
    echo "<strong> 1 - TIPAGEM </strong> <br/>";
    $teste = 2;
    echo $teste;

    unset($teste); // UNSET -> remover variavel da memoria

    echo "<br/>";

    if (isset($teste)){ // ISSET  => verificar se a variavel existe;
        echo $teste;
    } else {
        echo "erro" . " " . "CONTATENOU";
        //exit; // finalizar a execução, limitar até aqui 
    }
    
    $aspasDupla = "testando";
    $aspasSimples = 'testando algo';

    $integer = 1990;
    $float = 200.00;
    $boolean = false;        
    $data = new DateTime();

    $arquivo = fopen("index.php","r");
    
    var_dump($arquivo);


    // ******************* VARIAVEIS PRÉ DEFINIDAS (SUPER GLOBAIS) *********************
    /* 
        $_GET[""] -> Informações via URL
        $_SERVER[""] -> INFORMAÇÕES DO SERVIDOR

    */
    echo "<strong> 2 - VARIAVEIS PRÉ DEFINIDAS (SUPER GLOBAIS) </strong> <br/>";  

    $URL = (float)$_GET["a"]; /// CAST
    $IP = $_SERVER["REMOTE_ADDR"]; /// PEGAR O IP 
    $nomeArquivo = $_SERVER["SCRIPT_NAME"]; /// NOME DO ARQUIVO PHP EXECUTADO.

    var_dump($nomeArquivo);


    // ******************* ESCOPO DE VARIÁVEIS *********************
    /* 
        global -> para utilizar variaveis globais do arquivo php.

    */    

    echo "<strong> 3 - ESCOPO DE VARIÁVEIS </strong> <br/>"; 
    

    $nome = "luiz";

    function testarNome() {
        $nome = "carlos";
        global $nome; // QUANDO QUERO UTILIZAR UMA VARIAVEL FORA DA FUNCTION (DEVE TER O MESMO NOME)

        echo $nome;        
    };

    testarNome();

    // ******************* OPERADORES *********************
    /* 
        *= . -->> USAR COM PORCENTAGEM
         =  --> 1 SINAL DE IGUAL ATRIBUI VALOR
         == --> COMPARA OS VALORES
         === --> COMPARA OS VALORES E OS TIPOS (IGUALDADE DE IDENTIDADE)
         != -> COMPARA VALOR
         !== -> COMPARA VALOR E TIPO DE DADO
    */       
    echo "<br/> <strong> 4 - OPERADORES </strong> <br/>"; 

    $nome = "Testando";

    $nome .= " algo com string <br/>";

    echo $nome;

    $subTotal = 200;
    $subTotal += 100;
    $subTotal -= 50;
    $subTotal *= 2; 
    $subTotal *= .5; // 50% DO VALOR 

    echo $subTotal;

    echo "<br />";

    $valorTeste = 20;

    echo $subTotal + $valorTeste . "- SOMA <br />";
    echo $subTotal - $valorTeste . "- SUBTRAÇÃO <br />";
    echo $subTotal * $valorTeste . "- MULTIPLICAÇÃO <br />";
    echo $subTotal / $valorTeste . "- DIVISÃO <br />";
    echo $subTotal % $valorTeste . "- MÓDULO Resto de A dividido por B <br />"; 
    //echo $subTotal ** $valorTeste . "- EXPONENCIAL Resultado de A elevado a B <br />"; 

    $a = 10;
    $b = 35;

    var_dump($a !== $b);



?>