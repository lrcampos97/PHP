<?php

    echo "<br/> <strong> 1 - ABRIR CONEXÃO  </strong> <br/>"; 
    
    $conn = new mysqli("localhost","root","","curso");

    if ($conn->connect_error){
        echo "Erro: " . $conn->connect_error;    
    } else {
        echo "Conectou com sucesso!!!";
    };


    echo "<br/> <strong> 2 - INCLUIR REGISTRO </strong> <br/>";     


    $stmt = $conn->prepare("INSERT INTO tb_usuarios (deslogin, dessenha) values (?, ?)");

    $stmt->bind_param("ss",$login ,$pass);

    $login = "user";
    $pass = "123";

    //$stmt->execute();  
    echo "Registro já incluso!!!";  

    echo "<br/> <strong> 3 - CONSULTAR REGISTRO </strong> <br/>";     


    $result = $conn->query("SELECT * FROM tb_usuarios");

    $data = array();

    while ($row = $result->fetch_assoc()){
        array_push($data, $row);
    }

    echo json_encode($data);





?>