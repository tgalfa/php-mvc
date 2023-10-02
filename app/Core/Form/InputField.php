<?php

namespace App\Core\Form;

use App\Core\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_HIDDEN = 'hidden';

    /**
     * The field type.
     * Defaults to text.
     */
    public string $type;

    /**
     * Create a new field.
     */
    public function __construct(public Model $model, public string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    /**
     * Set the field type to password.
     */
    public function passwordField(): InputField
    {
        $this->type = self::TYPE_PASSWORD;

        return $this;
    }

    /**
     * Set the field type to number.
     */
    public function numberField(): InputField
    {
        $this->type = self::TYPE_NUMBER;

        return $this;
    }

    /**
     * Set the field type to hidden.
     */
    public function hiddenField(): InputField
    {
        $this->type = self::TYPE_HIDDEN;

        return $this;
    }

    /**
     * Render the input field.
     */
    public function renderField(): string
    {
        return sprintf(
            '
            <input type="%s" id="%s" name="%s" placeholder="%s" value="%s" class="%s">',
            $this->type,
            $this->attribute,
            $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : ''
        );
    }
}
