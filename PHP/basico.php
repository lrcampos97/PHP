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

    //$URL = (float)$_GET["a"]; /// CAST & pegar valor por URL
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
         <=> -> space chip -1 caso o da DIREITA é maior 0 caso sejam IGUAIS e 1 caso o da ESQUERDA é maior
         ?? -> coalesce
         $a++ ou ++$a -> 
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

    echo 'space chip' . var_dump($a <=> $b) . '<br />';

    $a = NULL;
    $b = 20;

     echo $a ?? $b . '<br />'; // se a variavel A for NULA, exibir B.. assim por diante 

    // ******************* String *********************
    /* 
        Interpolação de variaveis (utilizar aspas simple)
        strtoupper -> deixar o valor em UPPER CASE
        strtolower -> DEIXAR EM lower
        ucwords -> Deixar a primeira letra MAIUSCULA de cada palavra.
        ucfirst -> Deixa a primeira letra da primeira palavra em MAIUSCULA
        str_replace -> Alterar letras na string.
        strpos -> indice da palavra que quero buscar
        substr -> pegar palavra entre indices
        strlen -> tamanho da string;
    */       
    echo "<br/> <strong> 5 - STRINGS </strong> <br/>"; 

    $aspasDupla = "Teste aspas duplas";
    $numero = 30;
    $aspasSimples = 'Teste aspas simples';

    echo "INTERPOLAÇÃO DE VARIAVEIS ->  $aspasDupla MAIS outra variavel SEM concatenar $numero <br /><br />" ;

    $testeUpper = "teste upper case";

    echo "upper case de variavel ->".strtoupper($testeUpper)."<br />"; 

    $nome = "luiz felipe campos <br />";

    echo ucwords($nome); 

    $testeReplace = "Teste replace";

    echo str_replace("e","3", $testeReplace)."<- REPLACE <br />";

    $testePos = "Maria santos aparecida";
    echo strpos($testePos, "santos")." <- POSIÇÃO <br />";

    echo substr($testePos, 0, strlen($testePos)-3)." <- SUB STRING <br />";

    // ******************* INCLUDE && REQUIRE *********************
    /* 
        
    */       
    echo "<br/> <strong> 6 - INCLUDE && REQUIRE </strong> <br/>"; 

?>