<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Song;
use App\Entity\Style;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class MiscController
 * @package App\Controller
 */
class MiscController extends AbstractController
{
    public function terms() {
        return $this->render('pages/misc/terms.html.twig');
    }

    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        if($request->getMethod() === 'POST') {
            if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL) === false) {
                $this->addFlash('danger', 'Your email is not valid : <strong>' . $request->get('email') . '</strong>');

                return $this->render('pages/misc/contact.html.twig', []);
            }

            if (mb_strlen($request->get('text')) < 10) {
                $this->addFlash('danger', 'Your message is too short');

                return $this->render('pages/misc/contact.html.twig', []);
            }

            if (empty($request->get('name')) || empty($request->get('firstname'))) {
                $this->addFlash('danger', 'Please provide your name & firstname');

                return $this->render('pages/misc/contact.html.twig', []);
            }

            $message = (new \Swift_Message('Contact form'))
                ->setFrom('gothiclist@gmail.com')
                ->setTo('gothiclist@gmail.com')
                ->setBody('<html><body>' .
                    'Email : ' . $request->get('email') . "\n" .
                    'Name : ' . $request->get('name') . "\n" .
                    '<b>Firstname : </b>' . $request->get('firstname') . '<br />' .
                    '<b>Text : </b>' . $request->get('text') . '<br />
                    </body></html>'
                );

            $resp = $mailer->send($message);

            if($resp === 1) {
                $this->addFlash('success', 'Thank you for your message, we will try to reply you soon');
            } else {
                $this->addFlash('danger', 'Sorry, an error occurred while sending the email');
            }
        }

        return $this->render('pages/misc/contact.html.twig');
    }

    public function sitemap(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = ['loc' => $hostname.'/'];
        $urls[] = ['loc' => $this->generateUrl('misc_contact', [], UrlGeneratorInterface::ABSOLUTE_URL)];
        $urls[] = ['loc' => $this->generateUrl('misc_terms', [], UrlGeneratorInterface::ABSOLUTE_URL)];

        $artists = $em->getRepository(Artist::class)->findAll();
        /** @var Artist $artist */
        foreach ($artists as $artist) {
            $urls[] = [
                'loc' => $this->generateUrl('artist_home', [
                    'id' => $artist->getId(),
                    'slug' => $artist->getSlug()
                ], UrlGeneratorInterface::ABSOLUTE_URL
            )];
        }

        $styles = $em->getRepository(Style::class)->findAll();
        /** @var Style $style */
        foreach ($styles as $style) {
            $urls[] = [
                'loc' => $this->generateUrl('style_home', [
                    'idStyle' => $style->getId(),
                    'slug' => $style->getSlug()
                ], UrlGeneratorInterface::ABSOLUTE_URL
            )];
        }

        $songs = $em->getRepository(Song::class)->findAll();
        /** @var Song $song */
        foreach ($songs as $song) {
            $urls[] = [
                'loc' => $this->generateUrl('song_home', [
                    'id' => $song->getId(),
                    'slug' => $song->getSlug()
                ], UrlGeneratorInterface::ABSOLUTE_URL
                )];
        }

        $users = $em->getRepository(User::class)->findAll();
        /** @var User $user */
        foreach ($users as $user) {
            $urls[] = [
                'loc' => $this->generateUrl('user_public_page', [
                    'id' => $user->getId(),
                    'nickname' => $user->getSlug()
                ], UrlGeneratorInterface::ABSOLUTE_URL
            )];
        }

        return $this->render('pages/misc/sitemap.xml.twig', ['urls' => $urls]);
    }

}