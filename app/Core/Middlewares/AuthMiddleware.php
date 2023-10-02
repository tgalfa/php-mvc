<?php

namespace App\Core\Middlewares;

use App\Core\Application;
use App\Core\Exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    /**
     * Create a new AuthMiddleware.
     */
    public function __construct(protected array $actions = [])
    {
    }

    /**
     * Execute the middleware.
     * For each action in the actions array, check if the user is a guest.
     * If the user is a guest, throw a ForbiddenException.
     *
     * @throws ForbiddenException
     */
    public function execute(): void
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
