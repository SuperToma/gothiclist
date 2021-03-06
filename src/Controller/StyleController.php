<?php

namespace App\Controller;

use App\Entity\Style;
use App\Repository\SongRepository;
use App\Repository\StyleRepository;
use App\Repository\VoteSongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class StyleController
 * @package App\Controller
 */
class StyleController extends AbstractController
{
    /**
     * @param int $idStyle
     * @param string $slug
     * @param SongRepository $songRepository
     * @param VoteSongRepository $voteSongRepository
     * @return RedirectResponse|Response
     */
    public function home(int $idStyle, string $slug, SongRepository $songRepository, VoteSongRepository $voteSongRepository)
    {
        /** @var Style $style */
        $style = $this->getDoctrine()->getRepository(Style::class)->find($idStyle);

        if(!$style) {
            throw $this->createNotFoundException('Sorry, this style does not exist');
        }

        if($slug != $style->getSlug()) {
            return $this->redirectToRoute(
                'style_home', ['idStyle' => $style->getId(), 'slug' => $style->getSlug()]
            );
        }

        $lastSongsAdded = $songRepository->getLastByStyle($idStyle);
        foreach($lastSongsAdded as &$song) {
            $song->nbVotes = $voteSongRepository->count(['song' => $song]);
        }

        return $this->render('pages/style/index.html.twig', [
            'style' => $style,
            'last_songs' => $lastSongsAdded,
            'most_rated_songs' => $songRepository->getMostRatedByStyle($idStyle, 20),
        ]);
    }

    /**
     * @param StyleRepository $artistRepository
     * @return Response
     */
    public function list(StyleRepository $styleRepository): Response
    {
        return $this->render('pages/style/list.html.twig', [
            'styles' => $styleRepository->getAllWithInfos(),
        ]);
    }

}