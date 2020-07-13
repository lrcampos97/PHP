<?php

namespace Ecommerce;

use Rain\Tpl;


class Page {


    private $tpl;

    private $options = [];
    private $defaults = [ // data default to config TPL settings
        "data"=> []
    ];

    public function __construct($opts = array(), $tpl_dir = "/views/"){

        $this->options = array_merge($this->defaults, $opts);  // array merge between two arrays to replace the information

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
            "debug"         => false
        );
    
        Tpl::configure( $config );
    
        $this->tpl = new Tpl;    

        $this->setData($this->options["data"]); // assign key and value for TPL template. Variables that will show at template

        $this->tpl->draw("header"); // first file to load, by default all pages have a "header"
    }

    private function setData($data = array()){
        foreach ($data as $key => $value) {
            $this->tpl->assign($key, $value); 
        }
    }

    public function setTpl($templateName, $data = array(), $returnHTML = false){
        $this->setData($data);

        return $this->tpl->draw($templateName, $returnHTML);
    }

    public function __destruct(){
        $this->tpl->draw("footer"); // first file to load, by default all pages have a "footer"
    }

}

?>