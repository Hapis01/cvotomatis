<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class ExportService
{
    public function exportPdf($html, $filename = 'document.pdf')
    {
        while(ob_get_level() > 0) ob_end_clean();
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        // Otomatis simpan salinan langsung ke folder Downloads/lamaran
        self::saveToDownloadsLamaran($filename, $pdfOutput);
        
        $dompdf->stream($filename, ["Attachment" => true]);
        exit;
    }

    public static function getDownloadsLamaranDir()
    {
        $possibleDirs = [
            'C:/Users/Hapiss/Downloads/lamaran',
            'C:\\Users\\Hapiss\\Downloads\\lamaran',
            rtrim(getenv('USERPROFILE') ?: 'C:/Users/Hapiss', '\\/') . '/Downloads/lamaran'
        ];

        foreach ($possibleDirs as $d) {
            if (is_dir($d)) return $d;
        }

        $fallback = (getenv('USERPROFILE') ?: 'C:/Users/Hapiss') . '/Downloads/lamaran';
        if (!is_dir($fallback)) {
            @mkdir($fallback, 0755, true);
        }
        return is_dir($fallback) ? $fallback : null;
    }

    public static function saveToDownloadsLamaran($filename, $content)
    {
        $dir = self::getDownloadsLamaranDir();
        if (!$dir) return false;

        $targetFile = $dir . DIRECTORY_SEPARATOR . $filename;
        @file_put_contents($targetFile, $content);

        self::copyToMatchingSubfolder($dir, $filename, $targetFile);

        return true;
    }

    private static function copyToMatchingSubfolder($dir, $filename, $sourceFile)
    {
        if (!file_exists($sourceFile)) return;

        $subfolders = @scandir($dir) ?: [];
        $fnLower = strtolower($filename);

        foreach ($subfolders as $sub) {
            if ($sub === '.' || $sub === '..') continue;
            $subPath = $dir . DIRECTORY_SEPARATOR . $sub;
            if (is_dir($subPath)) {
                $subLower = strtolower($sub);
                $isMatch = false;

                // 1. Business Analyst / System Analyst / Project Admin
                if ((str_contains($fnLower, 'business analyst') || str_contains($fnLower, 'system analyst') || str_contains($fnLower, 'project admin') || str_contains($fnLower, 'bisnis')) 
                    && (str_contains($subLower, 'bisnis') || str_contains($subLower, 'it bisnis'))) {
                    $isMatch = true;
                }
                // 2. Software Engineer / Programmer
                elseif (str_contains($fnLower, 'software engineer') && (str_contains($subLower, 'software engineer') || str_contains($subLower, 'software'))) {
                    $isMatch = true;
                }
                // 3. Web Developer / Frontend / Fullstack
                elseif ((str_contains($fnLower, 'web developer') || str_contains($fnLower, 'full-stack') || str_contains($fnLower, 'fullstack')) && (str_contains($subLower, 'web developer') || str_contains($subLower, 'fullstack it') || str_contains($subLower, 'web it'))) {
                    $isMatch = true;
                }
                // 4. IT Support / Hardware
                elseif ((str_contains($fnLower, 'it support') || str_contains($fnLower, 'hardware')) && (str_contains($subLower, 'hardware') || str_contains($subLower, 'it staff'))) {
                    $isMatch = true;
                }

                if ($isMatch) {
                    @copy($sourceFile, $subPath . DIRECTORY_SEPARATOR . $filename);
                }
            }
        }
    }
}
