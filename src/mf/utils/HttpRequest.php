<?php


namespace mf\utils;


use mf\router\AbstractRouter;

class HttpRequest extends  AbstractHttpRequest
{
    public function __construct()
    {
        $this->script_name = $_SERVER["SCRIPT_NAME"];
        if(isset($_SERVER["PATH_INFO"])) {
            $this->path_info = $_SERVER["PATH_INFO"];
        }
        $this->root = dirname($_SERVER["SCRIPT_NAME"], 1);
        if(array_key_exists("REQUEST_METHOD", $_SERVER)) {
            $this->method = $_SERVER["REQUEST_METHOD"];
            $this->get = $_GET;
            $this->post = $_POST;
        }
    }
}