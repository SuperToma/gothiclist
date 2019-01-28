<?php

namespace App\Controller;

use App\Entity\Style;
use App\Repository\SongRepository;
use App\Repository\StyleRepository;
use App\Repository\VoteSongRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class StyleController
 * @package App\Controller
 */
class StyleController extends Controller
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

        $slugify = new Slugify();
        if($slug != $slugify->slugify($style->getName())) {
            return $this->redirectToRoute(
                'style_home', ['idStyle' => $style->getId(), 'slug' => $slugify->slugify($style->getName())]
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
        return $this->render('pages/artist/list.html.twig', [
            'artists' => $styleRepository->getAllWithInfos(),
        ]);
    }

}