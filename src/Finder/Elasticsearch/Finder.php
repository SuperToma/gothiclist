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
    protected function getMainReleasesFromArtistId($artistId)
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
            'size' => $limit * 5,
            '_source' => ['title', 'tracklist.title'],
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'match' => ['tracklist.title' => $text]
                        ],
                        'filter' => [
                            'term' => ['artists.artist.id' => $artistId]
                        ]
                    ]

                ]
            ]
        ];

        $esResults = $this->getResults($this->client->search($params));

        //Remove non main_releases
        $mainReleases = $this->getMainReleasesFromArtistId($artistId);

        //@TODO : keep releases non existing songs
        foreach($esResults['results'] as $i => $result) {
            if(in_array($result['title'], $mainReleases) ) {
                if($result['id'] != array_search($result['title'], $mainReleases)) {
                    unset($esResults['results'][$i]);
                    $esResults['total']--;
                }
            }
        }

        $newResults = [];
        $titles = false;
        foreach($esResults['results'] as $i => $result) { // Albums
            $foundInAlbum = false;
            foreach($result['tracklist'] as $j => $track) { // Tracks
                if(stripos($track['title'][0], $text) !== false) {
                    $newResults[] = [
                        'id' => $result['id'],
                        'track' => $track['title'][0],
                        'album' => $result['title'],
                    ];
                    $titles[] = $track['title'][0];
                    if($foundInAlbum) {
                        $esResults['total']++;
                    }
                    $foundInAlbum = true;
                }
            }
        }


        // Add album title if duplicate titles
        if($titles !== false && count($titles) !== count(array_unique($titles))) {
            $duplicateTitles = array_diff_assoc($titles, array_unique($titles));
            foreach($newResults as &$result) {
                if(in_array($result['track'], $duplicateTitles)) {
                    $result['track'] .= ' ('.$result['album'].')';
                }
            }
        }

        // Remove album
        foreach($newResults as &$result) {
            unset($result['album']);
        }

        return [
            'total' => $esResults['total'],
            'results' => array_splice($newResults, 0, $limit),
        ];
    }



}
