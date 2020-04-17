<?php

namespace App\Uploader;

use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class DailymotionUploader
 * @package App\Uploader
 */
class DailymotionUploader extends AbstractController
{
    /** string $dmApiKey */
    protected $dmApiKey;

    /** string $dmApiSecret */
    protected $dmApiSecret;

    /** string $dmUserLogin */
    protected $dmUserLogin;

    /** string $dmUserPassword */
    protected $dmUserPassword;

    /**
     * AdminController constructor.
     * @param $dmApiKey
     * @param $dmApiSecret
     * @param $dmUserLogin
     * @param $dmUserPassword
     */
    public function __construct($dmApiKey, $dmApiSecret, $dmUserLogin, $dmUserPassword)
    {
        $this->dmApiKey = $dmApiKey;
        $this->dmApiSecret = $dmApiSecret;
        $this->dmUserLogin = $dmUserLogin;
        $this->dmUserPassword = $dmUserPassword;
    }

    /**
     * @return string
     */
    protected function getDmApiKey()
    {
        return $this->dmApiKey;
    }

    /**
     * @return string
     */
    protected function getDmApiSecret()
    {
        return $this->dmApiSecret;
    }

    /**
     * @return string
     */
    protected function getDmUserLogin()
    {
        return $this->dmUserLogin;
    }

    /**
     * @return string
     */
    protected function getDmUserPassword()
    {
        return $this->dmUserPassword;
    }

    /**
     * @param Song $song
     * @param string $videoPath
     * @return mixed
     * @throws \DailymotionApiException
     * @throws \DailymotionAuthRequiredException
     */
    public function uploadSong(Song $song, string $videoPath)
    {
        $dmApi = new \Dailymotion();
        $dmApi->setGrantType(
            \Dailymotion::GRANT_TYPE_PASSWORD,
            $this->getDmApiKey(),
            $this->getDmApiSecret(),
            ['manage_videos', 'userinfo'],
            ['username' => $this->getDmUserLogin(), 'password' => $this->getDmUserPassword()]
        );

        $videoUrl = $dmApi->uploadFile($videoPath);

        //Format song styles
        $songStyles = $song->getRelease()->getStyles();
        foreach($songStyles as $k => $style) {
            $songStyles[$k] = str_replace(',', ' ', $style->getName());
        }

        $songUrl = $this->generateUrl('song_home', [
            'id' => $song->getId(),
            'slug' => $song->getSlug()
        ], UrlGeneratorInterface::ABSOLUTE_URL
        );

        //Video object creation
        $result = $dmApi->post('/videos', [
                'fields'              => 'id',
                'url'                 => $videoUrl,
                'title'               => $song->getArtist()->getName().' - '.$song->getTitle(),
                'description'         => $song->getTitle().". \n".
                    "From the album: ".$song->getRelease()->getTitle()."\n".
                    "By: ".$song->getArtist()->getName()."\n\n".
                    "Rate & comment on: \n".$songUrl,
                'channel'             => 'music',
                'tags'                => implode(',', $songStyles),
                'published'           => true,
            ]
        );

        $song->setDailymotionId($result['id']);
        $this->getDoctrine()->getManager()->persist($song);
        $this->getDoctrine()->getManager()->flush();

        return $result['id'];
    }

}