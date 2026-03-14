<?php
defined("ROOTPATH") or exit("Access Denied!");

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

  private function splitUrl()
    {
        $URI = $_GET['url'] ?? 'home';
        $url = rtrim($URI, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
       
    }

    public function loadController()
    {
        $url = $this->splitUrl();

        if(file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
            require_once '../app/controllers/' . ucfirst($url[0]) . '.php';
            $this->controller = $url[0];
            unset($url[0]);
        } else {
            require_once '../app/controllers/_404.php';
            $this->controller = "_404";
            $this->method = 'controllerNotFound';
        }
        
        
        if(isset($url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        if(!method_exists($this->controller, $this->method)) {
            require_once '../app/controllers/_404.php';
            $this->controller = "_404";
            $this->method = 'methodNotFound';
        }
        if(!empty($url[2])) {
            $this->params = $url[2];
           
        } else {
            $this->params = [];
        }
        
        $controller = new $this->controller;

        call_user_func_array([$controller, $this->method], [$this->params]);
    }

}