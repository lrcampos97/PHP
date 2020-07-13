<?php
    // ******************* DATA ACCESS OBJECT(DAO) + PDO *********************
    /* 
        Para abstrair o banco de dados, são classes que fazem as funções do banco de dados. 
    */   

    require_once("config.php");
    
    echo "<br/> <strong> 1 - Buscar apenas um usuário </strong> <br/>";  

    $usuario = new Usuario();

    $usuario->loadById(1);

    echo $usuario;

    echo "<br/> <strong> 2 - Buscar todos os usuários </strong> <br/>";  

    // Trazer todos os usuários usando método ESTÁTICO

    $usuarios = Usuario::getAllUsers();

    echo json_encode($usuarios);


    echo "<br/> <strong> 3 - Filtrar usuários </strong> <br/>";  

    $search = Usuario::search("us");
    
    echo json_encode($search);


    echo "<br/> <strong> 4 - Validar usuário </strong> <br/>"; 
    
    $maria = new Usuario();

    $maria->login("paula","123");


    echo "<br/> <strong> 5 - Incluir novo usuários </strong> <br/>"; 

    $carlos = new Usuario("carlos","654sadadas");

    //$carlos->insert(); // INCLUIR NOVO USUÁRIO

    echo "<br/> <strong> 6 - Atualizar dados usuário </strong> <br/>"; 

    $usuario = new Usuario();

    $usuario->loadById(3);

    $usuario->update("paula", "123");

    echo $usuario;

    echo "<br/> <strong> 7 - Exclusão de usuário </strong> <br/>"; 
    
    $usuario = new Usuario();

    $usuario->loadById(5);

    $usuario->delete();

?>