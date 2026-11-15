<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class FileUploadHelper
{
//    /**
//     * Upload new file with UNIQUE name
//     * File can be pdf, image
//     *
//     * @param $file - File
//     * @param $existingFilePath - Existing storage path
//     * @param $storePath - storage path
//     * @return $fileName - new file name
//     */
//    public static function uploadFile($file, $existingFilePath, $storePath)
//    {
//        $storagePath = public_path(Config::get('helper.' . $storePath));
//        $previousImage = $storagePath . $existingFilePath;
//
//        if (File::exists($previousImage)) {
//            File::delete($previousImage);
//        }
//
//        $fileExt       = $file->extension();
//        $filePath      = $file->getClientOriginalName();
//        $fileName      = pathinfo($filePath, PATHINFO_FILENAME);
//        $fileFinalName = $fileName . '-' . date('Ymdhis') . rand(100000000000000, 99999999999999999) . '.' . $fileExt;
//        $file->move($storagePath, $fileFinalName);
//        return $fileFinalName;
//    }

    /**
     * Upload a file to a monthly folder automatically
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $existingFilePath
     * @param string $basePath
     * @return string $fileFinalName
     */
    public static function uploadFile($file, $existingFilePath = null, $basePath = 'uploads')
    {
        // Define allowed file types and extensions
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf',
                                // 'application/msword',                // .doc
                                // 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                // 'application/vnd.ms-excel',          // .xls
                                // 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // .xlsx
        ];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf',
                                // 'doc', 'docx', 'xls',  'xlsx',   'txt',
        ];
        // Validate file type and extension
        $fileMimeType = $file->getMimeType();
        $fileExtension = $file->getClientOriginalExtension();

        if (!in_array($fileMimeType, $allowedMimeTypes) || !in_array($fileExtension, $allowedExtensions)) {
            throw new \Exception('Invalid file type. Only JPG, PNG, GIF, and PDF files are allowed.');
        }

        // Sanitize the filename to prevent directory traversal attacks
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedFileName = preg_replace('/[^A-Za-z0-9\-_]/', '', $originalFileName);


        // Generate current month folder
        $monthYear = now()->format('FY'); // September2025
        $storePath = $basePath . '/' . $monthYear;

        // Full storage path
        $storagePath = public_path($storePath);

        // Create folder if it doesn't exist
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true); // recursive creation
        }

        // Delete previous file if exists
        if ($existingFilePath) {
            $previousImagePath = public_path($basePath . '/' . $existingFilePath);
            if (File::exists($previousImagePath)) {
                File::delete($previousImagePath);
            }
        }

        // Generate unique file name
        $fileFinalName = $sanitizedFileName . '-' . date('YmdHis') . rand(1000000000, 9999999999) . '.' . $fileExtension;

        // Move file
        $file->move($storagePath, $fileFinalName);

        // Return relative path to store in DB (optional: include monthly folder)
        return $monthYear . '/' . $fileFinalName;
    }
}
