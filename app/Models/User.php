<?php

namespace App\Models;

use App\Core\DbModel;

class User extends DbModel
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETED = 2;

    public string $email = '';
    public string $name = '';
    public string $password = '';
    public int $status = self::STATUS_INACTIVE;

    /**
     * Get the table associated with the model.
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * Attributes for mass assignment.
     */
    public function attributes(): array
    {
        return ['email', 'name', 'password', 'status'];
    }

    /**
     * Labels for attributes.
     */
    public function labels(): array
    {
        return [
            'email' => 'Email',
            'name' => 'Name',
            'password' => 'Password',
        ];
    }

    /**
     * Get the rules for the model.
     */
    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class,
            ]],
            'name' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 3], [self::RULE_MAX, 'max' => 255]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [[self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    /**
     * Save the model to the database.
     */
    public function save(): bool
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        return parent::save();
    }
}
