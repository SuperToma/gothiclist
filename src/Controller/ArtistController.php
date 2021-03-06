<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\ArtistVersion;
use App\Repository\ArtistRepository;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ArtistController
 * @package App\Controller
 */
class ArtistController extends AbstractController
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

        $artist->setName(ArtistRepository::cleanArtistName($artist->getName()));
        if($slug !== $artist->getSlug()) {
            return $this->redirectToRoute('artist_home', ['id' => $id, 'slug' => $artist->getSlug()]);
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
            'last_songs' => $songRepository->getLast(10, ['artist' => $id, 'validated' => true])
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

        if($slug !== $artist->getSlug()) {
            return $this->redirectToRoute('artist_json', ['id' => $id, 'slug' => $artist->getSlug()]);
        }

        return $this->json([
            'id' => $id,
            'slug' => $slug,
            'name' => $artist->getName(),
            'description' => $artist->getDescription()
        ]);
    }

    /**
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    public function list(ArtistRepository $artistRepository): Response
    {
        return $this->render('pages/artist/list.html.twig', [
            'artists' => $artistRepository->getAllWithInfos(),
        ]);
    }
}