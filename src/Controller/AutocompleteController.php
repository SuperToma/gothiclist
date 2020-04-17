<?php

namespace App\Controller;

use App\Entity\Style;
use App\Finder\Elasticsearch\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AutocompleteController
 * @package App\Controller
 */
class AutocompleteController extends AbstractController
{
    /**
     * @param string $prefixArtist
     * @param Finder $esFinder
     * @return JsonResponse
     */
    public function artistGroup(string $prefixArtist, Finder $esFinder): JsonResponse
    {
        $max = 150;

        return $this->json($esFinder->getArtistGroupStartingWith($prefixArtist, $max));
    }

    /**
     * @param int $artistId
     * @param string $prefixSong
     * @param Finder $esFinder
     * @return JsonResponse
     */
    public function songsByArtist(int $artistId, string $prefixSong, Finder $esFinder): JsonResponse
    {
        $max = 150;

        return $this->json($esFinder->getSongsStartingWith($artistId, $prefixSong, $max));
    }

    /**
     * @return JsonResponse
     */
    public function styles(): JsonResponse
    {
        $styles = $this->getDoctrine()->getRepository(Style::class)->findAll();

        $results = [];
        foreach($styles as $style) {
            $results[] = ['id' => $style->getId(), 'name' => $style->getName()];
        }

        return $this->json($results);
    }
}