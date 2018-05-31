<?php

namespace App\Controller;

use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Finder\Elasticsearch\Finder;
use App\Entity\User;
use App\Entity\Artist;

class SongController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, Finder $esFinder)
    {
        $this->denyAccessUnlessGranted(
            'ROLE_USER', null, 'You need to be connected to access this page.'
        );

        /** @var $user User */
        $user = $this->getUser();

        if($request->getMethod() === 'POST') {
            $artist_id = $request->get('artist_id');
            $song_id = $request->get('song_id');

            if(empty($artist_id)) {
                $this->addFlash('danger', 'You didn\'t send any artist');
                return $this->render('pages/song/add.html.twig', []);
            }

            if(empty($song_id)) {
                $this->addFlash('danger', 'You did not send any song');
                return $this->render('pages/song/add.html.twig', []);
            }

            //Insert artist if necessary
            $artist = $this->getDoctrine()
                ->getRepository(Artist::class)
                ->findOneBy(['idDiscogs' => $artist_id]);

            if(is_null($artist)) {
                $esArtist = $esFinder->getArtistById($artist_id);

                if(empty($esArtist)) {
                    if(empty($artist_id)) {
                        $this->addFlash('danger', 'Selected artist not found');
                        return $this->render('pages/song/add.html.twig', []);
                    }
                }

                $artist = (new Artist())
                    ->setIdDiscogs($artist_id)
                    ->setUser($user)
                    ->setNameVariations($esArtist['namevariations'] ?? [])
                    ->setDescriptionDiscogs($esArtist['profile'] ?? '')
                    ->setName($esArtist['name'] ?? '')
                    ->setRealName($esArtist['realname'] ?? '');

                $this->getDoctrine()->getManager()->persist($artist);
                $this->getDoctrine()->getManager()->flush();
            }

            //Insert the song
            $song = $this->getDoctrine()
                ->getRepository(Song::class)
                ->findOneBy(['idDiscogs' => $song_id]);

            if(is_object($song)) {
                $this->addFlash('danger', 'Sorry, this song already exists');
                return $this->render('pages/song/add.html.twig', []);
            }

            $esSong = $esFinder->getSongById($song_id);
            dump($esSong); exit();

            $song = (new Song())
                ->setIdDiscogs($song_id)
                ->setUser($user)
                ->setIdArtist($artist->getId())
                ->setTitle()
                ->setDescription()
                ->setName($esArtist['results'][0]['name'])
                ->setRealName($esArtist['results'][0]['name'] ?? null);

            $this->getDoctrine()->getManager()->persist($song);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thank you ! Your song has been added.');
        }

        return $this->render('pages/song/add.html.twig', []);
    }

    protected function verifyForm()
    {

    }
}