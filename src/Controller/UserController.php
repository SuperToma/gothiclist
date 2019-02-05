<?php

namespace App\Controller;

use App\Entity\Song;
use App\Entity\User;
use App\Entity\VoteSong;
use App\Repository\UserRepository;
use App\Repository\VoteSongRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function privateAccount(Request $request, UserRepository $userRepository)
    {
        $this->denyAccessUnlessGranted(
            'ROLE_USER', null, 'You need to be connected to access this page.'
        );

        if ($request->isMethod('POST')) {

            //Check email valid
            if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL) === false) {
                $this->addFlash(
                    'danger',
                    'Your email is not valid : <strong>'.$request->get('email').'</strong>'
                );

                return $this->render('pages/user/private_account.html.twig', []);
            }

            //Check email not already exists
            $emailAlreadyUsed = $userRepository->emailAlreadyUsed($request->get('email'), $this->getUser()->getId());
            if($emailAlreadyUsed) {
                $this->addFlash(
                    'danger',
                    'Sorry, this email is already in use by somebody else : <strong>'.$request->get('email').'</strong>'
                );

                return $this->render('pages/user/private_account.html.twig', []);
            }


            //Check username not empty
            if(empty(trim($request->get('nickname')))) {
                $this->addFlash('danger', 'Please enter a username');

                return $this->render('pages/user/private_account.html.twig', []);
            }

            //Check nickname not already exits
            $nicknameAlreadyUsed = $userRepository->nicknameAlreadyUsed($request->get('nickname'), $this->getUser()->getId());
            if($nicknameAlreadyUsed) {
                $this->addFlash(
                    'danger',
                    'Sorry, this nickname is already in use by somebody else : <strong>'.$request->get('nickname').'</strong>'
                );

                return $this->render('pages/user/private_account.html.twig', []);
            }

            /** @var User $user */
            $user = $this->getUser();
            $user->setNickname($request->get('nickname'))
                ->setNicknameCanonical((new Slugify())->slugify($request->get('nickname')))
                ->setEmailCanonical($user->getEmailCanonical())
                ->setEmail($request->get('email'))
                ->setName($request->get('name'))
                ->setFirstname($request->get('firstname'));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Your informations have been saved');
        }

        return $this->render('pages/user/private_account.html.twig', [

        ]);
    }

    /**
     * @param int $id
     * @param string $nickname
     * @param VoteSongRepository $voteSongRepository
     * @return RedirectResponse|Response
     */
    public function publicProfile(int $id, string $nickname, VoteSongRepository $voteSongRepository)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if(!$user || !$user->isValid()) {
            throw $this->createNotFoundException('Sorry, this user does not exist');
        }

        if($user->getNicknameCanonical() !== $nickname) {
            return $this->redirectToRoute('user_public_page', ['id' => $id, 'nickname' => $user->getNicknameCanonical()]);
        }

        $lastSongsAdded = $this->getDoctrine()
            ->getRepository(Song::class)
            ->findBy(['user' => $user], ['createdAt' => 'DESC'], 10);
        foreach($lastSongsAdded as &$song) {
            $song->nbVotes = $voteSongRepository->count(['song' => $song]);
        }

        $lastVotesSong = $this->getDoctrine()
            ->getRepository(VoteSong::class)
            ->findBy(['user' => $user], ['createdAt' => 'DESC'], 10);
        foreach($lastVotesSong as &$vote) {
            $vote->getSong()->nbVotes = $voteSongRepository->count(['song' => $vote->getSong()]);
        }

        return $this->render('pages/user/public_profile.html.twig', [
            'user' => $user,
            'last_songs_added' => $lastSongsAdded,
            'last_votes_song' => $lastVotesSong
        ]);

    }
}