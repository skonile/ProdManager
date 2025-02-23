<?php
declare(strict_types = 1);

namespace App;

class Request{
    protected string $method;
    protected string $uri;
    protected $uriArgsParts;
    protected array $params;

    public function __construct($method, $uri, $params = []){
        $this->method = $method;
        $this->uri = $uri;
        $this->params = $this->sanitizeParams($params);
        $this->setUriArgsParts();
    }

    /** 
     * Get the request method.
     * 
     * @return string the request method
    */
    public function getMethod(): string{
        return $this->method;
    }

    /**
     * Get the request URI.
     * 
     * @return string URI
     */
    public function getUri(): string{
        return $this->uri;
    }

    protected function setUriArgsParts(){
        $uriArgsParts = \array_slice(\explode("/", $this->uri), 1);
        $uriArgsParts[0] = '/' . $uriArgsParts[0];
        $this->uriArgsParts = $uriArgsParts;
    }

    public function getFirstUriPart(): string{
        return $this->uriArgsParts[0];
    }

    public function getSecondUriPart(): ?string{
        return $this->uriArgsParts[1] ?? null;
    }

    public function getThirdUriPart(): ?string{
        return $this->uriArgsParts[2] ?? null;
    }

    public function getParams(){
        return $this->params;
    }

    /**
     * Get the value of the $_COOKIE constant.
     * 
     * @return array The value of $_COOKIE
     */
    public function getCookie(string $cookieName): ?array{
        return $_COOKIE[$cookieName] ?? null;
    }

    /**
     * Get a session object.
     * 
     * @return Session The session object
     */
    public function getSession(): Session{
        return Session::getInstance();
    }

    /**
     * Get the value of the $_GET constant.
     * 
     * @return array The value of $_GET
     */
    public function getGET(): array{
        return $_GET;
    }

    /**
     * Makes a call to the $_GET with the param as the key.
     * 
     * @param string $key The key for the $_GET
     * @return string|null Returns the value of the $_GET[$key] or null if it does not exist.
     */
    public function get(string $key): string|null{
        return isset($_GET[$key])? $_GET[$key]: null;
    }

    /**
     * Makes a call to the $_POST with the param as the key.
     * 
     * @param string $key The key for the $_POST
     * @return string|null Returns the value of the $_POST[$key] or null if it does not exist.
     */
    public function post(string $key): string|null{
        return isset($_POST[$key])? $_POST[$key]: null;
    }

    /**
     * Get the value of the $_POST constant.
     * 
     * @return array The value of $_POST
     */
    public function getPOST(): array{
        return $_POST;
    }

    public function sanitizeParams(array $params): array{
        $sanitezedArr = [];
        foreach($params as $key => $value)
            $sanitezedArr[$key] = $this->sanitizeString($value);
        return $sanitezedArr;
    }

    public function sanitizeString(string $text): string{
        return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
    }
}