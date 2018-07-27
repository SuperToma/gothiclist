<?php

namespace App\Finder\Elasticsearch;

use Elasticsearch\ClientBuilder;

/**
 * Class Finder
 * @package App\Finder\Elasticsearch
 */
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
    protected function getMainReleasesTitlesFromArtistId($artistId)
    {
        $params = [
            'index' => 'master',
            'from' => 0,
            'size' => 1000,
            '_source' => ['main_release', 'title'],
            'body' => [
                'query' => [
                    'term' => [ 'artists.id' => $artistId ]
                ]
            ]
        ];

        $rawResults = $this->getResults($this->client->search($params));

        $results = [];
        foreach($rawResults['results'] as $rawResult) {
            $results[$rawResult['main_release']] = $rawResult['title'];
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
            '_source' => ['title'],
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'nested' => [
                                    "path" => "tracklist",
                                    "inner_hits" => [
                                        "_source" => [
                                            "tracklist.title"
                                        ]
                                    ],
                                    'query' => [
                                        'bool' => [
                                            'must' => [
                                                'match' => ['tracklist.title' => $text]
                                            ]
                                        ]
                                    ]
                                ]
                            ], [
                                'term' => ['artists.artist.id' => $artistId]
                            ]
                        ]
                    ]
                ],
                //'sort' => ['released' => ['order' => 'asc']]
            ]
        ];

        //$this->client->search($params);
        //dump($this->client->transport->getLastConnection()->getLastRequestInfo());

        $esResults = $this->getResults($this->client->search($params));

        //Remove non main_releases
        $mainReleases = $this->getMainReleasesTitlesFromArtistId($artistId);
        //@TODO : keep releases's non existing songs
        foreach($esResults['results'] as $i => $result) {
            if (in_array($result['title'], $mainReleases)) {
                if ($result['id'] != array_search($result['title'], $mainReleases)) {
                    unset($esResults['results'][$i]);
                    $esResults['total']--;
                }
            }
        }

        //Make results in correct form & duplicate tracks
        $newResults = [];
        $titles = [];
        foreach($esResults['results'] as $result) {
            //If many tracks returned
            foreach($result['inner_hits']['tracklist']['hits']['hits'] as $track) {
                $title = $track['_source']['title'][0];
                $newResults[] = [
                    'id' => $result['id'],
                    'album' => $result['title'],
                    'track' => $title,
                ];
                $titles[] = $title;
            }
        }

        // Concat with album title if duplicate titles
        if(!empty($titles) && count($titles) !== count(array_unique($titles))) {
            $duplicateTitles = array_diff_assoc($titles, array_unique($titles));
            foreach($newResults as &$result) {
                if(in_array($result['track'], $duplicateTitles)) {
                    $result['track'] .= ' ('.$result['album'].')';
                }
            }
        }

        // Remove albums
        foreach($newResults as &$result) {
            unset($result['album']);
        }

        return [
            'total' => $esResults['total'],
            'results' => array_splice($newResults, 0, $limit),
        ];
    }



}
