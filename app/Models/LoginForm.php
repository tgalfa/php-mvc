<?php

namespace App\Models;

use App\Core\Application;
use App\Core\Model;

class LoginForm extends Model
{
    public string $username = '';
    public string $password = '';

    /**
     * Get the rules for the model.
     */
    public function rules(): array
    {
        return [
            'username' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED],
        ];
    }

    /**
     * Get the labels for the model.
     */
    public function labels(): array
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    /**
     * Attempt to log the user in.
     */
    public function login(): bool
    {
        $user = User::findOne(['name' => $this->username]);

        if (!$user) {
            $this->addError('username', 'User does not exist with this username');

            return false;
        }

        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Password is incorrect');

            return false;
        }

        return Application::$app->login($user);
    }
}
