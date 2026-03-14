<?php

defined("ROOTPATH") or exit("Access Denied!");
class _404 extends Controller
{
   
    public function controllerNotFound()
    {
        $data = [
            'title' => '404 Not Found',
            'description' => 'The controller you are looking for does not exist.'
        ];
        $this->view('404/index', $data);
    }

    public function methodNotFound()
    {
        $data = [
            'title' => '404 Not Found',
            'description' => 'The method you are looking for does not exist.'
        ];
        $this->view('404/index', $data);
    }
}