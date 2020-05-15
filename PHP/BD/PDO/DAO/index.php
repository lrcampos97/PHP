<?php
    // ******************* DATA ACCESS OBJECT(DAO) + PDO *********************
    /* 
        Para abstrair o banco de dados, são classes que fazem as funções do banco de dados. 
    */   

    require_once("config.php");

    $sql = new sql();

    $usuarios = $sql->select("SELECT * FROM tb_usuarios");

    echo json_encode($usuarios);

?>