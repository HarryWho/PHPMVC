<?php
defined("ROOTPATH") or exit("Access Denied!");

class Uploads extends Controller
{
    public static function upload(): void
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);

        // Use proper server path
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/dist/img/uploads/';

        $debug = [
            'uploadDir' => $uploadDir,
            'directoryExists' => is_dir($uploadDir),
            'directoryWritable' => is_writable($uploadDir)
        ];

        // Create directory if missing
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create upload directory',
                    'debug' => $debug
                ]);
                exit;
            }
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'No file or upload error']);
            exit;
        }

        $file = $_FILES['image'];

        // ... keep your mime type and size checks the same ...

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = bin2hex(random_bytes(16)) . '.' . $ext;
        $destination = $uploadDir . $newName;

        $debug['destination'] = $destination;
        $debug['tmp_name_exists'] = file_exists($file['tmp_name']);

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $debug['move_error'] = error_get_last();
            echo json_encode([
                'success' => false,
                'message' => 'Failed to save file.',
                'debug' => $debug
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Upload successful',
            'messageBody' => $newName,
            'debug' => $debug
        ]);
        exit;
    }
}
