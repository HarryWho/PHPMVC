<?php
defined("ROOTPATH") or exit("Access Denied!");

class Users extends Controller
{
 
  public function profile($id=null)
  {
    if (!Auth::atLeast('member')) {
      redirect(BASE_URL . '/users/login');
      exit;
    }
    
    // Authorization check: User can only view their own profile unless they're admin
    if ((int)$id !== Auth::user()->user_id && !Auth::atLeast('admin')) {
      Flash::set('error', 'You do not have permission to view this profile.');
      redirect(BASE_URL);
      exit;
    }
    
    require_once '../app/models/User.php';
    $userModel = new User();

    $user = $userModel->first(['user_id' => $id]);
    
    if (!$user) {
      Flash::set('error', 'User not found.');
      redirect(BASE_URL);
      exit;
    }
    
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
      // Validate CSRF token first
      requireCSRFToken();
      
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
        // Check for duplicate email
        require_once '../app/models/User.php';
        $user = new User();
        
        $existing = $user->first(['user_email' => $_POST['user_email']]);
        if ($existing) {
          $errors['email_error'] = 'Email already registered.';
          $data = [
            'title' => 'Register',
            'description' => 'Create an account to access all features.',
            'errors' => $errors,
            'field_values' => $_POST
          ];
          $this->view('users/register', $data);
          exit;
        }
        
        // Save user to database
        $user->insert([
          'user_name' => $_POST['user_name'],
          'user_email' => $_POST['user_email'],
          'user_password' => password_hash($_POST['user_password'], PASSWORD_BCRYPT)
          
        ]);
        Flash::set('success', 'Registration successful. You can now log in.');
        redirect(BASE_URL . '/users/login');
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
      redirect(BASE_URL . '/dashboard');
      exit;
    }
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Validate CSRF token first
      requireCSRFToken();
      
      // Process form
      $user_email = $_POST['user_email'];
      $user_password = $_POST['user_password'];
      require_once '../app/models/User.php';
      $user = new User();
      $found_user = $user->get_row("SELECT * FROM `users` WHERE `user_email` = ?", [$user_email]);
      
      if ($found_user && password_verify($user_password, $found_user->user_password)) {
        // Login successful
        $_SESSION['user'] = $found_user;
        Flash::set('success', 'You have been logged in successfully.');
        redirect(BASE_URL . '/dashboard');
        exit;
      } else {
        // Login failed - log the attempt
        logError("Failed login attempt", ['email' => $user_email, 'ip' => $_SERVER['REMOTE_ADDR']], 'warning');
        
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