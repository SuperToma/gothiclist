<?php

namespace App\Generator;

use App\Entity\Song;

class ImageGenerator
{
    const VIDEOS_BACKGROUNDS_DIR = '../assets/videos-backgrounds/';

    const BACKGROUND_WIDTH = 2468;
    const BACKGROUND_HEIGHT = 1396;

    /** @var Song */
    protected $song;

    /**
     * ImageGenerator constructor.
     * @param Song $song
     */
    public function __construct(Song $song)
    {
        $this->song = $song;
    }

    /**
     * @return string
     */
    public function getSongVideoBackgroundPath()
    {
        return self::VIDEOS_BACKGROUNDS_DIR.
            $this->song->getId().'-'.
            $this->song->getArtist()->getSlug().'--'.
            $this->song->getSlug().'.jpg';
    }

    /**
     * @return bool
     */
    protected function deleteSongVideoBackgroundFile()
    {
        if(file_exists($this->getSongVideoBackgroundPath())) {
            return unlink($this->getSongVideoBackgroundPath());
        }

        return true;
    }

    /**
     * @param Song $song
     * @return string background file path
     * @throws \ImagickException
     */
    public function generateSongVideoBackgroundFile()
    {
        $coverWidth = 950;

        // Background
        $image = new \Imagick($this->song->getRelease()->getCoverPath());
        $image->blurImage(8,8);
        $image->scaleImage(self::BACKGROUND_WIDTH);
        $image->cropThumbnailImage(self::BACKGROUND_WIDTH, self::BACKGROUND_HEIGHT);
        $image->brightnessContrastImage(-30, -50);

        // Small cover
        $cover = new \Imagick($this->song->getRelease()->getCoverPath());
        $cover->scaleImage($coverWidth, 0);

        $image->compositeImage($cover, \Imagick::COMPOSITE_DEFAULT, 150, 150);

        // Song infos
        $draw = new \ImagickDraw();
        $draw->setFont('fonts/vtRemingtonPortable.ttf');
        $draw->setFontSize( 80 );
        $draw->setFillColor('white');
        $image->annotateImage($draw, 1200, 450, 0, $this->song->getTitle());

        $draw->setFontSize( 70 );
        $image->annotateImage($draw, 1200, 700, 0, $this->song->getArtist()->getName());
        $draw->setFontSize( 50 );
        $image->annotateImage($draw, 1200, 830, 0, $this->song->getRelease()->getTitle());

        // Gothiclist signature
        /* $draw->setFont('fonts/civitype.ttf');
        $draw->setFontSize( 80 );
        $image->annotateImage($draw, 2100, 1300, 0, 'GothicList.com');

        header('Content-Type: image/' . $image->getImageFormat());
        echo $image->getImageBlob();
        die(); */

        $handle = fopen($this->getSongVideoBackgroundPath(), 'w+');

        $image->writeImageFile($handle);

        return $this->getSongVideoBackgroundPath();
    }
}