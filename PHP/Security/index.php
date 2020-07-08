<?php
    /*  
        Segurança no PHP 
        -> escapeshellcmd(): Escapa qualquer caractere em uma string que possa ser utilizado para enganar um comando shell 
                             para executar comandos arbritários        

        -> permissão 0775: utilizar ela quando criar uma pasta em tempo de execução

        -> strip_tags: retirar qualquer tipo de tags injetadas nos inputs

        -> SEMPRE depois de validar login e senha do usuário, reiniciar o ID de sessão, para que nao tenha risco de alguém conseguir
    */

    echo "<br/> <strong> 1 - Command Injection  </strong> <br/>"; 
   
    if ($_SERVER["REQUEST_METHOD"] === 'POST'){
        
        $cmd = escapeshellcmd($_POST["cmd"]);

        var_dump($cmd);

        echo "<pre>";

        $comando = system($cmd, $retorno);

        echo "</pre>";
    }

    $html = '    
    <form method="post">
        <input type="text" name="cmd">
        <button type="submit"> Enviar </button>
    </form>';

    echo $html;


    echo "<br/> <strong> 2 - SQL Injection  </strong> <br/>";

    echo " - A utilização do PDO com o bindvalues, já exibe qualquer ataque de SQL Injection <br/>";

    echo "<br/> <strong> 3 - Criptografia  </strong> <br/>"; 


    echo "-> Utilizando <strong> MCRYPT </strong> <br>";

    $data = [
        "senha"=>"testeDeSenha"
    ];

    define('SECRET', pack('a16','senha')); // CRIANDO A CONSTANTE. Pack converte a string para 16bits

    $mcrypt = mcrypt_encrypt(
         MCRYPT_RIJNDAEL_128,
         SECRET,
         json_encode($data), // valor que vai ser encriptado
         MCRYPT_MODE_ECB // modo de criptografia utilizado.
    );


    $final = base64_encode($mcrypt); // para fizer "legível" o código e salvar em algum lugar 

    var_dump($final);
    
    $string = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        SECRET,
        base64_decode($final),
        MCRYPT_MODE_ECB
    );

    echo "<br>";

    var_dump($string);

    echo "<br> -> Utilizando <strong> OPENSSL </strong> <br>";
    
    $dados = [
        "senha"=>"123senha"
    ];

    define('SECRET_IV', pack('a16','senha'));
    define('SECRET_I', pack('a16','senha'));

    $openssl = openssl_encrypt(
        json_encode($dados),
        'AES-128-CBC', // ALGORITMO UTILIZADO
        SECRET_I,
        0,
        SECRET_IV
    );

    echo $openssl;


    $resultado = openssl_decrypt(
        $openssl,
        'AES-128-CBC',
        SECRET_I,
        0,
        SECRET_IV
    );

    echo "<br>";
    var_dump($resultado);



?>

