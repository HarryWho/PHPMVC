<?php
defined("ROOTPATH") or exit("Access Denied!");

class Upload extends Controller
{
    public static function upload(): string
    {
        // Upload directory
        $uploadDir = BASE_URL .'/dist/img/uploads/';

        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Check if file was uploaded
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return ("No file uploaded or upload error.");
        }

        $file = $_FILES['image'];

        // Allowed MIME types
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ];

        // Validate MIME type using finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedTypes)) {
            return "Invalid file type.";
        }

        // Limit file size (5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return "File too large.";
        }

        // Generate safe random filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = bin2hex(random_bytes(16)) . '.' . $ext;

        // Move file
        $destination = $uploadDir . $newName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return "Failed to save file.";
        }

        return "Upload successful! Saved as: " . htmlspecialchars($newName);
    }
}
