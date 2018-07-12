<?php

namespace App\Controller;

use App\Entity\Style;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\Response;

class StyleController extends Controller
{
    /**
     * @param int $id
     * @param string $slug
     * @return Response
     */
    public function home(int $id, string $slug, SongRepository $songRepository)
    {
        /** @var Style $style */
        $style = $this->getDoctrine()->getRepository(Style::class)->find($id);

        if(!$style) {
            throw $this->createNotFoundException('Sorry, this style does not exist');
        }

        $slugify = new Slugify();
        if($slug != $slugify->slugify($style->getName())) {
            return $this->redirectToRoute(
                'style_home', ['id' => $style->getId(), 'slug' => $slugify->slugify($style->getName())]
            );
        }

        return $this->render('pages/style/index.html.twig', [
            'last_songs' => $songRepository->getLastByStyle($id),
            'most_rated_songs' => $songRepository->getMostRatedByStyle($id, 20),
        ]);
    }

}