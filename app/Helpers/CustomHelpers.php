<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class CustomHelpers
{

    /**
     * Get id from slug url
     * ex: /senior-software-engineer-2
     * @param $slugId
     * @return $id
     */
    public static function getIdFromSlug($slugId)
    {
        // Split the slugId by the last hyphen
        $lastHyphenPos = strrpos($slugId, '-');
        if ($lastHyphenPos === false) {
            abort(404); // Not found
        }

        $id = substr($slugId, $lastHyphenPos + 1);

        // Check if the ID is a valid number
        if (!is_numeric($id)) {
            abort(404); // Not found
        }

        return (int) $id;
    }

    /**
     * Filename format
     * @param $str - 'software----engineer---2'
     * @return - 'software-engineer-2'
     */
    public static function formatFileName($str)
    {
        $str  = strip_tags(trim($str));
        $str  = preg_replace('/[^a-zA-Z0-9-_ ]/', '', $str);
        $str  = preg_replace("/\s+/", "-", $str);
        $str  = str_replace('_', '-', $str);
        $str  = str_replace('---', '-', $str);
        $str  = str_replace('--', '-', $str);
        $str  = str_replace('-', '-', $str);
        $str  = strtolower($str);
        return $str;
    }

    /**
     * Return file badge HTML with icon and color
     *
     * @param string $extension
     * @return string
     */
    public static function fileIcon(string $extension): string
    {
        $ext = strtolower($extension);

        switch ($ext) {
            case 'pdf':
                $icon = 'fa fa-file-pdf-o';
                $color = '#e74c3c';
                break;
            case 'doc':
            case 'docx':
                $icon = 'fa fa-file-word-o';
                $color = '#3498db';
                break;
            case 'xls':
            case 'xlsx':
                $icon = 'fa fa-file-excel-o';
                $color = '#27ae60';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'webp':
            case 'svg':
                $icon = 'fa fa-file-image-o';
                $color = '#f1c40f';
                break;
            case 'mp3':
                $icon = 'fa fa-file-audio-o';
                $color = '#9b59b6';
                break;
            case 'mp4':
                $icon = 'fa fa-file-video-o';
                $color = '#e67e22';
                break;
            default:
                $icon = 'fa fa-file-o';
                $color = '#7f8c8d';
                break;
        }

        // Return HTML
        return '<span class="file-badge" style="color:' . $color . '; border: 0px solid' . $color . '; font-size:12px; padding:2px 8px; border-radius:4px; display:inline-block; margin:2px;">' .
            '<i class="' . $icon . '"></i> ' . htmlspecialchars($ext) .
            '</span>';
    }
}
