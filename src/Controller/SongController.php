<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Release;
use App\Entity\Song;
use App\Entity\Style;
use App\Entity\User;
use App\Finder\Elasticsearch\Finder;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SongController
 * @package App\Controller
 */
class SongController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, Finder $esFinder): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_USER', null, 'You need to be connected to access this page.'
        );

        /** @var $user User */
        $user = $this->getUser();

        if($request->getMethod() === 'POST') {
            $artistId = $request->get('artist_id');
            $releaseId = $request->get('release_id');
            $songName = $request->get('song_name');
            $artistName = $request->get('artist_name');

            if(empty($artistId)) {
                $this->addFlash('danger', 'You didn\'t send any artist');
                return $this->render('pages/song/add.html.twig', []);
            }

            if(empty($releaseId) || empty($songName)) {
                $this->addFlash('danger', 'You did not send any song');
                return $this->render('pages/song/add.html.twig', []);
            }

            //Insert artist if necessary
            $artist = $this->getDoctrine()
                ->getRepository(Artist::class)
                ->findOneBy(['idDiscogs' => $artistId]);

            if(is_null($artist)) {
                $esArtist = $esFinder->getArtistById($artistId);

                if(empty($esArtist)) {
                    if(empty($artistId)) {
                        $this->addFlash('danger', 'Selected artist not found');
                        return $this->render('pages/song/add.html.twig', []);
                    }
                }

                $artist = (new Artist())
                    ->setIdDiscogs($artistId)
                    ->setUser($user)
                    ->setNameVariations($esArtist['namevariations'] ?? [])
                    ->setDescriptionDiscogs($esArtist['profile'] ?? '')
                    ->setName($esArtist['name'] ?? '')
                    ->setRealName($esArtist['realname'] ?? '');

                $this->getDoctrine()->getManager()->persist($artist);
            }

            //Insert the release
            $release = $this->getDoctrine()
                ->getRepository(Release::class)
                ->findOneBy(['idDiscogs' => $releaseId]);

            $esRelease = $esFinder->getReleaseById($releaseId); //Needed for tracks

            if(is_null($release)) {

                if(empty($esRelease)) {
                    $this->addFlash('danger', 'Sorry, we did not found the album of your song');
                    return $this->render('pages/song/add.html.twig', []);
                }

                //Add release genres
                $genres = new ArrayCollection();
                foreach($esRelease['genres'] as $genreName) {
                    $genre = $this->getDoctrine()->getRepository(Genre::class)->findOneBy(['name' => $genreName]);
                    if(empty($genre)) {
                        $genre = (new Genre())->setName($genreName);
                        $this->getDoctrine()->getManager()->persist($genre);
                        $this->getDoctrine()->getManager()->flush();
                    }
                    $genres->add($genre);
                }

                //Add release styles
                $styles = new ArrayCollection();
                foreach($esRelease['styles'] as $styleName) {
                    $style = $this->getDoctrine()->getRepository(Style::class)->findOneBy(['name' => $styleName]);
                    if(empty($style)) {
                        $style = (new Style())->setName($styleName);
                        $this->getDoctrine()->getManager()->persist($style);
                        $this->getDoctrine()->getManager()->flush();
                    }
                    $styles->add($style);
                }

                $release = $this->getDoctrine()->getRepository(Release::class)->findOneBy(['idDiscogs' => $releaseId]);
                if(empty($release)) {
                    $release = (new Release())
                        ->setIdDiscogs($releaseId)
                        ->setUser($user)
                        ->setTitle($esRelease['title'])
                        ->setStyles($styles)
                        ->setGenres($genres)
                        ->setCountry($esRelease['country'])
                        ->setDiscogsNotes($esRelease['notes'] ?? '');

                    $this->getDoctrine()->getManager()->persist($release);
                    $this->getDoctrine()->getManager()->flush();
                }
            }

            //Find song in release
            $trackFound = false;
            $tracklist = '';

            foreach($esRelease['tracklist'] as $track) {
                $tracklist .= '<li>'.$track['title'][0].'</li>';
                if(preg_match("/^".preg_quote($track['title'][0])."( \(.*\))$/", $songName) === 1) {
                    $songName = $track['title'][0];
                    $trackFound = true;
                    break;
                }
                if($track['title'][0] == $songName) {
                    $trackFound = true;
                    break;
                }
            }

            if(!$trackFound) {
                $this->addFlash(
                    'danger',
                    'The track : <strong>'.$songName.'</strong><br />
                    is not found in the album : <strong>'.$esRelease['title'].'</strong><br />
                    did you mean : <ul>'.$tracklist.'</ul>'
                );
                return $this->render('pages/song/add.html.twig', []);
            }

            //Insert the song
            $song = $this->getDoctrine()
                ->getRepository(Song::class)
                ->findOneBy(['title' => $songName]);

            if(is_object($song)) {
                $this->addFlash('danger', 'Sorry, this song already exists');
                return $this->render('pages/song/add.html.twig', []);
            }

            $this->getDoctrine()->getManager()->flush(); // For having artistId

            $song = (new Song())
                ->setUser($user)
                ->setRelease($release)
                ->setArtist($artist)
                ->setValidated(1)
                ->setTitle($songName);

            $this->getDoctrine()->getManager()->persist($song);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thank you ! Your song has been added.');
        }

        return $this->render('pages/song/add.html.twig', []);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function patch(Request $request, int $id): JsonResponse
    {
        $validPatchProperties = ['youtubeId', 'spotifyId'];
        $params = $request->request->all();

        if(empty($params)) {
            $this->json(false);
        }

        $song = $this->getDoctrine()
            ->getRepository(Song::class)
            ->find($id);

        foreach($params as $name => $value) {
            if(!in_array($name, $validPatchProperties)) {
                return $this->json(false, 400);
            } else {
                $setter = 'set'.ucfirst($name);
                $song->$setter($value);
            }
        }

        $this->getDoctrine()->getManager()->persist($song);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(true);
    }
}