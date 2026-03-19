<?php
defined("ROOTPATH") or exit("Access Denied!");

class Dashboard extends Controller
{
  
  public function index()
  {
    require_once '../app/core/DashboardLoader.php';
    if(Auth::atLeast('member')) {
      $data = [
        'title' => 'Dashboard',
        'description' => 'Welcome to your dashboard.',
        'catagories' => DashboardLoader::loadDashboardFunction('catagories'), // contect the catagories database
        'groups-i-am-in' => '', // contact database for groups i am in 
        'pages-i-own' => ''

      ];
      $this->view('dashboard/index', $data);
      exit();
    }else{
      redirect('/');
      exit;
    }
  }
}