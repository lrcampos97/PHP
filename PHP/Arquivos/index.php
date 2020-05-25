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
    
    $htmlARQUIVO = 
    "<form method='POST' enctype='multipart/form-data'>
        <input type='file' name='fileUpload'>

        <button type='submit'> Send </button>
    </form>";

    echo $htmlARQUIVO;


    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $file = $_FILES["fileUpload"];

        if ($file["error"]){
            throw new Exception("Error:" . $file["error"], 1);            
        }


        $dirUploads = "uploads";

        if (!is_dir($dirUploads)){
            mkdir($dirUploads);
        }

        if (move_uploaded_file($file["tmp_name"], $dirUploads . DIRECTORY_SEPARATOR . $file["name"])){
            echo "Upload realizado com sucesso";
        } else {
            throw new Exception("Erro ao tentar realizar o upload", 1);    
        }
    }    

?>
<!-- 
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="fileUpload">

    <button type="submit"> Send </button>
</form> -->