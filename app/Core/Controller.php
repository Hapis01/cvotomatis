<?php

namespace App\Core;

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        
        $viewFile = __DIR__ . '/../../resources/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View does not exist: " . $view);
        }
    }

    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url)
    {
        if (preg_match('/^https?:\/\//i', $url)) {
            header("Location: {$url}");
        } else {
            $base = rtrim(env('APP_URL') ?? '', '/');
            header("Location: {$base}{$url}");
        }
        exit;
    }
}
