<?php
defined("ROOTPATH") or exit("Access Denied!");

class Controller
{
    protected function view($view, $data = [])
    {
        if (isLoggedIn()) {
            $user = Auth::user();
            $tasks = Messaging::getMessageType('tasks', ['task_ownerId' => $user->user_id]);
            $messages = Messaging::getMessageType('messages', ['message_ownerId' => $user->user_id]);
            $notifications = Messaging::getMessageType('notifications', ['notification_ownerId' => $user->user_id]);
            $adminUsers = Messaging::getUsers();
        }
        require_once '../app/views/includes/template_header.php';

        if (file_exists('../app/views/' . $view . '.view.php')) {
            require_once '../app/views/' . $view . '.view.php';
        } else {
            die('View does not exist');
        }

        require_once '../app/views/includes/template_footer.php';
    }
}
