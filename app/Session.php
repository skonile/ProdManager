<?php
declare(strict_types = 1);

namespace App;

class Session{
    private static ?Session $session = null;

    private function __construct(){
        @session_start();
    }

    public static function getInstance(): static{
        if(static::$session === null)
            static::$session = new static();
        return static::$session;
    }

    public function set(string $key, string|bool|null|int|float $value){
        $_SESSION[$key] = $value;
    }

    public function get(string $key): string|bool|null|int|float{
        if(!isset($_SESSION[$key]))
            return null;
        return $_SESSION[$key];
    }

    /**
     * Check if user is logged in or not.
     * 
     * Uses the session variable to check if a user id and 
     * is logged in are set and is logged in value is true.
     * 
     * @return bool true if the user is logged in or false otherwise.
     */
    public function isLoggedIn(): bool{
        if((isset($_SESSION['user-id']) && isset($_SESSION['is-logged-in'])) && $_SESSION['is-logged-in'] === true)
            return true;
        return false;
    }

    public function setIsLoggedIn(int $userId){
        $this->set('user-id', $userId);
        $this->set('is-logged-in', true);
    }

    public function logout(){
        session_unset();
        session_destroy();
    }

    /**
     * Set the message to be passed to another page.
     * 
     * This session variable is used to pass a message when a page
     * is going to redireect to another page and wants to display 
     * a message on that page.
     * 
     * @param string $message Message to pass.
     */
    public function setMessage(string $message){
        $_SESSION['message'] = $message;
    }

    /**
     * Get the passed message.
     * 
     * Get the message that was passed by another page
     * 
     * @return string|null Message passed or null if non was set.
     */
    public function getMessage(): string|null{
        if(!isset($_SESSION['message'])) return null;
        return $_SESSION['message'];
    }

    public function removeMessage(){
        unset($_SESSION['message']);
    }
}