<?php

namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Middlewares\GuestMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Models\LoginForm;

class AuthController extends Controller
{
    /**
     * Create a new AuthController.
     */
    public function __construct()
    {
        $this->registerMiddleware(new GuestMiddleware([
            'login',
        ]));

        $this->registerMiddleware(new AuthMiddleware([
            'logout',
        ]));
    }

    /**
     * Get the login view or attempt to log the user in.
     * Only guests can access this view.
     */
    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();

        if ($request->isPost()) {
            // Attempt to log the user in.
            $loginForm->loadData($request->getBody());

            if ($loginForm->validation() && $loginForm->login()) {
                $response->redirect('/');

                return;
            }

            // If the login fails, show an error message.
            Application::$app->session->setFlash('error', 'Wrong Login Data');
        }

        return $this->render('login', [
            'model' => $loginForm,
            'title' => 'Login',
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();

        $response->redirect('/');
    }
}
