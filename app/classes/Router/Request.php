<?php


namespace App\classes\Router;


class Request
{
    private string $method;
    private string $requestedUri;

    /**
     * Request constructor.
     * @param string $method
     * @param string $requestedUri
     */
    public function __construct(string $method, string $requestedUri)
    {
        $this->method = $method;
        $this->requestedUri = $requestedUri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getRequestedUri(): string
    {
        return $this->requestedUri;
    }




}
