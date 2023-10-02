<?php

namespace App\Core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    /**
     * The model errors.
     */
    public array $errors = [];

    /**
     * Rules for the model.
     */
    abstract public function rules(): array;

    /**
     * Load data from an array into the model.
     */
    public function loadData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Validate the model.
     */
    public function validation(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};

            foreach ($rules as $rule) {
                $ruleName = is_string($rule) ? $rule : $rule[0];

                $this->validateRule($attribute, $value, $ruleName, is_array($rule) ? $rule : []);
            }
        }

        return empty($this->errors);
    }

    /**
     * Add an error message to the model.
     */
    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute][] = $message;
    }

    /**
     * Add an error message to the model for the given rule.
     */
    public function addErrorForRule(string $attribute, string $rule, array $params = []): void
    {
        $message = $this->errorMessages()[$rule] ?? '';

        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    /**
     * Return all error messages for the model.
     */
    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required.',
            self::RULE_EMAIL => 'This field must be a valid email address.',
            self::RULE_MIN => 'This field must be at least {min} characters long.',
            self::RULE_MAX => 'This field must be at most {max} characters.',
            self::RULE_MATCH => 'This field must match {match}.',
            self::RULE_UNIQUE => 'Record with this {field} already exists.',
        ];
    }

    /**
     * Return the first error message for the given attribute.
     */
    public function getFirstError(string $attribute): string
    {
        return $this->errors[$attribute][0] ?? '';
    }

    /**
     * Return whether the given attribute has an error.
     */
    public function hasError(string $attribute): bool
    {
        return !empty($this->errors[$attribute]);
    }

    /**
     * Return error messages for the given attribute.
     */
    public function getError(string $attribute): array
    {
        return $this->errors[$attribute] ?? [];
    }

    /**
     * Return all error messages.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Return whether the given attribute has an error.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Return the labels for the model.
     */
    public function labels(): array
    {
        return [];
    }

    /**
     * Return the label for the given attribute.
     */
    public function getLabel(string $attribute): string
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    /**
     * Validate the given rule.
     */
    private function validateRule(string $attribute, mixed $value, string $ruleName, array $parameters = []): bool
    {
        switch ($ruleName) {
            case self::RULE_REQUIRED:
                return $this->validateRequired($attribute, $value);
            case self::RULE_EMAIL:
                return $this->validateEmail($attribute, $value);
            case self::RULE_MIN:
                return $this->validateMin($attribute, $value, $parameters);
            case self::RULE_MAX:
                return $this->validateMax($attribute, $value, $parameters);
            case self::RULE_MATCH:
                return $this->validateMatch($attribute, $value, $parameters);
            case self::RULE_UNIQUE:
                return $this->validateUnique($attribute, $value, $parameters);
            default:
                return true; // Default to true for unknown rules
        }
    }

    /**
     * Validate the required rule.
     */
    private function validateRequired(string $attribute, mixed $value): bool
    {
        if (empty($value)) {
            $this->addErrorForRule($attribute, self::RULE_REQUIRED);
            return false;
        }
        return true;
    }

    /**
     * Validate the email rule.
     */
    private function validateEmail(string $attribute, mixed $value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addErrorForRule($attribute, self::RULE_EMAIL);
            return false;
        }
        return true;
    }

    /**
     * Validate the min rule.
     */
    private function validateMin(string $attribute, mixed $value, array $parameters): bool
    {
        if (strlen($value) < $parameters['min']) {
            $this->addErrorForRule($attribute, self::RULE_MIN, $parameters);
            return false;
        }
        return true;
    }

    /**
     * Validate the max rule.
     */
    private function validateMax(string $attribute, mixed $value, array $parameters): bool
    {
        if (strlen($value) > $parameters['max']) {
            $this->addErrorForRule($attribute, self::RULE_MAX, $parameters);
            return false;
        }
        return true;
    }

    /**
     * Validate the match rule.
     */
    private function validateMatch(string $attribute, mixed $value, array $parameters): bool
    {
        if ($value !== $this->{$parameters['match']}) {
            $this->addErrorForRule($attribute, self::RULE_MATCH, $parameters);
            return false;
        }
        return true;
    }

    /**
     * Validate the unique rule.
     */
    private function validateUnique(string $attribute, mixed $value, array $parameters): bool
    {
        $className = $parameters['class'];
        $uniqueAttribute = $parameters['attribute'] ?? $attribute;
        $tableName = $className::tableName();

        $statement = Application::$app->db->prepare(
            "SELECT * FROM $tableName WHERE $uniqueAttribute = :$uniqueAttribute"
        );
        $statement->bindValue(":$uniqueAttribute", $value);
        $statement->execute();

        $record = $statement->fetchObject();

        if ($record) {
            $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
            return false;
        }

        return true;
    }
}
