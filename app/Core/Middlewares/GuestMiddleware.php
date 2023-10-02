<?php

namespace App\Core\Middlewares;

use App\Core\Application;

class GuestMiddleware extends BaseMiddleware
{
    /**
     * Create a new GuesMiddleware.
     */
    public function __construct(protected array $actions = [])
    {
    }

    /**
     * Execute the middleware.
     * For each action in the actions array, check if the user is a guest.
     * If the user is logged in redirect to the news page.
     *
     * @throws ForbiddenException
     */
    public function execute(): void
    {
        if (Application::isLoggedIn()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                Application::$app->response->redirect('/news');
            }
        }
    }
}
