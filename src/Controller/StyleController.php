<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StyleController extends Controller
{
    /**
     * @param int $id
     * @param string $slug
     */
    public function home(int $id, string $slug)
    {
        $songRepository = $this->getDoctrine()->getRepository(Song::class);

        //Search last 20 songs added
        $songs = $songRepository->findBy([], ['createdAt' => 'DESC'], 20);
    }

}