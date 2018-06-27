<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StyleController extends Controller
{
    /**
     *
     */
    public function home()
    {
        $songRepository = $this->getDoctrine()->getRepository(Song::class);

        //Search last 20 songs added
        $songs = $songRepository->findBy([], ['createdAt' => 'DESC'], 20);
    }

}