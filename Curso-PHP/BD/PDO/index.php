<?php
    // ******************* PDO *********************
    /* 
       FETCH_ASSOC -> Trás o nome da coluna e o seu valor;
       fetchAll -> trás o nome da coluna e trás o valor junto com um index da coluna. 


       ConnectionPooling-> Utilizado em multi thread. Começa, executa e encerra.
    */       

    echo "<strong> 1 - CONEXÃO BD COM PDO </strong> <br/>";

    
    $conn = new PDO("mysql:dbname=curso;host=localhost","root",""); // MYSQL
    
    //$conn = new PDO("sqlsrv:Database=curso;server=.;ConnectionPooling=0","sa","123"); // SQL SERVER

    echo "Conectado com sucesso! <br>";


    echo "<br><strong> 2 - CONSULTAR DADOS </strong> <br/>";

    $stmt = $conn->prepare("SELECT * FROM tb_usuarios");

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($results as $row) {
        foreach ($row as $key => $value) {
            echo "<strong>". $key . "</strong>: " . $value . "<br/>";            
        }

        echo "=============================================== <br/>";
    }


    echo "<br/> <strong> 3 - INCLUIR REGISTRO </strong> <br/>";     


    $conn = new PDO("mysql:dbname=curso;host=localhost","root","");

    $stmt = $conn->prepare("INSERT INTO tb_usuarios (deslogin, dessenha) VALUES (:LOGIN,:SENHA)");

    $login = "maria";
    $senha = "99999996441";

    $stmt->bindParam(":LOGIN",$login);
    $stmt->bindParam(":SENHA",$senha);

   // $stmt->execute();

    echo "Incluiu o login: <strong>" . $login . "</strong> com sucesso!!";

    echo "<br/> <strong> 4 - TRANSAÇÕES </strong> <br/>";

    $conn->beginTransaction(); // INICIANDO A TRANSAÇÃO

    $stmt = $conn->prepare("DELETE FROM tb_usuarios WHERE idusuario = ?");
    
    $stmt->execute(array(3)); //NOVA MANEIRA DE PASSAR PARÂMETRO 

    $conn->rollBack();
    //$conn->commit();

    echo "Delete OK";

    

?>