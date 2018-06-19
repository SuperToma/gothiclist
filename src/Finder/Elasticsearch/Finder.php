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
        foreach($rawResults['hits']['hits'] as &$result) {
            if(isset($result['_source'])) {
                $result += $result['_source'];
                unset($result['_source']);
            }

            $result['id'] = $result['_id'];
            unset(
                $result['_id'],
                $result['_index'],
                $result['_type'],
                $result['_score']
            );

            //If only Ids
            if(count($result) === 1) {
                $result = $result['id'];
            }
        }

        return [
            'total' => $rawResults['hits']['total'],
            'results' => $rawResults['hits']['hits'],
        ];
    }

    /**
     * @param $artistId
     * @return array
     */
    public function getArtistById($artistId)
    {
        $params = [
            'index' => 'artist',
            'body' => [
                'query' => [
                    'term' => ['_id' => $artistId]
                ]
            ]
        ];

        return $this->getResults($this->client->search($params))['results'][0] ?? [];
    }

    public function getReleaseById($songId)
    {
        $params = [
            'index' => 'release',
            'body' => [
                'query' => [
                    'term' => ['_id' => $songId]
                ]
            ]
        ];

        return $this->getResults($this->client->search($params))['results'][0] ?? [];
    }

    /**
     * @param string $text
     * @param int $limit
     * @return array
     */
    public function getArtistGroupStartingWith(string $text, int $limit = 15)
    {
        $params = [
            'index' => 'artist',
            '_source' => 'name',
            'body' => [
                'query' => [
                    'match' => [
                        'name' => $text
                    ]
                ]
            ]
        ];

        return $this->getResults($this->client->search($params));
    }

    /**
     * @param $artistId
     * @return array
     */
    protected function getMainReleaseIdsFromArtistId($artistId)
    {
        $params = [
            'index' => 'master',
            'from' => 0,
            'size' => 1000,
            '_source' => 'main_release',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'term' => [ 'artists.id' => $artistId ]
                        ]
                    ]
                ]
            ]
        ];

        $rawResults = $this->getResults($this->client->search($params));

        $results = [];
        foreach($rawResults['results'] as $rawResult) {
            $results[] = $rawResult['main_release'];
        }

        return $results;
     }

    /**
     * @param int $artistId
     * @param string $text
     * @param int $limit
     * @return array
     */
    public function getSongsStartingWith(int $artistId, string $text, $limit = 15)
    {
        $params = [
            'index' => 'release',
            'from' => 0,
            'size' => $limit,
            '_source' => 'tracklist.title',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'match' => [ 'tracklist.title' => $text ]
                        ],
                        'filter' => [
                            'terms' => ['_id' => $this->getMainReleaseIdsFromArtistId($artistId)],
                        ]
                    ]
                ]
            ]
        ];

        $results = $this->getResults($this->client->search($params));
        //dump($this->client->search($params));
        //dump($this->client->transport->getLastConnection()->getLastRequestInfo());
        //exit();

        $newResults = [];
        foreach($results['results'] as $i => $result) {
            foreach($result['tracklist'] as $j => $track) {
                if(stripos($track['title'][0], $text) !== false) {
                    $newResults[] = ['id' => $result['id'], 'track' => $track['title'][0]];
                }
            }
        }

        return $newResults;
    }



}
