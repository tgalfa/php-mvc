<?php

namespace App\Core;

use App\Core\Exception\NotFoundException;

class Router
{
    /**
     * The routes array.
     */
    protected array $routes = [];

    /**
     * Create a new Router.
     */
    public function __construct(protected Request $request, protected Response $response)
    {
    }

    /**
     * Get the current URL path.
     */
    public function get(string $path, mixed $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * Post the current URL path.
     */
    public function post(string $path, mixed $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * Resolve the current URI.
     *
     * @throws NotFoundException
     */
    public function resolve(): string
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        // Check if the path matches with any of the defined routes which contain a parameter.
        if (false === $callback) {
            foreach ($this->routes[$method] as $route => $value) {
                // Check if the route contains a parameter.
                if (false !== strpos($route, '{')) {
                    // Replace the parameter with a regex.
                    $route = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<\1>[a-zA-Z0-9-]+)', $route);
                    $route = rtrim($route, '/');
                    $regex = "#^$route$#";

                    // Check if the path matches the regex.
                    if (preg_match($regex, $path, $matches)) {
                        // Remove the first match.
                        array_shift($matches);
                        $callback = $value;
                        $this->request->setRouteParams($matches);
                        break;
                    }
                }
            }
        }

        // If the callback is false, throw a NotFoundException.
        if (false === $callback) {
            throw new NotFoundException();
        }

        // If the callback is a string, render the view.
        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        // If the callback is an array, create a new instance of the controller.
        // Set the controller action to the callback.
        // Set the callback to the controller.
        // Execute the middlewares.
        if (is_array($callback)) {
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}
