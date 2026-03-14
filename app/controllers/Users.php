<?php
defined("ROOTPATH") or exit("Access Denied!");

class Users extends Controller
{
 
  public function profile($id=null)
  {
    if (!Auth::atLeast('member')) {
      redirect('/users/login');
      exit;
    }
    require_once '../app/models/User.php';
    $userModel = new User();

    $user = $userModel->first(['user_id' => $id]);
    $data = [
      'title' => 'Profile',
      'description' => 'View and edit your profile information.',
      'user' => $user
    ];
    $this->view('users/profile', $data);
  }
  public function register()
  {
    if(Auth::atLeast('member')){
      redirect('/dashboard');
      exit;
    }
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      $errors = validateRegistration($_POST);
     
      if(!empty($errors)){
        $data = [
          'title' => 'Register',
          'description' => 'Create an account to access all features.',
          'errors' => $errors,
          'field_values' => $_POST
        ];
        $this->view('users/register', $data);
        exit;
      }else{
        // Save user to database (not implemented here)
        require_once '../app/models/User.php';
        $user = new User();
        
        $user->insert([
          'user_name' => $_POST['user_name'],
          'user_email' => $_POST['user_email'],
          'user_password' => password_hash($_POST['user_password'], PASSWORD_DEFAULT)
          
        ]);
        Flash::set('success', 'Registration successful. You can now log in.');
        redirect('/users/login');
        exit;
      }

    }

    $data = [
      'title' => 'Register',
      'description' => 'Create an account to access all features.'
    ];
    $this->view('users/register', $data);
  }

  public function login()
  {
    if(Auth::atLeast('member')){
      redirect('/dashboard');
      exit;
    }
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      $user_email = $_POST['user_email'];
      $user_password = $_POST['user_password'];
      require_once '../app/models/User.php';
      $user = new User();
      $found_user = $user->get_row("SELECT * FROM users WHERE user_email = ?", [$user_email]);
      
      if ($found_user && password_verify($user_password, $found_user->user_password)) {
        // Login successful
        $_SESSION['user'] = $found_user;
        Flash::set('success', 'You have been logged in successfully.');
        redirect('/dashboard');
        exit;
      } else {
        // Login failed
        $data = [
          'title' => 'Login',
          'description' => 'Login to access your account.',
          'errors' => ['login_error' => 'Invalid email or password.']
        ];
        $this->view('users/login', $data);
        exit;
      }

    }

    $data = [
      'title' => 'Login',
      'description' => 'Login to access your account.'
    ];
    $this->view('users/login', $data);
  }

  public function logout()
  {
    Flash::set('success', 'You have been logged out successfully.');
    unset($_SESSION['user']);
    redirect('/users/login');
    exit;
  }

}