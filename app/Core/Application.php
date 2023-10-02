<?php

namespace App\Core;

class Application
{
    public static Application $app;
    public static string $ROOT_DIR;
    public static string $BASE_URL;

    /**
     * The application user class.
     */
    public string $userClass;

    /**
     * The application controller.
     */
    public ?Controller $controller = null;
    /**
     * The application database.
     */
    public Database $db;
    /**
     * The application request.
     */
    public Request $request;
    /**
     * The application response.
     */
    public Response $response;
    /**
     * The application router.
     */
    public Router $router;
    /**
     * The application session.
     */
    public Session $session;
    /**
     * The application user.
     */
    public ?DbModel $user;
    /**
     * The application view.
     */
    public View $view;

    /**
     * Create a new Application.
     */
    public function __construct(string $rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$BASE_URL = $config['baseUrl'] ?? '';
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);

        $this->view = new View();

        $this->user = null;
        $this->userClass = $config['userClass'] ?? '';

        $this->db = new Database($config['db']);

        // Get the primary value from the session.
        $primaryValue = $this->session->get('user');

        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            // Find the user by the primary key.
            $this->user = $this->userClass::findOne([
                $primaryKey => $primaryValue,
            ]);
        }
    }

    /**
     * Is the user a guest?
     */
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }

    /**
     * Is the user logged in?
     */
    public static function isLoggedIn(): bool
    {
        return false === self::isGuest();
    }

    /**
     * Get the controller.
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * Set the controller.
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Login the user.
     */
    public function login(DbModel $user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $value = $user->{$primaryKey};
        $this->session->set('user', $value);

        return true;
    }

    /**
     * Logout the user.
     */
    public function logout(): void
    {
        $this->user = null;
        $this->session->remove('user');
    }

    /**
     * Run the application.
     */
    public function run(): void
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            // Clear flash messages.
            $this->session->setFlash('error', '');
            $this->session->setFlash('success', '');
            echo Application::$app->view->renderView('_error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
