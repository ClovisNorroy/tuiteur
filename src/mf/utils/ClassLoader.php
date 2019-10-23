<?php
namespace mf\utils;

class ClassLoader
{
    private $prefix;

    public function __construct($prefix)
    {
        $this->prefix=$prefix;
    }

    public function register(){
        spl_autoload_register(function($stringClass){
            $toRecquire= $this->prefix."/".strtr($stringClass, "\\", DIRECTORY_SEPARATOR) . ".php";
            if(file_exists($toRecquire))
              require_once $toRecquire;
        });
    }
}