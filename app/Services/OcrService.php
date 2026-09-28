<?php

namespace App\Services;

class OcrService
{
    public static function extractFromImage($imagePath)
    {
        $realPath = realpath($imagePath);
        if (!$realPath || !file_exists($realPath)) {
            return false;
        }

        $psScript = __DIR__ . '/../Helpers/ocr.ps1';
        $psScript = realpath($psScript);

        $cmd = 'powershell.exe -NoProfile -ExecutionPolicy Bypass -File ' . escapeshellarg($psScript) . ' -ImagePath ' . escapeshellarg($realPath);
        
        $output = [];
        $returnVar = 0;
        exec($cmd, $output, $returnVar);

        if ($returnVar === 0 && !empty($output)) {
            return implode("\n", $output);
        }

        return false;
    }
}
