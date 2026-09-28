<?php

namespace App\Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            $base = rtrim(env('APP_URL'), '/');
            header("Location: {$base}/login");
            exit;
        }
    }
}
