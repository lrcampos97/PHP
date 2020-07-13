<?php

    // ******************* FUNÇÕES *********************
    /* 
        func_get_args() -> Utilizar dentro de funções caso queira pegar os parâmetros. (se os parâmetros não existiram na declaração)   
        & -> na função é passagem de parâmetro por referencia; (ou seja, vou poder alterar o valor que está sendo passado dentro da function)        
        int ...$valores -> declaração de tipos escalares.
        setlocale() -> ajusta configurações do padrão de linguagem.
        strftime() -> trazer dias e datas por extenso. 
        DateInterval -> para incluir um periodo no método add.
        "->": isso é utilizar um método dentro de uma classe.
    
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

    echo "<br> ------ FUNÇÕES RECURSIVAS --------";

    $hierarquia = array(
        array(
            'nome_cargo'=>'CEO',
            'subordinados'=>array(
                array(
                    'nome_cargo'=>'Diretor Comercial',
                    'subordinados'=>array(
                        array(
                            'nome_cargo'=>'Gerente de Vendas'
                        )
                    ),
                    'nome_cargo'=>'Diretor de Pesquisas',
                    'subordinados'=>array(
                        array(
                            'nome_cargo'=>'Gerente de Vendas',
                            'subordinados'=>array(
                                array(
                                    'nome_cargo'=>"Funcionario"
                                )
                            )
                        )
                    )                    
                )   
            )
        ),

        array(
            'nome_cargo'=>'CTO',
            'subordinados'=>array(
                array(
                    'nome_cargo'=>"Funcionario"
                )
            )
        )
    );

    function exibe($cargos){
        $html = '<ul>';  
    


        foreach ($cargos as $cargo) {
            $html .= '<li>';

            $html .= $cargo['nome_cargo'];

            if(isset($cargo['subordinados']) && count($cargo['subordinados']) > 0 ){
                $html.= exibe($cargo['subordinados']);
            }

            $html .= '</li>';
        };
        
        $html .= '</ul>';

        return $html;
    };

    echo exibe($hierarquia);

    echo '<br>';


    echo "<br> ------ FUNÇÕES ANONIMAS --------";

    function testeAnonimo($functionCallBack){
        // algum processo lento....

        $functionCallBack();
    };


    testeAnonimo(function(){
        echo "<br> Chamada função anonima";
    });

    $variavelFuncao = function(){
        echo "<br> variavel função anonima";
    };

    $variavelFuncao();


    echo "<br> ------ FUNÇÕES DATA E HORA -------- <br>";


    echo "Data atual: ".date("d/m/Y H:i:s")."<br>";

    echo "Time: ".time()."<br>";


    setlocale(LC_ALL,"pt-BR","pt_BR.utf-8","portuguese");

    echo "Hoje é: ".date("d")." de ".ucwords(strftime('%B %A'))."<br>";

    $data = new DateTime();

    echo "Utilizando datetime: ". $data->format("d/m/Y")."<br>";

    $periodo = new DateInterval("P1D");

    $data->add($periodo);

    echo "Incrementando um dia: ". $data->format("d/m/Y")."<br>";







?>