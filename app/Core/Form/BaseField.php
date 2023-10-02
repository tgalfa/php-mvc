<?php

namespace App\Core\Form;

use App\Core\Model;

abstract class BaseField
{
    /**
     * Render the field.
     */
    abstract public function renderField(): string;

    /**
     * Create a new field.
     */
    public function __construct(public Model $model, public string $attribute)
    {
    }

    /**
     * Render the field as a string and build the HTML.
     */
    public function __toString(): string
    {
        return sprintf(
            '
            <div class="input-group">
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>',
            $this->renderField(),
            $this->model->getFirstError($this->attribute)
        );
    }
}
