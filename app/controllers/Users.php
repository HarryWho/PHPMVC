<?php
// Must be accessed via the index.php front controller
// Other wise Exit and proceed no further
defined("ROOTPATH") or exit("Access Denied!");

class Users extends Controller
{

  #region User Profile
  public function profile($id = null)
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


    $userModel = self::GetRequiredUser();

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
  #endregion

  #region User Register
  public function register()
  {
    if (Auth::atLeast('member')) {
      redirect('/dashboard');
      exit;
    }
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Validate CSRF token first
      requireCSRFToken();

      // Validate input using new Validator class
      $validator = Validator::validate($_POST)
        ->required('user_name', 'Username')
        ->username('user_name')
        ->required('user_email', 'Email')
        ->email('user_email')
        ->required('user_password', 'Password')
        ->password('user_password', 8)
        ->required('confirm_password', 'Confirm Password')
        ->matches('user_password', 'confirm_password', 'Passwords');

      if ($validator->fails()) {
        dd($validator->getErrors());
        //die;
        $data = [
          'title' => 'Register',
          'description' => 'Create an account to access all features.',
          'errors' => $validator->getErrors(),
          'field_values' => $_POST
        ];
        $this->view('users/register', $data);
        exit;
      }

      // Check for duplicate email (database-level validation)

      $user = self::GetRequiredUser();

      $existing = $user->first(['user_email' => $_POST['user_email']]);
      if ($existing) {
        $validator->addError('user_email', 'Email already registered.');
        $data = [
          'title' => 'Register',
          'description' => 'Create an account to access all features.',
          'errors' => $validator->getErrors(),
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

    $data = [
      'title' => 'Register',
      'description' => 'Create an account to access all features.'
    ];
    $this->view('users/register', $data);
  }
  #endregion

  #region User Login
  public function login()
  {
    if (Auth::atLeast('member')) {
      redirect(BASE_URL . '/dashboard');
      exit;
    }
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Validate CSRF token first
      requireCSRFToken();

      // Check rate limiting before processing credentials
      if (isRateLimited()) {
        $remaining = getRateLimitRemaining();
        $minutes = ceil($remaining / 60);

        $data = [
          'title' => 'Login',
          'description' => 'Login to access your account.',
          'errors' => ['login_error' => "Too many failed login attempts. Please try again in $minutes minute(s)."]
        ];
        $this->view('users/login', $data);
        exit;
      }

      // Process form
      $user_email = $_POST['user_email'];
      $user_password = $_POST['user_password'];

      $user = self::GetRequiredUser();
      $found_user = $user->get_row("SELECT * FROM `users` WHERE `user_email` = ?", [$user_email]);

      if ($found_user) {
        if (password_verify($user_password, $found_user->user_password)) {
          // Login successful - clear rate limiting and regenerate session ID
          clearFailedAttempts();
          Auth::regenerateSessionId();
          $_SESSION['user'] = $found_user;
          $user = self::GetRequiredUser();
          $user->update(Auth::user()->user_id, ['user_last_login' => date("Y-m-d H:i:s")]);
          // Log successful login
          logError("Successful login", ['email' => $user_email, 'ip' => $_SERVER['REMOTE_ADDR']], 'info');

          Flash::set('success', 'You have been logged in successfully.');
          redirect(BASE_URL . '/dashboard');
          exit;
        } else {
          // Login failed - record the attempt for rate limiting
          recordFailedAttempt();
          logError("Failed login attempt", ['email' => $user_email, 'ip' => $_SERVER['REMOTE_ADDR']], 'warning');
          Flash::set('warning', 'The password is incorrect.');
          redirect('/users/login');
          exit;
        }
      } else {
        // Login failed - record the attempt for rate limiting
        recordFailedAttempt();
        logError("Failed login attempt", ['email' => $user_email, 'ip' => $_SERVER['REMOTE_ADDR']], 'warning');
        Flash::set('warning', 'That email is not registered.');
        redirect('/users/login');
        exit;
      }
    }

    $data = [
      'title' => 'Login',
      'description' => 'Login to access your account.'
    ];
    $this->view('users/login', $data);
  }
  #endregion

  #region User Logout
  public function logout()
  {
    Flash::set('success', 'You have been logged out successfully.');
    Auth::logout();
    redirect(BASE_URL . '/users/login');
    exit;
  }
  #endregion

  #region User Update
  public function update($id = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user = self::GetRequiredUser();
      $user->update($id, ['user_role' => $_POST['user_role']]);
      echo json_encode([
        "success" => true,
        "message" => "Role updated"
      ]);
      exit;
    }
  }

  #endregion

  #region User Delete
  public function delete($id = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user = self::GetRequiredUser();
      $user->delete($id);
      echo json_encode([
        "success" => true,
        "message" => "User Deleted"
      ]);
      exit;
    }
  }
  #endregion

  #region Require User files and return User Object
  private static function GetRequiredUser(): mixed
  {
    require_once '../app/models/User.php';
    return new User;
  }
  #endregion
}
