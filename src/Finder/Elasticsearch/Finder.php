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
        if(isset($rawResults['hits']['hits'])) {
            $results = $rawResults['hits']['hits'];
        } else {
            $results = [];
        }

        foreach($results as $i => $result) {
            if(isset($results[$i]['_source'])) {
                $results[$i] += $results[$i]['_source'];
                unset($results[$i]['_source']);
            }

            $results[$i]['id'] = $result['_id'];
            unset(
                $results[$i]['_id'],
                $results[$i]['_index'],
                $results[$i]['_type'],
                $results[$i]['_score']
            );

            if(count($results[$i]) === 1) {
                $results[$i] = $results[$i]['id'];
            }
        }

        return $results;
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

        dump($params);
        dump($this->client->search($params));
        dump($this->client->transport->getLastConnection()->getLastRequestInfo());
        exit();
        return $this->getResults(
            $this->client->search($params)
        );
    }

    /**
     * @param $artistId
     * @return array
     */
    protected function getMasterIdsFromArtistId($artistId)
    {
        $params = [
            'index' => 'master',
            'from' => 0,
            'size' => 500,
            '_source' => false,
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

        return $this->getResults($this->client->search($params));
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
                            'terms' => ['master_id' => $this->getMasterIdsFromArtistId($artistId)],
                        ]
                    ]
                ]
            ]
        ];

        $results = $this->getResults($this->client->search($params));

        $newResults = [];
        foreach($results as $i => $result) {
            foreach($result['tracklist'] as $j => $track) {
                if(stripos($track['title'][0], $text) !== false) {
                    $newResults[] = ['id' => $result['id'], 'track' => $track['title'][0]];
                }
            }
        }

        return $newResults;
    }

}
