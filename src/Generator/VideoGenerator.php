<?php

namespace App\Generator;

use App\Entity\Song;

class VideoGenerator
{
    const VIDEOS_DIR = '../assets/videos/';

    /** @var Song */
    protected $song;

    /** @var ImageGenerator **/
    protected $imageGenerator;

    /**
     * VideoGenerator constructor.
     * @param Song $song
     */
    public function __construct(Song $song)
    {
        $this->song = $song;
        $this->imageGenerator = new ImageGenerator($song);
    }

    public function generateVideo()
    {
        $this->imageGenerator->generateSongVideoBackgroundFile();

        $this->generateVideoFile(false);
    }

    /**
     * @return string
     */
    public function getVideoPath()
    {
        return self::VIDEOS_DIR.
            $this->song->getId().'-'.
            $this->song->getArtist()->getSlug().'--'.
            $this->song->getSlug().'.mkv';
    }

    /**
     * @return bool
     */
    public function deleteVideoFile()
    {
        if(file_exists($this->getVideoPath())) {
            return unlink($this->getVideoPath());
        }

        return true;
    }

    /**
     * Generates a video from mp3 & cover
     *
     * For a 6min06 video :
     *  - Without compress: 96Mb
     *  - With compress: 11mb
     *
     * @param bool $compressVideo
     * exec() doesn't return command status...
     *
     * @param bool $compressVideo
     * @throws \ImagickException
     */
    protected function generateVideoFile($compressVideo = true)
    {
        exec('/usr/bin/ffmpeg -y -loop 1 -framerate 1 \
            -i '.$this->imageGenerator->generateSongVideoBackgroundFile().' \
            -i '.$this->song->getMp3Path().' \
            '.($compressVideo ? '-c:v libx264 -crf 0 -c:a copy' : '-c copy').' -shortest \
            '.$this->getVideoPath());
    }
}