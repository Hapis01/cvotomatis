<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class DashboardController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        ob_start();
        require_once __DIR__ . '/../../resources/views/dashboard/index.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Dashboard',
            'content' => $content
        ]);
    }
}
