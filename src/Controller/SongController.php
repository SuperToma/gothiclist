<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class SongController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        $this->denyAccessUnlessGranted(
            'ROLE_USER',
            null,
            'You need to be connected to access this page.'
        );

        if($request->getMethod() === 'POST') {
            /** @var $user User */
            $user = $this->getUser();

            dump($request->get('artist_group_id'));
            die('posting');
        } else {
            return $this->render('pages/song/add.html.twig', []);
        }
    }
}