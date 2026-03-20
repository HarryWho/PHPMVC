<?php
defined("ROOTPATH") or exit("Access Denied!");

class Dashboard extends Controller
{
  
  public function index()
  {
    require_once '../app/core/DashboardLoader.php';
    if(Auth::atLeast('member')) {
      $catagories = QueryCache::remember(
        'catagories_' . Auth::user()->user_id,
        fn() => DashboardLoader::loadDashboardFunction('catagories')
      );
      $data = [
        'title' => 'Dashboard',
        'description' => 'Welcome to your dashboard.',
        'catagories' => $catagories, // contect the catagories database
        'groups-i-am-in' => '', // contact database for groups i am in 
        'my-pages' => ''

      ];
      $this->view('dashboard/index', $data);
      exit();
    }else{
      redirect('/');
      exit;
    }
  }
}