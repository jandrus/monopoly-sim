<?php


namespace Core;

use Core\Middleware\Middleware;

class Router {
    protected $routes = [];

    public function route(string $uri, string $method) {
        foreach ($this->routes as $route) {
            if ($route['uri'] == $uri && $route['method'] === strtoupper($method)) {
                Middleware::resolve($route['middleware']);
                return require(base_path($route['controller']));
            }
        }
        $this->abort();
    }

    public function only($key) {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;
        return $this;
    }

    public function get(string $uri, string $controller) {
        return $this->add($uri, $controller, 'GET');
    }

    public function post(string $uri, string $controller) {
        return $this->add($uri, $controller, 'POST');
    }

    public function delete(string $uri, string $controller) {
        return $this->add($uri, $controller, 'DELETE');
    }

    public function patch(string $uri, string $controller) {
        return $this->add($uri, $controller, 'PATCH');
    }

    public function put(string $uri, string $controller) {
        return $this->add($uri, $controller, 'PUT');
    }

    public function create(string $uri, string $controller) {
        return $this->add($uri, $controller, 'CREATE');
    }

    protected function abort(int $status_code=404): void {
        http_response_code($status_code);
        $view = base_path("views/{$status_code}.view.php");
        if (file_exists($view)) {
            require($view);
        } else {
            require(base_path('views/404.view.php'));
        }
        die();
    }

    protected function add(string $uri, string $controller, string $method) {
        $this->routes[] = [
          'uri' => $uri,
          'controller' => $controller,
          'method' => $method,
          'middleware' => null
        ];
        return $this;
    }
}
