<?php
    // ******************* PDO *********************
    /* 
       FETCH_ASSOC -> Trás o nome da coluna e o seu valor;
       fetchAll -> trás o nome da coluna e trás o valor junto com um index da coluna. 


       ConnectionPooling-> Utilizado em multi thread. Começa, executa e encerra.
    */       

    echo "<strong> 1 - CONEXÃO BD COM PDO </strong> <br/>";

    //$conn = new PDO("mysql:dbname=curso;host=localhost","root",""); // MYSQL
    
    $conn = new PDO("sqlsrv:Database=curso;server=.;ConnectionPooling=0","sa","123"); // SQL SERVER

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

    

?>