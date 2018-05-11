<?php

namespace App\Controller;

use App\Finder\Elasticsearch\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AutocompleteController extends Controller
{
    protected $esFinder;

    /**
     * AutocompleteController constructor.
     * @param Finder $esFinder
     *
    public function __construct(Finder $esFinder)
    {
        $this->esFinder = $esFinder;
    }*/

    /**
     * @param string $artistGroup
     * @param Finder $esFinder
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ArtistGroup(string $artistGroup, Finder $esFinder)
    {
        $max = 15;

        return $this->json(
            $esFinder->getArtistGroupStartingWith($artistGroup, $max)
        );
    }

    /**
     * @param int $id
     * @param string $song
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function SongsByArtist(int $id, string $song)
    {
        $max = 15;

        return $this->json(
            $this->esFinder->getSongsByArtistStartingWith($id, $song, $max)
        );
    }
}