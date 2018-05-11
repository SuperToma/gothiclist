<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SongController extends Controller
{
    public function add()
    {

        return $this->render('pages/song/add.html.twig', [

        ]);
    }
}