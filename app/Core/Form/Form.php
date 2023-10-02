<?php

namespace App\Core\Form;

class Form
{
    /**
     * Create a new form.
     */
    public function __construct(protected string $action, protected string $method, protected ?string $id = null)
    {
    }

    /**
     * Open a form.
     */
    public function begin(): string
    {
        $id = $this->id ? sprintf('id="%s"', $this->id) : '';

        return sprintf('<form action="%s" method="%s" %s>', $this->action, $this->method, $id);
    }

    /**
     * Close a form.
     */
    public static function end(): string
    {
        return '</form>';
    }

    /**
     * Submit button.
     */
    public static function submitButton(string $text): string
    {
        return sprintf('<button type="submit" class="btn btn-primary">%s</button>', $text);
    }
}
