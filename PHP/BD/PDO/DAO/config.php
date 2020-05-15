<?php

    spl_autoload_register(function($className){

        $dirName = "class";
        $filename = $dirName . DIRECTORY_SEPARATOR .  $className . ".php";

        if (file_exists($filename)){
            require_once($filename);
        }
    })

?>