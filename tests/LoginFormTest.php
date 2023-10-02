<?php

namespace Tests;

use App\Models\LoginForm;

class LoginFormTest extends TestCase
{
    /**
     * Test login with invalid credentials.
     */
    public function testFailedLogin()
    {
        $loginForm = new LoginForm();

        $loginForm->username = 'username';
        $loginForm->password = 'mypassword';

        $this->assertFalse($loginForm->login());
    }

    /**
     * Test login with valid credentials.
     */
    public function testSuccessfulLogin()
    {
        $loginForm = new LoginForm();

        $loginForm->username = 'admin';
        $loginForm->password = 'password';

        $this->assertTrue($loginForm->login());
    }
}
