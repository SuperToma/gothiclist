<?php

namespace App\Controller;

use App\Entity\Style;
use App\Repository\SongRepository;
use App\Repository\VoteSongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\Response;

class StyleController extends Controller
{
    /**
     * @param int $idStyle
     * @param string $slug
     * @param SongRepository $songRepository
     * @param VoteSongRepository $voteSongRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
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
                'style_home', ['id' => $style->getId(), 'slug' => $slugify->slugify($style->getName())]
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

}