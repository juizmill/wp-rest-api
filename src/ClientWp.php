<?php

namespace WpRestApi;

use RuntimeException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ClientWp
{
    const ENDPOINT_POSTS = 'posts';
    const ENDPOINT_REVISIONS = 'revisions';
    const ENDPOINT_CATEGORIES = 'categories';
    const ENDPOINT_TAGS = 'tags';
    const ENDPOINT_PAGES = 'pages';
    const ENDPOINT_COMMENTS = 'comments';
    const ENDPOINT_TAXONOMIES = 'taxonomies';
    const ENDPOINT_MEDIA = 'media';
    const ENDPOINT_USERS = 'users';
    const ENDPOINT_TYPES = 'types';
    const ENDPOINT_STATUSES = 'statuses';
    const ENDPOINT_SETTINGS = 'settings';

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get($endpoint, $options = [])
    {
        $response = $this->client->get($endpoint, $options);

        return $this->normalizeResponse($response);
    }

    public function normalizeResponse(ResponseInterface $response, $statusCode = 200) : array
    {
        if ($response->getStatusCode() != $statusCode) {
            throw new RuntimeException('Endpoint not found, status code: '. $response->getStatusCode());
        }

        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }
}
