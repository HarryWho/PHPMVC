<?php
defined("ROOTPATH") or exit("Access Denied!");

class Dashboard extends Controller
{
  
  public function index()
  {
    if(Auth::atLeast('member')) {
      $data = [
        'title' => 'Dashboard',
        'description' => 'Welcome to your dashboard.'
      ];
      $this->view('dashboard/index', $data);
      exit();
    }else{
      redirect('/');
      exit;
    }
  }

}