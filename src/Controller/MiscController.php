<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MiscController extends Controller
{
    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Contact form'))
            ->setFrom('gothiclist@gmail.com')
            ->setTo('gothiclist@gmail.com')
            ->setBody(''.
                '<b>Email : </b>' .$request->get('email').'<br />'.
                '<b>Name : </b>' .$request->get('name').'<br />'.
                '<b>Firstname : </b>' .$request->get('firstname').'<br />'.
                '<b>Text : </b>' .$request->get('text').'<br />'
            );

        $mailer->send($message);

        $this->addFlash('success', 'Thank you for your message, we will try to reply you soon');

        return $this->render('pages/misc/contact.html.twig', []);

    }

}