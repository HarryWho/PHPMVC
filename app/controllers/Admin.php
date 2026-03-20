<?php
defined("ROOTPATH") or exit("Access Denied!");

class Admin extends Controller
{
    public function index()
    {

        if (Auth::atLeast('admin')) {
            $data = [
                'title' => 'Admin',
                'description' => 'Welcome to your administration dashboard.',
                'users' => AdminLoader::loadFunction('users')
            ];
            $this->view('admin/index', $data);
            exit();
        } else {
            Flash::set('error', 'You do not have permission to view that page');
            redirect('/');
            exit;
        }
    }

    public function ajax_get_user($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate CSRF token first
            requireCSRFToken();
            
        }
    }
}
