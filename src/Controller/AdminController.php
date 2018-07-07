<?php

namespace App\Controller;

use App\Entity\Release;
use App\Entity\Song;
use App\Entity\Style;
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

    /**
     * @param Request $request
     * @param SongRepository $songRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
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

    /**
     * @param int $idRelease
     * @param int $idStyle
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function releaseStyle(int $idRelease, int $idStyle)
    {
        $release = $this->getDoctrine()->getRepository(Release::class)->find($idRelease);
        if(empty($release)) {
            return $this->json(false, 422);
        }

        $style = $this->getDoctrine()->getRepository(Style::class)->find($idStyle);
        if(empty($style)) {
            return $this->json(false, 422);
        }

        if($release->hasStyle($idStyle)) {
            //$release->addStyle($style->getName());
        } else {

        }

        return $this->json(true);
    }

}