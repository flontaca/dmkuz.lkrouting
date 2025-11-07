<?php

namespace Dmkuz\Lkrouting\Service;

class StaticService
{
    public function showStatic(string $filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $this->setContentType($extension);

        if (in_array($extension, ['mp4', 'webm', 'avi', 'mov', 'wmv', 'mpeg', 'mpg', 'ogv', '3gp', '3g2'])) {
            $this->streamVideo($filePath);
        } else {
            readfile($filePath);
        }
    }

    protected function setContentType(string $extension): void
    {
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'html' => 'text/html; charset=utf-8',
            'htm' => 'text/html; charset=utf-8',
            'txt' => 'text/plain; charset=utf-8',
            // Видео типы
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'wmv' => 'video/x-ms-wmv',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'ogv' => 'video/ogg',
            '3gp' => 'video/3gpp',
            '3g2' => 'video/3gpp2',
        ];


        if (isset($contentTypes[$extension])) {
            header('Content-Type: ' . $contentTypes[$extension]);
        } else {
            header('Content-Type: application/octet-stream');
        }
    }

    protected function streamVideo($filePath)
    {
        $filesize = filesize($filePath);
        $file = fopen($filePath, 'rb');

        // Поддержка byte-range requests (для перемотки видео)
        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            $range = str_replace('bytes=', '', $range);
            $range = explode('-', $range);

            $start = intval($range[0]);
            $end = $range[1] === '' ? $filesize - 1 : intval($range[1]);

            if ($end >= $filesize) {
                $end = $filesize - 1;
            }

            $length = $end - $start + 1;

            header('HTTP/1.1 206 Partial Content');
            header("Content-Range: bytes $start-$end/$filesize");
            header("Content-Length: $length");

            fseek($file, $start);
            echo fread($file, $length);
        } else {
            header("Content-Length: $filesize");
            fpassthru($file);
        }

        fclose($file);
    }

}