<?php

namespace App\Core;

class Session
{
    protected const FLASH_MESSAGES = 'flash_messages';

    /**
     * Create a new Session.
     */
    public function __construct()
    {
        // Start session if not already started.
        session_status() === PHP_SESSION_NONE && session_start();

        // Get the flash messages from the session.
        $flashMessages = $_SESSION[self::FLASH_MESSAGES] ?? [];
        // Set the flash messages to be removed.
        // This will remove the flash messages from the session when the session is destroyed.
        foreach ($flashMessages as &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_MESSAGES] = $flashMessages;
    }

    /**
     * Set a value in the session.
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a value from the session.
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * Remove a value from the session.
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Set a flash message in the session.
     */
    public function setFlash(string $key, string $message): void
    {
        $_SESSION[self::FLASH_MESSAGES][$key] = [
            'remove' => false,
            'value' => $message,
        ];
    }

    /**
     * Get a flash message from the session.
     */
    public function getFlash(string $key): string
    {
        return $_SESSION[self::FLASH_MESSAGES][$key]['value'] ?? '';
    }

    /**
     * Destroy the session.
     */
    public function __destruct()
    {
        // Get the flash messages from the session.
        $flashMessages = $_SESSION[self::FLASH_MESSAGES] ?? [];
        // Remove the flash messages that are set to be removed.
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_MESSAGES] = $flashMessages;
    }
}
