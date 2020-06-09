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
        implode -> pegar um array e transformar em uma string de uma linha (explode é o contrário)
        cURL -> para consumir WS
        Cookie -> arquivo de texto que armazena informações na máquina do usuário (se nao colocar data o cookie morre na hora que o navegador fecha)
    */   

    echo "<br/> <strong> 1 - CRIANDO DIRETÓRIO  </strong> <br/>"; 

    $name = "images";

    if (!is_dir($name)){
        
        mkdir($name);

        echo "Diretório <strong> $name </strong> criado com sucesso!!";
    } else {
        echo "Diretório <strong> $name </strong> já existe!!";
    }

    echo "<br/> <strong> 2 - PESQUISANDO ARQUIVOS DIRETÓRIO </strong> <br/>"; 

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


    echo "<br/> <strong> 3 - CRIANDO E ABRINDO ARQUIVOS </strong> <br/>";

    $dir = "files";

    if (is_dir($dir)){
        $file = fopen($dir . DIRECTORY_SEPARATOR . "log.txt","w+");

        fwrite($file,"Testeeeee ARQUIVOS" . "\r\n" . "outro testeee CRIAANDO");
    
        fclose($file);

        echo "Arquivo criado e gravado com sucesso!!";
    } else {
        echo "Diretório <strong> $dir </strong> não encontrado!";
    }


    echo "<br/> <strong> 4 - EXCLUIR ARQUIVOS </strong> <br/>";

    $dir = "files";

    if (is_dir($dir)){
       $file = fopen($dir . DIRECTORY_SEPARATOR . "ARQUIVO_EXCLUIR.txt","w+");    

       fclose($file);
        
        echo "Arquivo <strong> ARQUIVO_EXCLUIR.txt </strong> criado!! <br>";
    }

    unlink($dir . DIRECTORY_SEPARATOR . "ARQUIVO_EXCLUIR.txt");

    echo "<br> Arquivo  <strong> ARQUIVO_EXCLUIR.txt </strong> excluído <br>";

    echo "<br/> <strong> 5 - LER CONTEÚDO DE ARQUIVOS </strong> <br/>";

    $filename = "images" . DIRECTORY_SEPARATOR ."php7.png";



    if (file_exists($filename)) {

        $base64 = base64_encode(file_get_contents($filename));

        // ver o tipo

        $fileInfo = new finfo(FILEINFO_MIME_TYPE);

        $mimeType = $fileInfo->file($filename);

        $base64encode = "data:" . $mimeType . ";base64," . $base64;

    } else {
        echo "Arquivo de imagem não encontrado.";
    }

    // $html = " <img src=".$base64encode.">";

    // echo $html;

    echo "<br/> <strong> 6 - UPLOAD DE ARQUIVOS </strong> <br/>";   

    /******* COMENTADO APENAS PARA NÃO INTERFERIR EM OUTRAS AULAS ******/

    // $htmlARQUIVO = 
    // "<form method='POST' enctype='multipart/form-data'>
    //     <input type='file' name='fileUpload'>

    //     <button type='submit'> Send </button>
    // </form>";

    // echo $htmlARQUIVO;


    // if ($_SERVER["REQUEST_METHOD"] === "POST"){
    //     $file = $_FILES["fileUpload"];

    //     if ($file["error"]){
    //         throw new Exception("Error:" . $file["error"], 1);            
    //     }


    //     $dirUploads = "uploads";

    //     if (!is_dir($dirUploads)){
    //         mkdir($dirUploads);
    //     }

    //     if (move_uploaded_file($file["tmp_name"], $dirUploads . DIRECTORY_SEPARATOR . $file["name"])){
    //         echo "Upload realizado com sucesso";
    //     } else {
    //         throw new Exception("Erro ao tentar realizar o upload", 1);    
    //     }
    // }    

    echo "<br/> <strong> 7 - DOWNLOAD ARQUIVOS </strong> <br/>";

    $link = "https://www.google.com.br/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png";

    $content = file_get_contents($link);

    $parse = parse_url($link);

    $baseName = basename($parse["path"]); // pegar apenas o nome do arquivo

    $diretorio = "images";
    
    if (is_dir($diretorio)){
        $file = fopen($diretorio . DIRECTORY_SEPARATOR . $baseName, "w+"); // CRIAR O ARQUIVO  
        
        fwrite($file, $content); /// criar um clone da imagem

        fclose($file); // fechar o arquivo
    }
    
    echo "Download realizado com sucesso";


    echo "<br/> <strong> 8 - MOVENDO ARQUIVOS </strong> <br/>";

    $dir1 = "Folder_1";
    $dir2 = "Folder_2";

    if (!is_dir($dir1)) mkdir($dir1);

    if (!is_dir($dir2)) mkdir($dir2);

    $nomeArquivo = "README.txt";

    if (!file_exists($dir1 . DIRECTORY_SEPARATOR . $nomeArquivo)){

        $arquivo = fopen($dir1 . DIRECTORY_SEPARATOR . $nomeArquivo, "w+");

        fwrite($arquivo, date("Y-m-d H:i:s"));

        fclose($arquivo);
    }


    rename(
            $dir1 . DIRECTORY_SEPARATOR . $nomeArquivo, // mover da origem
            $dir2 . DIRECTORY_SEPARATOR . $nomeArquivo // PARA O DESTINO
    );

    echo "Arquivo movido com sucesso!";


    echo "<br/> <strong> 9 - cURL </strong> <br/>";

    $cep = "94950555";
    $link = "http://viacep.com.br/ws/$cep/json";

    $ch = curl_init($link);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($response, true);

    print_r($data);


    echo "<br/> <strong> 10 - USANDO COOKIES </strong> <br/>";

    $data = array(
        "nome_empresa"=>"Teste nome empresa",
        "endereco"=>"rua iguaçu, 31"
    );


    setcookie("NOME_DO_COOKIE",json_encode($data), time() + 3600);

    echo "Cookie armazenado com sucesso";

    // CONSUMIR ALGUM COOKIE

    if (isset($_COOKIE["NOME_DO_COOKIE"])){

        echo "<br> valor cookie: <br>";
        
        var_dump(json_decode($_COOKIE["NOME_DO_COOKIE"], true));

    }
?>
