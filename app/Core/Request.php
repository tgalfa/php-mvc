<?php

namespace App\Core;

class Request
{
    private array $routeParams = [];

    /**
     * Get the current HTTP method.
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Check if the current HTTP method is GET.
     */
    public function isGet(): bool
    {
        return 'get' === $this->method();
    }

    /**
     * Check if the current HTTP method is POST.
     */
    public function isPost(): bool
    {
        return 'post' === $this->method();
    }

    /**
     * Get the current request body.
     * Sanitize the request body.
     * This will prevent XSS attacks.
     */
    public function getBody(): array
    {
        $body = [];

        if ('get' === $this->method()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ('post' === $this->method()) {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    /**
     * Get the current URL path.
     */
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        // If there is no query string, return the path.
        if (false === $position) {
            return $path;
        }

        // Return the path up to the query string.
        return substr($path, 0, $position);
    }

    /**
     * Set the current route params.
     */
    public function setRouteParams($params)
    {
        $this->routeParams = $params;

        return $this;
    }

    /**
     * Get the current route params.
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * Get a specific route param.
     */
    public function getRouteParam($param, $default = null)
    {
        return $this->routeParams[$param] ?? $default;
    }
}
