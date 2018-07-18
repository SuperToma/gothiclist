<?php

namespace App\Controller;

use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @param SongRepository $songRepository
     * @return Response
     */
    public function index(SongRepository $songRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'last_songs' => $songRepository->getLast(10),
            'most_rated_songs' => $songRepository->getMostRated(),
        ]);
    }
}