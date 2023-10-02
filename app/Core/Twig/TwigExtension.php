<?php

namespace App\Core\Twig;

use App\Core\Application;
use App\Core\Form\Form;
use App\Core\Form\InputField;
use App\Core\Form\TextareaField;
use App\Core\Model;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    /**
     * Get the functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('openForm', [$this, 'openForm'], ['is_safe' => ['html']]),
            new TwigFunction('closeForm', [$this, 'closeForm'], ['is_safe' => ['html']]),
            new TwigFunction('submitButton', [$this, 'submitButton'], ['is_safe' => ['html']]),
            new TwigFunction('inputField', [$this, 'inputField'], ['is_safe' => ['html']]),
            new TwigFunction('textareaField', [$this, 'textareaField'], ['is_safe' => ['html']]),
            new TwigFunction('getSuccessFlashMessage', [$this, 'getSuccessFlashMessage'], ['is_safe' => ['html']]),
            new TwigFunction('getErrorFlashMessage', [$this, 'getErrorFlashMessage'], ['is_safe' => ['html']]),
            new TwigFunction('unescape', [$this, 'unescape'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Open a form.
     */
    public function openForm(string $action, string $method, string $id = null): string
    {
        return (new Form($action, $method, $id))->begin();
    }

    /**
     * Close a form.
     */
    public function closeForm(): string
    {
        return Form::end();
    }

    /**
     * Submit button.
     */
    public function submitButton(string $text): string
    {
        return Form::submitButton($text);
    }

    /**
     * Create a new input field.
     */
    public function inputField(Model $model, string $fieldName, string $type = null): string
    {
        $field = new InputField($model, $fieldName);

        if (in_array($type, [InputField::TYPE_NUMBER, InputField::TYPE_HIDDEN])) {
            $field->{$type . 'Field'}();
        }

        return $field->__toString();
    }

    /**
     * Create a new textarea field.
     */
    public function textareaField(Model $model, string $fieldName, int $rows = null): string
    {
        return (new TextareaField($model, $fieldName, $rows))->__toString();
    }

    /**
     * Get Success Flash Message.
     */
    public function getSuccessFlashMessage()
    {
        return Application::$app->session->getFlash('success');
    }

    /**
     * Get Error Flash Message.
     */
    public function getErrorFlashMessage()
    {
        return Application::$app->session->getFlash('error');
    }

    /**
     * Convert HTML entities to their corresponding characters.
     */
    public function unescape($value)
    {
        return html_entity_decode($value);
    }
}
