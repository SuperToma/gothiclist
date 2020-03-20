<?php

namespace App\Controller;

use App\Entity\Release;
use App\Entity\Song;
use App\Entity\Style;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends Controller
{
    /** string $coversDirectory */
    private $coversDirectory;

    /** string $mp3Directory */
    private $mp3Directory;

    /**
     * AdminController constructor.
     * @param $coversDirectory
     */
    public function __construct($coversDirectory, $mp3Directory)
    {
        $this->coversDirectory = $coversDirectory;
        $this->mp3Directory = $mp3Directory;
    }

    /**
     * @return string
     */
    public function getCoversDirectory()
    {
        return $this->coversDirectory;
    }

    /**
     * @return string
     */
    public function getMp3Directory()
    {
        return $this->mp3Directory;
    }

    /**
     * @param SongRepository $songRepository
     * @return Response
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
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function releaseStyle(int $idRelease, int $idStyle)
    {
        $release = $this->getDoctrine()->getRepository(Release::class)->find($idRelease);
        if(empty($release)) {
            return $this->json(false, 400);
        }

        $style = $this->getDoctrine()->getRepository(Style::class)->find($idStyle);
        if(empty($style)) {
            return $this->json(false, 400);
        }

        if($release->hasStyle($idStyle)) {
            //$release->addStyle($style->getName());
        } else {

        }

        return $this->json(true);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadCover(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be administrator to access this page');

        /** @var UploadedFile $cover */
        $cover = $request->files->get('cover');
        $idRelease = $request->get('id');

        if(!$cover) {
            return $this->json(['message' => 'Cover is missing'], 400);
        }

        if($cover->getMimeType() !== 'image/jpeg') {
            return $this->json(['message' => 'Invalid file'], 400);
        }

        if(!$idRelease) {
            return $this->json(['message' => 'Id is missing'], 400);
        }

        $release = $this->getDoctrine()->getRepository(Release::class)->find($idRelease);
        if(empty($release)) {
            return $this->json(['message' => 'Invalid release'], 400);
        }

        $fileName = $idRelease.'-'.$release->getSlug().'.jpg';
        $cover->move($this->getCoversDirectory(), $fileName);

        return $this->json(['file' => $fileName]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadMp3(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be administrator to access this page');

        /** @var UploadedFile $mp3 */
        $mp3 = $request->files->get('mp3');
        $idSong = $request->get('id');

        if(!$mp3) {
            return $this->json(['message' => 'Mp3 is missing'], 400);
        }

        if($mp3->getMimeType() !== 'audio/mpeg') {
            return $this->json(['message' => 'Invalid file'], 400);
        }

        if(!$idSong) {
            return $this->json(['message' => 'Id is missing'], 400);
        }

        /** @var Song $song */
        $song = $this->getDoctrine()->getRepository(Song::class)->find($idSong);
        if(empty($song)) {
            return $this->json(['message' => 'Invalid id'], 400);
        }

        $mp3->move($this->getMp3Directory(), $idSong.'-'.$song->getArtist()->getSlug().'--'.$song->getSlug().'.mp3');

        return $this->json(['message' => 'Success']);
    }

}