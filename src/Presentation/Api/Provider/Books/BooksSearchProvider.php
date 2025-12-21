<?php

namespace App\Presentation\Api\Provider\Books;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Presentation\Api\Resource\Books\BooksSearchResource;


final class BooksSearchProvider implements ProviderInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiBaseUrl,
        private string $googleApiKey,
        private RequestStack $requestStack,
        private int $maxResults = 25,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return [];
        }

        $title = trim($request->query->get('q', ''));

        if (empty($title)) {
            return [];
        }

        $url = $this->apiBaseUrl . urlencode($title) .
            '&maxResults=' . $this->maxResults .
            '&key=' . $this->googleApiKey;


        $response = $this->client->request('GET', $url);

        try {
            $data = $response->toArray();
        } catch (\Throwable $e) {

            return [
                'error' => 'Unable to fetch data from Google Books API.',
                'message' => $e->getMessage()
            ];
        }

                // Transforme chaque item en BooksSearchResource
        $items = [];
        foreach ($data['items'] ?? [] as $item) {
            $info = $item['volumeInfo'] ?? [];
            $items[] = new BooksSearchResource([
                'id' => $item['id'] ?? null,
                'title' => $info['title'] ?? null,
                'authors' => $info['authors'] ?? [],
                'publisher' => $info['publisher'] ?? null,
                'publishedDate' => $info['publishedDate'] ?? null,
                'thumbnail' => $info['imageLinks']['thumbnail'] ?? null,
            ]);
        }

        return $items;
    }
}
