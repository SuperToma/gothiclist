<?php

namespace App\Controller;

use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->denyAccessUnlessGranted(
            'ROLE_ADMIN', null, 'You need to be administrator to access this page'
        );
    }

    /**
     *
     */
    public function home()
    {
        //Search last 20 songs added
        $songs = $this->getDoctrine()->getRepository(Song::class)->findBy(

        );
    }

}