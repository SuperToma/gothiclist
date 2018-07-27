<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MiscController
 * @package App\Controller
 */
class MiscController extends Controller
{
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

        return $this->render('pages/misc/contact.html.twig', []);

    }

}