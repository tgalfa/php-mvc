<?php

namespace App\Core;

use App\Core\Middlewares\BaseMiddleware;

class Controller
{
    /**
     * The controller action.
     */
    public string $action = '';

    /**
     * The controller middlewares.
     */
    protected array $middlewares = [];

    /**
     * Render a view.
     */
    public function render(string $view, array $params = []): string
    {
        return Application::$app->view->renderView($view, $params);
    }

    /**
     * Register a middleware.
     */
    public function registerMiddleware(BaseMiddleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Get all the registered middlewares.
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
