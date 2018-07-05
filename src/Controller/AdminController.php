<?php

namespace App\Controller;

use App\Entity\Song;
use App\Repository\SongRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @param SongRepository $songRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(SongRepository $songRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be administrator to access this page');

        return $this->render('pages/admin/index.html.twig', [
            'last_songs' => $songRepository->getLast(30)
        ]);
    }

    public function songValidated(Request $request, SongRepository $songRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be administrator to access this page');

        /** @var Song $song */
        $song = $songRepository->find($request->get('id'));
        $song->setValidated($request->get('validated'));
        $this->getDoctrine()->getManager()->persist($song);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(true);
    }

}