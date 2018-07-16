<?php

namespace Mochilo;

class Router
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $post;

    /**
     * @var array
     */
    private $routes;

    /**
     * array
     */
    const GET_URLS = [
        "" => "index",
        "/" => "index",
    ];

    /**
     * array
     */
    const POST_URLS = [
        "/contact" => "contact",
    ];

    /**
     * Router constructor.
     *
     * @param string $url
     * @param array $post
     * @param array $routes
     */
    public function __construct(string $url, array $post, array $routes)
    {
        $this->url = trim($url);
        $this->post = $post;
        $this->routes = $routes;
    }

    public function action()
    {

        if (isset(self::GET_URLS[$this->url])) {
            return self::GET_URLS[$this->url];
        } elseif (self::POST_URLS[$this->url]) {
            return self::POST_URLS[$this->url];
        }

        return 'notFound';
    }

    public function post()
    {
        return $this->post;
    }
}
