<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Song;

class DefaultController extends Controller
{
    public function index()
    {
        $songRepository = $this->getDoctrine()->getRepository(Song::class);

        $lastSongs = $songRepository->findBy([], ['createdAt' => 'DESC'], 10);


        return $this->render('pages/index.html.twig', [
            'last_songs' => $lastSongs,
        ]);
    }
}