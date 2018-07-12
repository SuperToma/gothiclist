<?php

namespace App\Controller;

use App\Entity\VoteArtist;
use App\Entity\VoteSong;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VoteController
 * @package App\Controller
 */
class VoteController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function voteSong(Request $request)
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->json(['message' => 'Please sign up before voting'], 403);
        }

        $songId = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        // Delete vote
        if(in_array($songId, $this->getUser()->getVotesSongIds())) {
            $repository = $em->getRepository('App:VoteSong');
            $voteSong = $repository->findOneBy(['song' => $songId, 'user' => $this->getUser()->getId()]);
            $em->remove($voteSong);
            $em->flush();

            return $this->json(['action' => 'delete', 'message' => 'Song unliked']);
        }

        // Add vote
        $voteSong = (new VoteSong())
            ->setSong($em->getReference('App\Entity\Song', $songId))
            ->setUser($this->getUser());
        $em->persist($voteSong);
        $em->flush();

        return $this->json(['action' => 'add', 'message' => 'Song liked']);
    }

    public function voteArtist(Request $request)
    {
        $this->denyAccessUnlessGranted(
            'ROLE_USER', null, 'You need to be connected to access this page.'
        );

        $artistId = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        if(in_array($artistId, $this->getUser()->getVotesArtistIds())) {
            return $this->json(['message' => 'You already vote for this artist'], 409);
        }

        $voteArtist = (new VoteArtist())
            ->setSong($em->getReference('App\Entity\Artist', $artistId))
            ->setUser($this->getUser());

        $em->persist($voteArtist);
        $em->flush();

        return $this->json('Yeah');
    }
}