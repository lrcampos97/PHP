<?php
    // ******************* MANIPULAÇÃO DE ARQUIVOS *********************
    /* 
        is_dir() -> verificaa se o nome passado é um diretório.
        rmdir() -> remover o diretório;
        scandir() -> "scaniar todos os arquivos de determinado diretório"
        in_array() -> Checks if a value exists in an array
        pathinfo -> PEGAR INFORMAÇÕES DO ARQUIVO
        filesize -> tamanho do arquivo
        filemtime -> data na qual foi alterado.
        
    */   

    echo "<br/> <strong> 1 - CRIANDO DIRETÓRIO  </strong> <br/>"; 

    $name = "images";

    if (!is_dir($name)){
        
        mkdir($name);

        echo "Diretório <strong> $name </strong> criado com sucesso!!";
    } else {
        echo "Diretório <strong> $name </strong> já existe!!";
    }

    echo "<br/> <strong> 2 - PESQUISANDO ARQUIVOS  </strong> <br/>"; 

    $images = scandir("images");

    $data = array();

    foreach ($images as $img) {
        if (!in_array($img, array(".",".."))) {
            
            $filename = "images" . DIRECTORY_SEPARATOR . $img;

            $info = pathinfo($filename); // PEGAR INFORMAÇÕES DO ARQUIVO.

            $info["size"] = filesize($filename);
            $info["modified"] = date("d/m/Y H:i:s", filemtime($filename));
            $info["url"] = "http://localhost/Code/PHP/Arquivos/" . str_replace("\\", "/", $filename);

            array_push($data, $info);
        }
    }

    echo json_encode($data);



?>