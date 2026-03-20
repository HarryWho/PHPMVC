<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Base Controller class
 * Handles view rendering and data passing to templates
 */
class Controller
{
    /**
     * Render a view with data
     * 
     * Automatically loads:
     * - template_header.php
     * - {view}.view.php
     * - template_footer.php
     * 
     * Before rendering, loads messaging data for logged-in users
     * Uses QueryCache to avoid duplicate database queries
     *
     * @param string $view The view file path (without .view.php extension)
     * @param array $data Associative array of data to pass to the view
     * @return void
     * @throws Exception If view file doesn't exist
     */
    protected function view(string $view, array $data = []): void
    {
        if (isLoggedIn()) {


            // Load messaging data for the current user
            // Uses query cache to avoid duplicate queries
            $tasks = QueryCache::remember(
                'tasks_' . Auth::user()->user_id,
                fn() => NavbarLoader::getMessageType('tasks', ['task_ownerId' => Auth::user()->user_id])
            );

            $messages = QueryCache::remember(
                'messages_' . Auth::user()->user_id,
                fn() => NavbarLoader::getMessageType('messages', ['message_ownerId' => Auth::user()->user_id])
            );

            $notifications = QueryCache::remember(
                'notifications_' . Auth::user()->user_id,
                fn() => NavbarLoader::getMessageType('notifications', ['notification_ownerId' => Auth::user()->user_id, "notify_everyone" => '0'])
            );

            // Load admin data if user is admin


            // Make messaging data available in views
            $data['tasks'] = $tasks ?? [];
            $data['messages'] = $messages ?? [];
            $data['notifications'] = $notifications ?? [];

            // For debugging: show cache stats if in debug mode
            if (DEBUG) {
                logError(
                    "Cache stats after view loading",
                    [
                        'cache_size' => QueryCache::getCacheSize(),
                        'cached_keys' => QueryCache::getCacheKeys()
                    ],
                    'debug'
                );
            }
        }

        require_once '../app/views/includes/template_header.php';

        $viewPath = '../app/views/' . $view . '.view.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            logError("View not found", ['view' => $view], 'error');
            die('View does not exist: ' . htmlspecialchars($view));
        }

        require_once '../app/views/includes/template_footer.php';
    }
}
