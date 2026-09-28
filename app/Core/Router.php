<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute($method, $uri, $action)
    {
        // Convert route params {id} to regex
        $uri = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $uri);
        $uri = '#^' . $uri . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function dispatch($requestUri, $requestMethod)
    {
        // Remove query string from URI
        $uri = parse_url($requestUri, PHP_URL_PATH);
        
        // Remove base path if application is in subfolder
        $basePath = parse_url(env('APP_URL'), PHP_URL_PATH);
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        if ($uri === '' || $uri === false) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($route['uri'], $uri, $matches)) {
                
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                $action = $route['action'];

                if (is_callable($action)) {
                    return call_user_func_array($action, array_values($params));
                }

                if (is_array($action) && count($action) == 2) {
                    $controller = new $action[0]();
                    $method = $action[1];
                    return call_user_func_array([$controller, $method], array_values($params));
                }
            }
        }

        // 404
        http_response_code(404);
        echo "404 Not Found";
        exit;
    }
}
