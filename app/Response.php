<?php
declare(strict_types = 1);

namespace App;

class Response{
    protected int $statusCode;
    protected array $headers;
    protected string $body;

    public function __construct(int $statusCode = 200, string $body = '', array $headers = []) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getStatusCode(): int{
        return $this->statusCode;
    }

    public function getBody(): string{
        return $this->body;
    }

    public function getHeaders(): array{
        return $this->headers;
    }

    public function setHeader(Header $header): static{
        $this->headers[] = $header;
        return $this;
    }

    public function send() {
        // Send HTTP headers
        /** @var Header $header */
        foreach ($this->headers as $header) {
            header($header->getKeyValueString());
        }

        // Send HTTP status code
        http_response_code($this->statusCode);

        // Send response body
        echo $this->body;
        exit;
    }

    public function sendToHomePage(){
        $this->sendToPage('/');
    }

    public function sendToPage(string $pageUri){
        header('Location: ' . $pageUri);
    }

    public function sendToPage404(){
        header("HTTP/1.1 404 Not Found");
    }

    public function json(array $data, int $statusCode = 200): static{
        $this->setHeader(new Header('Content-Type', 'application/json'));
        $this->statusCode = $statusCode;
        $this->body = json_encode($data);
        return $this;
    }
}