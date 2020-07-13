<?php

    require_once("../vendor/autoload.php");

    // namespace
    use Rain\Tpl;

    // config
    $config = array(
        "tpl_dir"       => "templates/",
        "cache_dir"     => "cache/"
    );

    Tpl::configure( $config );

    // create the Tpl object
    $tpl = new Tpl;

    // assign a variable
    $tpl->assign( "projeto", "Curso PHP" );
    $tpl->assign( "version", "1.0.0" );

    // assign an array
    //$tpl->assign( "week", array( "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" ) );

    // draw the template
    $tpl->draw( "index" ); // NOME DO MEU ARQUIVO LÁ NA PASTA TEMPLATES

?>