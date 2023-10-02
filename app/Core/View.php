<?php

namespace App\Core;

use App\Core\Twig\TwigExtension;
use Twig\Environment;
use Twig\Lexer;
use Twig\Loader\FilesystemLoader;

class View
{
    /**
     * The title of the page.
     */
    public string $title = '';

    /**
     * The twig environment.
     */
    public Environment $twig;

    /**
     * Create a new View.
     */
    public function __construct()
    {
        // Load the Twig environment.
        $loader = new FilesystemLoader(Application::$ROOT_DIR . '/views');
        $this->twig = new Environment($loader);

        // Add the custom Twig extension.
        $this->twig->addExtension(new TwigExtension());

        // Set unique Twig tag blocks.
        $lexer = new Lexer($this->twig, [
            'tag_block' => ['{@', '}'],
        ]);

        $this->twig->setLexer($lexer);
    }

    /**
     * Render a view.
     */
    public function renderView(string $view, array $params = []): string
    {
        // Add global variables to the view.
        $params['baseUrl'] = Application::$BASE_URL;
        $params['siteName'] = $_ENV['APP_NAME'] ?? '';

        return $this->twig->render($view . '.php', $params);
    }
}
