<?php
defined("ROOTPATH") or exit("Access Denied!");

function dd($stuff){
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

function esc($str){
    return htmlspecialchars($str);
}


#region Check for required php extensions
function check_extensions(){
    $required_extensions = [
        'gd',
        'mysqli'
    ];
    $not_loaded = [];
    foreach($required_extensions as $ext){
        if(!extension_loaded($ext)){
            $not_loaded[] = $ext;
        }
    }
    if(!empty($not_loaded)){
        dd("Please load the following extensions in your php.ini file: <br>". implode('<br>', $not_loaded));
        die();
    }
}

check_extensions();
#endregion

#region Redirect

function redirect($url){
    header("Location: $url");
    exit;
}   
#endregion

#region Authentication
function isLoggedIn(){
    return isset($_SESSION['user']);
}

function isMember(){
    return isLoggedIn() && $_SESSION['user']->user_role === 'member';
}

#endregion

#region Registration Form Checks
function validateRegistration($data){
    $errors = [];
   
    // Check for empty fields
    
    if (empty($data['user_email'])) {
        $errors['email_error'] = 'Email is required.';
    } elseif (!filter_var($data['user_email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_error'] = 'Invalid email format.';
    }
    if (!empty($errors['email_error'])) {
        Flash::set('danger', $errors['email_error']);
    }
    if (strlen($data['user_password']) < 6) {
        $errors['password_error'] = 'Password must be at least 6 characters.';
    }
    if ($data['user_password'] !== $data['confirm_password']) {
        $errors['password_error'] = 'Passwords do not match.';
    }

    if (!empty($errors['password_error'])) {
        Flash::set('danger', $errors['password_error']);
    }

    return $errors;
}

#endregion

