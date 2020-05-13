<?php
    require_once("config.php");

    use Cliente\Cadastro; // NAME SPACE QUE COLOQUEI NA CLASSE CADASTRO DO CLIENTE

    $cad = new Cadastro(); //QUANDO ESTOU UTILIZANDO O "USE" O SISTEMA VAI UTILIZAR A CLASSE "CADASTRO" DE ACORDO COM O NAME SPACE

    $cad->setNome("Luiz Felipe");
    $cad->setEmail("campos.luiz97@gmail.com");
    $cad->setSenha("123456");

    $cad->RegistrarVenda();

    echo $cad;
    

?>

