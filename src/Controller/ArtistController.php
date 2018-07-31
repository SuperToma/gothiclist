<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\ArtistVersion;
use App\Repository\SongRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ArtistController
 * @package App\Controller
 */
class ArtistController extends Controller
{
    /**
     * @param int $id
     * @param string $slug
     * @param Request $request
     * @param SongRepository $songRepository
     * @return Response
     */
    public function index(int $id, string $slug, Request $request, SongRepository $songRepository): Response
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

        if($request->getMethod() === 'POST') {
            $description = $request->get('description');
            $photoUrl = $request->get('photo_url');

            /** @var ArtistVersion $version */
            $artistVersion = (new ArtistVersion())
                ->setUser($artist->getUser())
                ->setArtist($artist)
                ->setDescription($artist->getDescription())
                ->setPhotoUrl($artist->getPhotoUrl());

            $artist->setDescription($description);
            $artist->setPhotoUrl($photoUrl);

            $this->getDoctrine()->getManager()->persist($artistVersion);
            $this->getDoctrine()->getManager()->persist($artist);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('pages/artist/home.html.twig', [
            'artist' => $artist,
            'last_songs' => $songRepository->getLast(10, ['artist' => $id])
        ]);
    }

    /**
     * @param int $id
     * @param string $slug
     * @return JsonResponse|RedirectResponse
     */
    public function jsonFormat(int $id, string $slug)
    {
        /** @var Artist $artist */
        $artist = $this->getDoctrine()->getRepository(Artist::class)->find($id);

        if(!$artist) {
            throw $this->createNotFoundException('Sorry, this artist does not exist');
        }

        $slugify = new Slugify();
        if($slug !== $slugify->slugify($artist->getName())) {
            return $this->redirectToRoute('artist_json', ['id' => $id, 'slug' => $slugify->slugify($artist->getName())]);
        }

        return $this->json([
            'id' => $id,
            'slug' => $slug,
            'name' => $artist->getName(),
            'description' => $artist->getDescription()
        ]);
    }
}