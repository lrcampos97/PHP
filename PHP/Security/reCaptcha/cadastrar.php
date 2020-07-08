<?php

    $email = isset($_POST["inputEmail"]) ? $_POST["inputEmail"] : "Oi";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify"); // API DE VERIFICAÇÃO DO GOOGLE
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // não utilizar SLL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        "secret"=>"key",
        "response"=>$_POST["g-recaptcha-response"],
        "remoteip"=>$_SERVER["REMOTE_ADDR"]
    )));


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // dizendo que estou esperando algum retorno

    $recaptcha = json_decode(curl_exec($ch), true);

    if ($recaptcha["success"] === true){
        echo "reCAPTCHA OK: " . $_POST["inputEmail"];
    } else {
        header("Location: index.php"); // retorna para a página
    }


?>
