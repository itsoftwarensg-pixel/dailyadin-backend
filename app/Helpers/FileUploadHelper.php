<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FileUploadHelper
{
    /** SVG is deliberately excluded: uploaded SVG can execute script in browsers. */
    private const MIME_EXTENSIONS = [
        'image/jpeg' => ['jpg', 'jpeg'], 'image/png' => ['png'], 'image/gif' => ['gif'],
        'image/webp' => ['webp'], 'application/pdf' => ['pdf'],
        'application/msword' => ['doc'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        'application/vnd.ms-excel' => ['xls'],
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
        'audio/mpeg' => ['mp3'], 'video/mp4' => ['mp4'],
    ];

    public static function uploadFile(UploadedFile $file, ?string $existingFilePath = null, string $basePath = 'uploads'): string
    {
        if (!$file->isValid()) throw new \InvalidArgumentException('The uploaded file is invalid.');
        $mime = (string) $file->getMimeType();
        $extension = strtolower((string) $file->getClientOriginalExtension());
        if (!isset(self::MIME_EXTENSIONS[$mime]) || !in_array($extension, self::MIME_EXTENSIONS[$mime], true)) {
            throw new \InvalidArgumentException('Unsupported or unsafe file type.');
        }

        $monthYear = now()->format('FY');
        $directory = public_path(trim($basePath, '/').'/'.$monthYear);
        File::ensureDirectoryExists($directory, 0755, true);
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name) ?: 'upload';
        $fileName = $name.'-'.bin2hex(random_bytes(16)).'.'.$extension;
        $file->move($directory, $fileName);

        // Do not delete the previous file until the replacement is safely stored.
        if ($existingFilePath) self::deleteFile($existingFilePath, $basePath);
        return $monthYear.'/'.$fileName;
    }

    /** Delete only a relative path that resolves inside this upload root. */
    public static function deleteFile(?string $relativePath, string $basePath = 'uploads'): void
    {
        if (!$relativePath || str_contains($relativePath, "\0")) return;
        $root = realpath(public_path(trim($basePath, '/')));
        if ($root === false) return;
        $candidate = realpath($root.DIRECTORY_SEPARATOR.ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath), DIRECTORY_SEPARATOR));
        if ($candidate === false || !str_starts_with($candidate, $root.DIRECTORY_SEPARATOR) || !is_file($candidate)) return;
        File::delete($candidate);
    }
}
