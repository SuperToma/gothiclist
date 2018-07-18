<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\SongRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ArtistController extends Controller
{
    /**
     * @param int $id
     * @param string $slug
     * @param SongRepository $songRepository
     * @return Response
     */
    public function index(int $id, string $slug, SongRepository $songRepository): Response
    {
        /** @var Artist $artist */
        $artist = $this->getDoctrine()->getRepository(Artist::class)->find($id);

        if(!$artist) {
            throw $this->createNotFoundException('Sorry, this artist does not exist');
        }

        $slugify = new Slugify();
        if($slug !== $slugify->slugify($artist->getName())) {
            return $this->redirectToRoute('artist_home', ['id' => $id, 'slug' => $slugify->slugify($artist->getName())]);
        }

        return $this->render('pages/artist/home.html.twig', [
            'artist' => $artist,
            'last_songs' => $songRepository->getLast(10, ['artist' => $id])
        ]);
    }
}