<?php

// Start session
session_start();

// Require composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Init Router
$router = new \App\Core\Router();

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Dispatch Request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
