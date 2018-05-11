<?php

namespace App\Finder\Elasticsearch;

use Elasticsearch\ClientBuilder;

class Finder
{
    /* @var $client ClientBuilder */
    protected $client;

    public function __construct()
    {
        $client = ClientBuilder::create();
        $client->setHosts([getenv('ES_ADDR')]);
        $this->client = $client->build();
    }

    /**
     * @param array $rawResults
     * @return array
     */
    protected function getResults(array $rawResults)
    {
        $results = [];
        if(!isset($rawResults['hits']['total']) || $rawResults['hits']['total'] === 0)
        {
            return $results;
        }

        foreach($rawResults['hits']['hits'] as $hit) {
            $results[] = $hit['_source'];
        }

        return $results;
    }

    /**
     * @param string $text
     * @param int $limit
     * @return array
     */
    public function getArtistGroupStartingWith(string $text, $limit = 15)
    {
        $params = [
            'index' => 'artist',
            '_source' => [ 'id', 'name' ],
            'from' => 0,
            'size' => $limit,
            'body' => [
                'query' => [
                    'prefix' => [
                        'name' => $text
                    ]
                ]
            ]
        ];

        return $this->getResults($this->client->search($params));
    }

    /**
     * @param int $artistId
     * @param string $title
     * @param int $limit
     * @return array
     */
    public function getSongsByArtistStartingWith(int $artistId, string $title, $limit = 15)
    {
        $params = [
            'index' => 'release',
            '_source' => [ 'tracklist.title' ],
            'from' => 0,
            'size' => $limit,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => [
                                    'artists.artist.id' => $artistId
                                ],
                            ],
                            [
                                'prefix' => [
                                    'tracklist.title' => $title
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        dump($this->client->search($params)); exit();
        return $this->getResults($this->client->search($params));
    }

}
