<?php

namespace App\Controller;

use App\Repository\VoteRepository;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Song;

class DefaultController extends Controller
{
    /**
     * @param SongRepository $songRepository
     * @param VoteRepository $voteRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SongRepository $songRepository, VoteRepository $voteRepository)
    {
        $lastSongs = $songRepository->findBy([], ['createdAt' => 'DESC'], 10);
        // foreach($lastSongs as &$song) {
            /** @var Song $song */
        //     $song->vote = $voteRepository->findVotesInfos(Song::VOTE_TYPE, $song->getId());
        // }

        //dump($lastSongs); exit();
        return $this->render('pages/index.html.twig', [
            'last_songs' => $lastSongs
        ]);
    }
}