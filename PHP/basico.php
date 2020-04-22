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
         <=> -> space chip, -1 caso o da DIREITA é maior, 0 caso sejam IGUAIS e 1 caso o da ESQUERDA é maior
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
        - boa prática: Colocar os arquivos include dentro de umas pasta "inc"
        
        Diferença INCLUDE x REQUIRE
        -> require: Obriga que o arquivo exista e que o arquivo esteja funcionando corretamente. Caso nao esteja funcionando ou nao existe, 
                    é gerado um erro FATAL, para a execução do sistema.
        -> require_once(MAIS RECOMENDADO): garantir que chame apenas uma vez o arquivo, esse arquivo já pode ter sido chamado em outro 
                                           momento no código.                    
        -> include: tenta funcionar mesmo que o arquivo não exista ou tenha algum problema. Tem mais recursos, por exemplo o 
                    diretório "includePath". Exemplo.: Caso eu realize o include do arquivo funcoes.php, o método pesquisa na pasta atual,
                    caso não encontre, ele vai lá na pasta "includePath" verificar se existe. UTILIZAR O INCLUDE APENAS SE EU USO 
                    ESSA CONFIGURAÇÃO DE DIRETÓRIO.  


    */       
    
    echo "<br/> <strong> 6 - INCLUDE && REQUIRE </strong> <br/>"; 
    
    require_once "inc/funcoes.php";

    echo somar(10,10) . "<br />";

    require_once "inc/funcoes.php";

    echo subtrair(10,5);

    // ******************* ESTRUTURAS DE CONDIÇÃO *********************
    /* 
        - If ternário. 
        - switch case

    */         
    
    echo "<br/> <strong> 7 - ESTRUTURAS DE CONDIÇÃO </strong> <br/>"; 

    $maiorIdade = 18;
    $minhaIdade = 22;

    echo ($minhaIdade >= $maiorIdade ) ? "Maior de Idade" : "Menor de Idade" . "<br />";

    $diaSemana = date("w");

    switch ($diaSemana) {
        case 0:
            echo "<br /> Domingo";
            break;

        case 1:
            echo "<br /> Segunda-feira";
            break;

        default:
            echo "<br /> Outro dia";
            break;
    };


    // ******************* FOR $ FOREACH & WHILE *********************
    /* 
        - continue 
        - break

    */         
    
    echo "<br/> <strong> 8 - FOR $ FOREACH & WHILE </strong> <br/>";     

    echo "<select>";

    for($i = 2020; $i <= 2030; $i++){

        echo '<option value="'.$i.'">'.$i.'</option>';
    };
    echo "</select> <br>";


    $meses = array("Janeiro","Fevereiro","Março",
                   "Abril","Maio","Junho");

    foreach ($meses as $index => $mes) {
        echo "índice atual: ".$index."<br>";
        echo "Mes atual:". $mes. "<br>";
    }

    $condicao = true;

    while ($condicao){
        $numero = rand(1, 10);

        if ($numero === 10) {
            echo "<<-- PAROOU <br>";
            $condicao = false;
            break;
        }

        echo $numero . " ";
    };


    // ******************* Arrays *********************
    /* 
        end -> traz o ultimo valor do array; 
        array_push -> adicionar um valor ao array.
        json_encode
        json_decode(colocar true para transformar em array)
        define("nome",valor) -> constante
    */         
    
    echo "<br/> <strong> 9 - ARRAYS </strong> <br/>";     
    
    $pessoas = array();

    array_push($pessoas, array(
        'nome'=>'Luiz',
        'idade'=>22         
    ));

    array_push($pessoas, array(
        'nome'=>'Maria',
        'idade'=>30         
    ));    

    print_r($pessoas);

    echo "Primeira pessoa do array:". $pessoas[0]['nome'] . "<br>";

    define("CONSTANTE","MINHA PRIMEIRA CONSTANTE");

    echo CONSTANTE . "<br>";

    define("ARRAY_CONSTANTE", [
        'IP'=>"200",
        'SENHA'=>123
    ]);

    print_r(ARRAY_CONSTANTE);


        // ******************* sessões *********************
    /* 
        session_start -> para dizer que vou usar sessões na página (toda página que vou usar sessão tem que ter esse comando)
        session_id('posso passar o id de uma sessão existente') -> id da sessão 
        session_regenerate_id


        Todas as e variaveis de sessões são armazenadas na pasta temp do computador. 


    */   
    
    echo "<br/> <strong> 10 - SESSÕES </strong> <br/>";    

    session_start();

    $_SESSION['Servidor'] = "algum servidor";

    //unset($_SESSION['Servidor']);  

    echo (isset($_SESSION['Servidor'])) ? $_SESSION['Servidor'] : "Unset";

    echo "<br>" . "Local da sessão: " . session_status();



?>