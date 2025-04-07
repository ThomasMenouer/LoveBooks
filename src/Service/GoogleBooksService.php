<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleBooksService
{
    private HttpClientInterface $client;
    private string $apiBaseUrl;
    private string $googleApiKey;

    public function __construct(HttpClientInterface $client, string $apiBaseUrl, string $googleApiKey)
    {
        $this->client = $client;
        $this->apiBaseUrl = $apiBaseUrl;
        $this->googleApiKey = $googleApiKey;
    }

    public function searchBooks(string $title, int $maxResults = 25): array
    {
        $url = $this->apiBaseUrl . urlencode($title) . 
            '&maxResults=' . $maxResults . 
            '&key=' . $this->googleApiKey;

        $response = $this->client->request('GET', $url);

        return $response->toArray();
    }

}