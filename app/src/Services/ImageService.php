<?php

namespace App\Services;

class ImageService
{
    // what type of images to accept
    private const ALLOWED_MIME_TYPES = [
        'image/png',
        'image/jpeg',
        'image/jpg',
    ];
    //
    private const ALLOWED_EXTENSIONS = [
        'png',
        'jpg',
        'jpeg',
    ];
    //max size of the image
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB

    private string $uploadDir;
    private string $webPathPrefix;

    public function __construct()
    {
        $this->uploadDir = realpath(__DIR__ . '/../../public/assets/img');
        if ($this->uploadDir === false) {
            $this->uploadDir = __DIR__ . '/../../public/assets/img';
        }
        $this->uploadDir = rtrim($this->uploadDir, DIRECTORY_SEPARATOR);
        $this->webPathPrefix = '/assets/img/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function uploadTemplateImage(array $file, ?string $existingImage = null): string
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            throw new \InvalidArgumentException('No image file was uploaded.');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->getUploadErrorMessage($file['error']));
        }
        //checks if the image is larger than the limit set
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new \RuntimeException('Image file is too large. Maximum allowed size is 5 MB.');
        }
        //checks if the file info (finfo) is valid from the options i set otherwise throw an error
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException('Invalid image type. Allowed types: png, jpg, jpeg');
        }
        //get the extension png/jpg ect
        $extension = $this->getFileExtension($file['name']);
        if ($extension === null || !in_array(strtolower($extension), self::ALLOWED_EXTENSIONS, true)) {
            throw new \RuntimeException('Invalid image extension.');
        }
        //generates a name for the file. unique id incase the same photo is used
        $filename = $this->generateFileName($file['name'], $extension);
        //sends it to the dir
        $destination = $this->uploadDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('Failed to move uploaded image.');
        }

        if ($existingImage) {
            $this->deleteImage($existingImage);
        }

        return $filename;
    }

    public function deleteImage(string $filename): bool
    {
        $file = $this->uploadDir . DIRECTORY_SEPARATOR . basename($filename);
        if (is_file($file)) {
            return unlink($file);
        }

        return false;
    }

    private function getFileExtension(string $filename): ?string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        return $extension !== '' ? $extension : null;
    }

    private function generateFileName(string $originalName, string $extension): string
    {
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $hash = bin2hex(random_bytes(8));
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        return sprintf('%s_%s.%s', $safeName, $hash, strtolower($extension));
    }

    //sends different errors for each case
    private function getUploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Uploaded image is too large.',
            UPLOAD_ERR_PARTIAL => 'Image was only partially uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder for uploads.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write image file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the image upload.',
            default => 'Image upload failed with an unknown error.',
        };
    }
}
