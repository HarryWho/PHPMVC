<?php
defined("ROOTPATH") or exit("Access Denied!");

class Create extends Controller
{
    public function catagory()
    {
        if (Auth::isLoggedIn() && Auth::atLeast('author')) {
            if ($_SERVER['REQUEST_METHOD'] === "POST") {
                dd($_POST);
                die;
            } else {
                require_once '../app/core/DashboardLoader.php';
                $catagories = QueryCache::remember(
                    'catagories_' . Auth::user()->user_id,
                    fn() => DashboardLoader::loadDashboardFunction('catagories')
                );
                $data = [
                    'title' => 'Create',
                    'description' => 'Create a new catagory.',
                    'catagories' => $catagories, // contect the catagories database
                    'which_form' => 'catagory'
                ];

                $this->view('create/catagory', $data);

                exit;
            }
        } else {
            Flash::set('error', 'You do not have permission to view that page! Please login');
            redirect('/users/login');
            exit;
        }
    }
}
