<?php

namespace App\Controller;

use App\Entity\Release;
use App\Entity\Song;
use App\Entity\Style;
use App\Generator\VideoGenerator;
use App\Repository\SongRepository;
use App\Uploader\DailymotionUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @param SongRepository $songRepository
     * @return Response
     */
    public function home(SongRepository $songRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be administrator to access this page');

        return $this->render('pages/admin/index.html.twig', [
            'last_songs' => $songRepository->getLast(100)
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
     * @param DailymotionUploader $dailymotionUploader
     * @return JsonResponse
     * @throws \DailymotionApiException
     * @throws \DailymotionAuthRequiredException
     */
    public function uploadCover(Request $request, DailymotionUploader $dailymotionUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be administrator to access this page');

        /** @var UploadedFile $cover */
        $cover = $request->files->get('cover');
        $idSong = $request->get('id');

        if(!$cover) {
            return $this->json(['message' => 'Cover is missing'], 400);
        }

        if($cover->getMimeType() !== 'image/jpeg') {
            return $this->json(['message' => 'Invalid file'], 400);
        }

        if(!$idSong) {
            return $this->json(['message' => 'Id is missing'], 400);
        }

        /** @var Song $song */
        $song = $this->getDoctrine()->getRepository(Song::class)->find($idSong);
        if(empty($song)) {
            return $this->json(['message' => 'Invalid Id'], 400);
        }

        $cover->move(Release::COVERS_DIR, $song->getRelease()->getCoverFileName());

        if($song->hasMp3()) {
            $videoGenerator = new VideoGenerator($song);
            $videoGenerator->generateVideo();

            $dailymotionId = $dailymotionUploader->uploadSong($song, $videoGenerator->getVideoPath());

            $videoGenerator->deleteVideoFile();

            return $this->json([
                'file' => '/'.$song->getRelease()->getCoverPath(),
                'dailymotionId' => $dailymotionId
            ]);
        }

        return $this->json(['file' => '/'.$song->getRelease()->getCoverPath()]);
    }

    /**
     * @param Request $request
     * @param DailymotionUploader $dailymotionUploader
     * @return JsonResponse
     * @throws \DailymotionApiException
     * @throws \DailymotionAuthRequiredException
     */
    public function uploadMp3(Request $request, DailymotionUploader $dailymotionUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be administrator to access this page');

        /** @var UploadedFile $mp3 */
        $mp3 = $request->files->get('mp3');
        $idSong = $request->get('id');

        if(!$mp3) {
            return $this->json(['message' => 'Mp3 is missing'], 400);
        }
        
        if(!in_array($mp3->getMimeType(), ['audio/mpeg', 'application/octet-stream', 'application/x-font-gdos'])) {
            return $this->json(['message' => 'Invalid mp3 file'], 400);
        }

        if(!$idSong) {
            return $this->json(['message' => 'Id is missing'], 400);
        }

        /** @var Song $song */
        $song = $this->getDoctrine()->getRepository(Song::class)->find($idSong);
        if(empty($song)) {
            return $this->json(['message' => 'Invalid id'], 400);
        }

        $mp3->move(Song::MP3_DIR, $song->getMp3FileName());

        if($song->getRelease()->hasCover()) {
            $videoGenerator = new VideoGenerator($song);
            $videoGenerator->generateVideo();

            $dailymotionId = $dailymotionUploader->uploadSong($song, $videoGenerator->getVideoPath());

            $videoGenerator->deleteVideoFile();

            return $this->json(['message' => 'Success', 'dailymotionId' => $dailymotionId]);
        }

        return $this->json(['message' => 'Success']);
    }

}