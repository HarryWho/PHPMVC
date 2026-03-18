<?php
defined("ROOTPATH") or exit("Access Denied!");

class Home extends Controller
{
   
    public function index($params = [])
    {
       if (!Auth::user()) {
            redirect('/users/login');
            exit;
        } else {
            redirect('/dashboard');
            exit();
        }

        $data = [
            'title' => 'Home',
            'description' => 'Welcome to the home page of our application.'
        ];
        $this->view('home/index', $data);
    }
    

    public function about()
    {
        $data = [
            'title' => 'About Us',
            'description' => 'This is the about page of our application.'
        ];
        $this->view('home/about', $data);
    }
    public function contact()
    {
        $data = [
            'title' => 'Contact Us',
            'description' => 'This is the contact page of our applications.'
        ];
        $this->view('home/contact', $data);
    }
}