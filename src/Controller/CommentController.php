<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\CommentArtist;
use App\Entity\CommentSong;
use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class CommentController
 * @package App\Controller
 */
class CommentController extends AbstractController
{
    public function add(Request $request): JsonResponse
    {
        if(empty($this->getUser())) {
            return $this->json(['msg' => 'You need to log in to post a comment'], Response::HTTP_UNAUTHORIZED);
        }

        if(empty($request->get('comment'))) {
            return $this->json(['msg' => 'Sorry, you sent an empty message'], Response::HTTP_LENGTH_REQUIRED);
        }

        if(empty($request->get('id')) || !is_numeric($request->get('id'))) {
            return $this->json(['msg' => 'Sorry, you sent a bad request'], Response::HTTP_BAD_REQUEST);
        }

        switch($request->get('type')) {
            case 'song':
                $songRepository = $this->getDoctrine()->getRepository(Song::class);

                $song = $songRepository->findOneBy(['id' => $request->get('id')]);
                if(empty($song)) {
                    return $this->json(['msg' => 'Sorry, you sent a bad request'], Response::HTTP_BAD_REQUEST);
                }

                $comment = new CommentSong();
                $comment->setSong($song);
                break;
            case 'artist':
                $artistRepository = $this->getDoctrine()->getRepository(Artist::class);

                $artist = $artistRepository->findOneBy(['id' => $request->get('id')]);
                if(empty($artist)) {
                    return $this->json(['msg' => 'Sorry, you sent a bad request'], Response::HTTP_BAD_REQUEST);
                }

                $comment = new CommentArtist();
                $comment->setArtist($artist);
                break;
            default:
                return $this->json(['msg' => 'Sorry, you sent a bad request'], Response::HTTP_BAD_REQUEST);
                break;
        }

        $comment
            ->setUser($this->getUser())
            ->setText($request->get('comment'));

        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();

        return $this->json('Comment added');

    }
}