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

    $maria->login("maria","99999996441");




?>