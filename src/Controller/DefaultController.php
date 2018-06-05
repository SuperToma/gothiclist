<?php

namespace App\Controller;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Song;

class DefaultController extends Controller
{
    public function index()
    {
        $songRepository = $this->getDoctrine()->getRepository(Song::class);
        $artistRepository = $this->getDoctrine()->getRepository(Artist::class);
        
        return $this->render('pages/index.html.twig', [
            'last_songs' => $songRepository->findBy([], ['createdAt' => 'DESC'], 10),
            'last_artists' => $artistRepository->findBy([], ['createdAt' => 'DESC'], 10),
        ]);
    }
}