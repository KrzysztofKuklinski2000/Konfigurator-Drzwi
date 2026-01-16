<?php

namespace App\Core;

class Request {
    /** @var array */
    private array $get;
    private array $post;
    private array $server;

    /** Konstruktor inicjalizuje dane żądania HTTP. */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
    }

    /** Zwraca dane zapytania GET lub pojedynczą wartość po nazwie. 
     * 
     * @param string|null $name
     * @return array|string|null
     */
    public function getQuery(?string $name = null): array | string | null {
        if ($name !== null) {
            return $this->get[$name] ?? null;
        }
        return $this->get;
    }

    /** Zwraca dane zapytania POST lub pojedynczą wartość po nazwie. 
     * 
     * @param string|null $name
     * @return array|string|null
     */
    public function getPost(?string $name = null): array | string | null {
        if ($name !== null) {
            return $this->post[$name] ?? null;
        }

        return $this->post;
    }
    
    /** Zwraca metodę żądania HTTP. 
     * 
     * @return string
     */
    public function getMethod(): string {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    /** Zwraca URI żądania HTTP usuwa wszystko po ?. 
     * 
     * @return string
     */
    public function getUri(): string {
        $url = $this->server['REQUEST_URI'] ?? '/';

        return parse_url($url, PHP_URL_PATH);
    }
}