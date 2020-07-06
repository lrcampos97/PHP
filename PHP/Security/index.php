<?php
    /*  
        Segurança no PHP 
        -> escapeshellcmd(): Escapa qualquer caractere em uma string que possa ser utilizado para enganar um comando shell 
                             para executar comandos arbritários
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

?>

