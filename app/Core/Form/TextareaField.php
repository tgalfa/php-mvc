<?php

namespace App\Core\Form;

use App\Core\Model;

class TextareaField extends BaseField
{
    /**
     * Render the textarea field.
     */
    public function __construct(public Model $model, public string $attribute, public ?int $rows = null)
    {
        parent::__construct($model, $attribute);
    }

    /**
     * Render the textarea field.
     */
    public function renderField(): string
    {
        return sprintf(
            '
            <textarea id="%s" name="%s" placeholder="%s" class="%s" rows=%s>%s</textarea>',
            $this->attribute,
            $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->rows ?? 10,
            $this->model->{$this->attribute}
        );
    }
}
