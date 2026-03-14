<?php
defined("ROOTPATH") or exit("Access Denied!");

class Controller
{
    protected function view($view, $data = [])
    {
        $user = Auth::user();
        
        require_once '../app/views/includes/template_header.php';
        if (file_exists('../app/views/' . $view . '.view.php')) {
            require_once '../app/views/' . $view . '.view.php';
        } else {
            die('View does not exist');
        }
        require_once '../app/views/includes/template_footer.php';
    }

    
}