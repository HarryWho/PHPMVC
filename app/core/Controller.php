<?php
defined("ROOTPATH") or exit("Access Denied!");

class Controller
{
    protected function view($view, $data = [])
    {
        $user = Auth::user();
        $tasks = Messaging::getMessageType('tasks', ['task_ownerId' => $user->user_id, 'task_createdAt' => $user->user_last_login]);
        $messages = Messaging::getMessageType('messages', ['message_ownerId' => $user->user_id, 'message_createdAt' => $user->user_last_login]);
        $notifications = Messaging::getMessageType('notifications', ['notification_ownerId' => $user->user_id, 'notification_createdAt' => $user->user_last_login]);
        
        require_once '../app/views/includes/template_header.php';

        if (file_exists('../app/views/' . $view . '.view.php')) {
            require_once '../app/views/' . $view . '.view.php';
        } else {
            die('View does not exist');
        }

        require_once '../app/views/includes/template_footer.php';
    }
}