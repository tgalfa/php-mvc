<?php

namespace App\Core;

class Response
{
    /**
     * Set the response status code.
     */
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Redirect to the given path.
     */
    public function redirect(string $path): void
    {
        header("Location: $path");
    }
}
