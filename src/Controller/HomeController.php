<?php

namespace App\Controller;

use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @param SongRepository $songRepository
     * @return Response
     */
    public function index(SongRepository $songRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'last_songs' => $songRepository->getLast(150, ['validated' => true]),
            'most_rated_songs' => $songRepository->getMostRated(),
        ]);
    }
}