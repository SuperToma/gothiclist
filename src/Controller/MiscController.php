<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MiscController extends Controller
{
    /**
     *
     */
    public function contact()
    {
        return $this->render('pages/misc/contact.html.twig', []);

    }

}