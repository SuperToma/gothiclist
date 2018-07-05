<?php

namespace App\Controller;

use App\Entity\Style;
use App\Finder\Elasticsearch\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AutocompleteController extends Controller
{
    /**
     * @param string $prefixArtist
     * @param Finder $esFinder
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function artistGroup(string $prefixArtist, Finder $esFinder)
    {
        $max = 15;

        return $this->json($esFinder->getArtistGroupStartingWith($prefixArtist, $max));
    }

    /**
     * @param int $artistId
     * @param string $prefixSong
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function songsByArtist(int $artistId, string $prefixSong, Finder $esFinder)
    {
        $max = 15;

        return $this->json($esFinder->getSongsStartingWith($artistId, $prefixSong, $max));
    }

    public function styles()
    {
        $styles = $this->getDoctrine()->getRepository(Style::class)->findAll();

        $results = [];
        foreach($styles as $style) {
            $results[] = ['id' => $style->getId(), 'name' => $style->getName()];
        }

        return $this->json($results);
    }
}